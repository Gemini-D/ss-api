<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace App\Service;

use App\Constants\ContentType;
use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use App\Model\Content;
use App\Schema\ContentListSchema;
use App\Schema\ContentSchema;
use App\Service\Dao\ContentDao;
use App\Service\Dao\SecretDao;
use App\Service\Formatter\ContentFormatter;
use App\Service\SubService\Encrypter;
use App\Service\SubService\UserAuth;
use Han\Utils\Service;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;
use JetBrains\PhpStorm\ArrayShape;
use Throwable;

class ContentService extends Service
{
    #[Inject]
    protected ContentDao $dao;

    #[Inject]
    protected Encrypter $encrypter;

    #[Inject]
    protected ContentFormatter $formatter;

    public function info(int $id, UserAuth $userAuth): ContentSchema
    {
        $userAuth->build();

        $model = $this->dao->first($id, true);
        if ($model->user_id !== $userAuth->getUserId() && ! di()->get(SecretDao::class)->isShare($userAuth->getUserId(), $model->secret_id)) {
            throw new BusinessException(ErrorCode::PERMISSION_DENY);
        }

        return new ContentSchema($model, true);
    }

    public function list(int $secretId, UserAuth $userAuth): ContentListSchema
    {
        $userAuth->build();

        $secret = di()->get(SecretDao::class)->first($secretId, true);
        if ($secret->user_id !== $userAuth->getUserId()) {
            throw new BusinessException(ErrorCode::PERMISSION_DENY);
        }

        if ($secret->share_id > 0) {
            $secret = di()->get(SecretDao::class)->first($secret->share_id, true);
        }

        $contents = $this->dao->findBySecretId($secret->id);

        $result = $this->formatter->formatList($contents);

        return new ContentListSchema($contents->count(), $result);
    }

    public function save(
        int $id,
        #[ArrayShape([
            'secret_id' => 'int',
            'title' => 'string',
            'content' => 'string',
            'type' => 'int',
        ])]
        array $input,
        UserAuth $userAuth
    ): bool {
        $userAuth->build();

        $type = ContentType::from($input['type']);
        $type->checkContent($input['content']);

        $secret = di()->get(SecretDao::class)->first((int) $input['secret_id'], true);
        if ($secret->share_id > 0) {
            throw new BusinessException(ErrorCode::CONTENT_CANNOT_SAVE_CAUSED_BY_SHARE);
        }
        if ($secret->user_id !== $userAuth->getUserId()) {
            throw new BusinessException(ErrorCode::PERMISSION_DENY);
        }

        if ($id > 0) {
            $model = $this->dao->first($id, true);
            if ($model->user_id !== $userAuth->getUserId()) {
                throw new BusinessException(ErrorCode::PERMISSION_DENY);
            }

            if ($model->secret_id !== $secret->id) {
                throw new BusinessException(ErrorCode::PERMISSION_DENY);
            }
        } else {
            $model = new Content();
            $model->user_id = $userAuth->getUserId();
            $model->secret_id = $secret->id;
        }

        $model->title = $input['title'];
        $model->type = ContentType::from((int) ($input['type'] ?? 0));
        $model->content = $this->encrypter->encrypt($input['content'], $userAuth->getSecret());

        $user = di()->get(YsUserService::class)->save($model, $input);

        Db::beginTransaction();
        try {
            $model->save();
            $user?->save();
            Db::commit();
        } catch (Throwable $throwable) {
            Db::rollBack();
            throw $throwable;
        }

        return true;
    }
}

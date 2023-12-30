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
use App\Service\Dao\ContentDao;
use App\Service\Dao\SecretDao;
use App\Service\SubService\UserAuth;
use Han\Utils\Service;
use Hyperf\Di\Annotation\Inject;
use JetBrains\PhpStorm\ArrayShape;

class ContentService extends Service
{
    #[Inject]
    protected ContentDao $dao;

    public function save(
        int $id,
        #[ArrayShape(['secret_id' => 'int', 'title' => 'string', 'content' => 'string'])]
        array $input,
        UserAuth $userAuth
    ) {
        $userAuth->build();

        $secret = di()->get(SecretDao::class)->first((int) $input['secret_id'], true);
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
            $model->type = ContentType::TEXT;
            $model->secret_id = $secret->id;
        }

        $model->title = $input['title'];
        $model->content = $input['content'];
        return $model->save();
    }
}

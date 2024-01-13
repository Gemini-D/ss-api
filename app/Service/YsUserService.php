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
use App\Constants\Status;
use App\Model\Content;
use App\Model\YsUser;
use App\Service\Dao\YsUserDao;
use App\Service\SubService\Mihoyo;
use Han\Utils\Service;
use Hyperf\Di\Annotation\Inject;
use JetBrains\PhpStorm\ArrayShape;

class YsUserService extends Service
{
    #[Inject]
    protected YsUserDao $dao;

    #[Inject]
    protected Mihoyo $api;

    public function save(
        Content $content,
        #[ArrayShape(['content' => 'string'])]
        array $input
    ): ?YsUser {
        if ($content->type !== ContentType::YUAN_SHEN) {
            return null;
        }

        [$mid, $ticket] = explode(PHP_EOL, $input['content']);

        $model = $this->dao->firstByContentId($content->id);
        if (! $model) {
            $model = new YsUser();
            $model->content_id = $content->id;
        }

        $model->mid = $mid;

        $res = $this->api->getMuLtiTokenByLoginTicket($model->mid, $ticket);

        $model->stoken = $res['stoken'];

        $res = $this->api->getActionTicketBySToken($model->stoken, $model->mid);

        $res = $this->api->getUserGameRoles($res['ticket']);

        $model->uid = $res['game_uid'];

        $res = di()->get(Mihoyo::class)->getAuthKey($model->uid, $model->stoken, $model->mid);

        $model->auth_key = $res['authkey'];
        $model->status = Status::YES;

        return $model;
    }
}

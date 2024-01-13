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

namespace App\Service\Dao;

use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use App\Model\YsUser;
use Han\Utils\Service;

class YsUserDao extends Service
{
    public function first(int $id, bool $throw = false): ?YsUser
    {
        $model = YsUser::findFromCache($id);
        if (! $model && $throw) {
            throw new BusinessException(ErrorCode::YS_USER_NOT_EXIST);
        }

        return $model;
    }

    public function firstByContentId(?int $contentId): ?YsUser
    {
        if ($contentId === null) {
            return null;
        }

        return YsUser::query()->where('content_id', $contentId)->first();
    }
}

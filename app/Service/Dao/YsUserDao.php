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

use App\Model\YsUser;
use Han\Utils\Service;

class YsUserDao extends Service
{
    public function firstByContentId(int $contentId): ?YsUser
    {
        return YsUser::query()->where('content_id', $contentId)->first();
    }
}

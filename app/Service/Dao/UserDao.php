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

use App\Model\User;
use Han\Utils\Service;

class UserDao extends Service
{
    public function firstOrCreate(string $openid): ?User
    {
        $model = $this->firstByOpenId($openid);
        if (! $model) {
            $model = new User();
            $model->openid = $openid;
            $model->save();
        }

        return $model;
    }

    public function firstByOpenId(string $openid): ?User
    {
        return User::query()->where('openid', $openid)->first();
    }
}

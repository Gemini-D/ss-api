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

use App\Model\Secret;
use Han\Utils\Service;

class SecretDao extends Service
{
    public function create(string $secret, int $userId): Secret
    {
        $secret = md5($secret);

        $model = new Secret();
        $model->secret = $secret;
        $model->user_id = $userId;
        $model->save();

        return $model;
    }
}

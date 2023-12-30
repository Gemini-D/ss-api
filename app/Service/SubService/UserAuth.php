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

namespace App\Service\SubService;

use App\Model\User;
use Hyperf\Codec\Json;
use Hyperf\Redis\Redis;
use Hyperf\Support\Traits\StaticInstance;

class UserAuth
{
    use StaticInstance;

    public const X_TOKEN = 'x-token';

    public const PREFIX = 'auth:';

    protected int $userId = 0;

    protected string $token = '';

    public function init(User $user): static
    {
        $token = md5($user->id . ':' . uniqid());

        di()->get(Redis::class)->set(self::PREFIX . $token, Json::encode(['id' => $user->id], 86400));

        $this->token = $token;
        $this->userId = $user->id;

        return $this;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getToken(): string
    {
        return $this->token;
    }
}

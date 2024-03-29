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

use App\Constants\ErrorCode;
use App\Exception\BusinessException;
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

    protected ?string $secret = null;

    public function init(User $user): static
    {
        $token = md5($user->id . ':' . uniqid());

        di()->get(Redis::class)->set(self::PREFIX . $token, Json::encode(['id' => $user->id]), 86400);

        $this->token = $token;
        $this->userId = $user->id;

        return $this;
    }

    public function save(string $secret): static
    {
        $this->secret = md5('secret:' . $secret);

        di()->get(Redis::class)->set(
            self::PREFIX . $this->token,
            Json::encode(['id' => $this->userId, 'secret' => $this->secret]),
            86400
        );

        return $this;
    }

    public function build(): static
    {
        if (! $this->userId) {
            throw new BusinessException(ErrorCode::TOKEN_INVALID);
        }

        if (! $this->secret) {
            throw new BusinessException(ErrorCode::SECRET_INVALID);
        }

        return $this;
    }

    public function load(string $token): static
    {
        $res = di()->get(Redis::class)->get(self::PREFIX . $token);
        if (! $res) {
            throw new BusinessException(ErrorCode::TOKEN_INVALID);
        }

        $data = Json::decode($res);
        if (empty($data['id']) || ! is_int($data['id'])) {
            throw new BusinessException(ErrorCode::TOKEN_INVALID);
        }

        $this->userId = $data['id'];
        $this->secret = $data['secret'] ?? null;
        $this->token = $token;

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

    public function getSecret(): ?string
    {
        return $this->secret;
    }
}

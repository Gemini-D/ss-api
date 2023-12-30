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

namespace App\Schema;

use App\Model\User;
use Hyperf\Swagger\Annotation\Property;
use Hyperf\Swagger\Annotation\Schema;
use JsonSerializable;

#[Schema(title: '用户详情')]
class UserSchema implements JsonSerializable
{
    #[Property(property: 'id', title: '', type: 'int')]
    public ?int $id;

    #[Property(property: 'openid', title: '小程序 OpenID', type: 'string')]
    public ?string $openid;

    #[Property(property: 'created_at', title: '', type: 'string')]
    public ?string $createdAt;

    #[Property(property: 'updated_at', title: '', type: 'string')]
    public ?string $updatedAt;

    public function __construct(User $model)
    {
        $this->id = $model->id;
        $this->openid = $model->openid;
        $this->createdAt = $model->created_at?->toDateTimeString();
        $this->updatedAt = $model->updated_at?->toDateTimeString();
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            // 'openid' => $this->openid,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];
    }
}

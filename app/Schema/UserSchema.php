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

    #[Property(property: 'has_secret', title: '是否已经创建过密码', type: 'boolean')]
    public ?bool $hasSecret;

    #[Property(property: 'created_at', title: '', type: 'string')]
    public ?string $createdAt;

    #[Property(property: 'updated_at', title: '', type: 'string')]
    public ?string $updatedAt;

    public function __construct(User $model, ?bool $hasSecret = null)
    {
        $this->id = $model->id;
        $this->hasSecret = $hasSecret;
        $this->createdAt = $model->created_at?->toDateTimeString();
        $this->updatedAt = $model->updated_at?->toDateTimeString();
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'has_secret' => $this->hasSecret,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];
    }
}

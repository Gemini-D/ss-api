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

use Hyperf\Swagger\Annotation\Property;
use Hyperf\Swagger\Annotation\Schema;
use JsonSerializable;

#[Schema(title: 'LoginSchema')]
class LoginSchema implements JsonSerializable
{
    public function __construct(
        #[Property(property: 'token', title: 'Token', type: 'string')]
        public string $token
    ) {
    }

    public function jsonSerialize(): mixed
    {
        return [
            'token' => $this->token,
        ];
    }
}

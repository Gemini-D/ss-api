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

#[Schema(title: '消息数据')]
class MessageSchema implements JsonSerializable
{
    public function __construct(
        #[Property(property: 'message', title: '消息内容', type: 'string')]
        public string $message
    ) {
    }

    public function jsonSerialize(): mixed
    {
        return [
            'message' => $this->message,
        ];
    }
}

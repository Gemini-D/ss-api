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

#[Schema(title: 'SavedSchema')]
class SavedSchema implements JsonSerializable
{
    public function __construct(
        #[Property(property: 'saved', title: '是否保存成功', type: 'boolean')]
        public bool $saved
    ) {
    }

    public function jsonSerialize(): mixed
    {
        return [
            'saved' => $this->saved,
        ];
    }
}

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

use App\Constants\ContentType;
use Hyperf\Swagger\Annotation\Property;
use Hyperf\Swagger\Annotation\Schema;
use JsonSerializable;

#[Schema(title: '文本类型')]
class ContentTypeSchema implements JsonSerializable
{
    #[Property(property: 'label', title: 'label', type: 'string')]
    public string $label;

    #[Property(property: 'value', title: 'value', type: 'int')]
    public int $value;

    #[Property(property: 'block', title: 'block', type: 'boolean')]
    public bool $block = false;

    public function __construct(ContentType $type)
    {
        $this->label = $type->getName();
        $this->value = $type->value;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'label' => $this->label,
            'value' => $this->value,
            'block' => $this->block,
        ];
    }
}

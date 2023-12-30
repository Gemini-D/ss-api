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

use Hyperf\Swagger\Annotation\Items;
use Hyperf\Swagger\Annotation\Property;
use Hyperf\Swagger\Annotation\Schema;
use JsonSerializable;

#[Schema(title: '内容列表')]
class ContentListSchema implements JsonSerializable
{
    #[Property(property: 'list', title: '列表', type: 'array', items: new Items(ref: '#/components/schemas/ContentSchema'))]
    public array $list;

    #[Property(property: 'count', title: '总数', type: 'integer')]
    public int $count;

    public function __construct(int $count, array $list = [])
    {
        $this->count = $count;
        $this->list = $list;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'count' => $this->count,
            'list' => $this->list,
        ];
    }
}

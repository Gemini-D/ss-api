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

#[Schema(title: '抽卡记录')]
class YsGachaSchema implements JsonSerializable
{
    public function __construct(
        #[Property(property: 'name', title: '角色 or 武器名称', type: 'string')]
        public string $name,
        #[Property(property: 'type', title: '类型', type: 'string')]
        public string $type,
        #[Property(property: 'rank', title: '星级', type: 'int')]
        public int $rank,
        #[Property(property: 'num', title: '前置抽取数量', type: 'int')]
        public int $num,
    ) {
    }

    public function jsonSerialize(): mixed
    {
        return [
            'name' => $this->name,
            'type' => $this->type,
            'rank' => $this->rank,
            'num' => $this->num,
        ];
    }
}

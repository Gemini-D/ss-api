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

namespace App\Model;

use Carbon\Carbon;

/**
 * @property int $id
 * @property int $uid 原神 UID
 * @property int $gacha_type 祈愿类型
 * @property string $time 抽卡时间
 * @property string $name 角色or 武器名称
 * @property string $item_type 类型
 * @property int $rank_type 稀有度
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class YsGachaLog extends Model
{
    public bool $incrementing = false;

    /**
     * The table associated with the model.
     */
    protected ?string $table = 'ys_gacha_log';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = ['id', 'uid', 'gacha_type', 'time', 'name', 'item_type', 'rank_type', 'created_at', 'updated_at'];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'int', 'uid' => 'integer', 'gacha_type' => 'integer', 'rank_type' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}

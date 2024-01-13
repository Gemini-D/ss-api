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

namespace App\Constants;

use Hyperf\Database\Model\Builder;

enum GachaType: int
{
    /**
     * 新手池.
     */
    case NEW_USER = 100;

    /**
     * 常驻池.
     */
    case NORMAL = 200;

    /**
     * UP 角色池.
     */
    case ROLE = 301;

    /**
     * UP 角色池2.
     */
    case ROLE_2 = 400;

    /**
     * 武器池.
     */
    case WEAPON = 302;

    public static function enums(): array
    {
        return [
            self::NORMAL,
            self::ROLE,
            self::WEAPON,
        ];
    }

    public function appendQuery(Builder $query)
    {
        return match ($this) {
            self::ROLE, self::ROLE_2 => $query->whereIn('gacha_type', [self::ROLE, self::ROLE_2]),
            default => $query->where('gacha_type', $this)
        };
    }
}

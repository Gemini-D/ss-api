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
}

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

use function Hyperf\Support\env;

class Environment
{
    public static function environment(): string
    {
        return env('APP_ENV', 'prod');
    }

    public static function isLocal(): bool
    {
        return self::environment() === 'local';
    }
}

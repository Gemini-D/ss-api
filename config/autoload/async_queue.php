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
use Hyperf\AsyncQueue\Driver\RedisDriver;

return [
    'default' => [
        'driver' => RedisDriver::class,
        'channel' => '{queue}',
        'timeout' => 2,
        'retry_seconds' => 5,
        'handle_timeout' => 3600,
        'processes' => 1,
        'concurrent' => [
            'limit' => 2,
        ],
    ],
];

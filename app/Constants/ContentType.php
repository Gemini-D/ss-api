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

enum ContentType: int
{
    case TEXT = 0;
    case AUDIO = 1;
    case VIDEO = 2;
    case IMAGE = 3;

    public function getName(): string
    {
        return match ($this) {
            self::TEXT => '文本',
            self::AUDIO => '音频',
            self::VIDEO => '视频',
            self::IMAGE => '图片'
        };
    }
}

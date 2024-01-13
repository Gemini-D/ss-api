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

use App\Schema\ContentTypeSchema;

enum ContentType: int
{
    case TEXT = 0;
    case AUDIO = 1;
    case VIDEO = 2;
    case IMAGE = 3;
    case YUAN_SHEN = 4;

    public function getName(): string
    {
        return match ($this) {
            self::TEXT => '文本',
            self::AUDIO => '音频',
            self::VIDEO => '视频',
            self::IMAGE => '图片',
            self::YUAN_SHEN => '原神'
        };
    }

    public static function enums(): array
    {
        return [
            self::TEXT,
            self::AUDIO,
            self::VIDEO,
            self::IMAGE,
            self::YUAN_SHEN,
        ];
    }

    public static function all(): array
    {
        $result = [];
        foreach (self::enums() as $enum) {
            $result[] = new ContentTypeSchema($enum);
        }

        return $result;
    }
}

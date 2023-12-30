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

namespace App\Service\Formatter;

use App\Model\Content;
use App\Schema\ContentSchema;
use Han\Utils\Service;
use Hyperf\Database\Model\Collection;

class ContentFormatter extends Service
{
    /**
     * @param Collection<int, Content> $models
     */
    public function formatList(Collection $models): array
    {
        $result = [];
        foreach ($models as $model) {
            $result[] = new ContentSchema($model);
        }
        return $result;
    }
}

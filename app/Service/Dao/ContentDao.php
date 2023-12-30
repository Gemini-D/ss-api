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

namespace App\Service\Dao;

use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use App\Model\Content;
use Han\Utils\Service;

class ContentDao extends Service
{
    public function first(int $id, bool $throw = false): ?Content
    {
        $model = Content::findFromCache($id);
        if (! $model && $throw) {
            throw new BusinessException(ErrorCode::CONTENT_NOT_EXIST);
        }

        return $model;
    }
}

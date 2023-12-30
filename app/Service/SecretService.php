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

namespace App\Service;

use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use App\Schema\SecretSchema;
use App\Service\Dao\SecretDao;
use Han\Utils\Service;
use Hyperf\Di\Annotation\Inject;

class SecretService extends Service
{
    #[Inject]
    protected SecretDao $dao;

    public function create(string $secret, int $userId): bool
    {
        $this->dao->create($secret, $userId);

        return true;
    }

    public function check(string $secret, int $userId): ?SecretSchema
    {
        $model = $this->dao->firstBySecret($secret, $userId);
        if (! $model) {
            throw new BusinessException(ErrorCode::SECRET_NOT_EXIST);
        }

        return new SecretSchema($model);
    }
}

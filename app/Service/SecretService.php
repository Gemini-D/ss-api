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
}

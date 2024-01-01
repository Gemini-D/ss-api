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

use App\Schema\UserSchema;
use App\Service\Dao\SecretDao;
use App\Service\Dao\UserDao;
use Han\Utils\Service;
use Hyperf\Di\Annotation\Inject;

class UserService extends Service
{
    #[Inject]
    protected UserDao $dao;

    public function info(int $id): UserSchema
    {
        $model = $this->dao->first($id, true);

        $hasSecret = di()->get(SecretDao::class)->countByUserId($model->id);

        return new UserSchema($model);
    }
}

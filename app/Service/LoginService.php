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

use App\Schema\LoginSchema;
use App\Service\Dao\UserDao;
use App\Service\SubService\UserAuth;
use App\Service\SubService\WeChatService;
use GeminiD\PltCommon\RPC\User\UserInterface;
use Han\Utils\Service;
use Hyperf\Di\Annotation\Inject;

use function Hyperf\Support\env;

class LoginService extends Service
{
    #[Inject]
    protected WeChatService $chat;

    #[Inject]
    protected UserDao $dao;

    #[Inject]
    protected UserInterface $user;

    public function login(string $code): LoginSchema
    {
        $res = $this->user->firstByCode($code, env('MP_APP_ID'));

        $model = $this->dao->firstOrCreate($res['id']);

        $userAuth = UserAuth::instance()->init($model);

        return new LoginSchema($userAuth->getToken());
    }
}

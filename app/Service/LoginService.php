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
use App\Schema\LoginSchema;
use App\Service\Dao\UserDao;
use App\Service\SubService\UserAuth;
use App\Service\SubService\WeChatService;
use Han\Utils\Service;
use Hyperf\Di\Annotation\Inject;

class LoginService extends Service
{
    #[Inject]
    protected WeChatService $chat;

    #[Inject]
    protected UserDao $dao;

    public function login(string $code): LoginSchema
    {
        $result = $this->chat->login($code);
        if (! $result['openid']) {
            throw new BusinessException(ErrorCode::OAUTH_FAILED);
        }

        $model = $this->dao->firstOrCreate($result['openid']);

        $userAuth = UserAuth::instance()->init($model);

        return new LoginSchema($userAuth->getToken());
    }
}

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

namespace App\Controller;

use App\Service\SubService\UserAuth;
use App\Service\UserService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Swagger\Annotation as SA;
use Hyperf\Swagger\Request\SwaggerRequest;

#[SA\HyperfServer('http')]
class UserController extends Controller
{
    #[Inject]
    protected UserService $service;

    #[SA\Get('/user/info', summary: '用户信息', tags: ['注册登录'])]
    #[SA\Response(response: '200', content: new SA\JsonContent(ref: '#/components/schemas/UserSchema'))]
    public function login(SwaggerRequest $request)
    {
        $userId = UserAuth::instance()->getUserId();

        $result = $this->service->info($userId);

        return $this->response->success($result);
    }
}

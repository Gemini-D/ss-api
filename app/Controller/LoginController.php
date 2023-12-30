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

use App\Service\LoginService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Swagger\Annotation as SA;

#[SA\HyperfServer('http')]
class LoginController extends Controller
{
    #[Inject]
    protected LoginService $service;

    #[SA\Post('/login', summary: '小程序登录', tags: ['注册登录'])]
    #[SA\RequestBody(content: new SA\JsonContent(properties: [
        new SA\Property(property: 'code', description: '微信授权码', type: 'string', rules: 'required|string'),
    ]))]
    #[SA\Response(response: '200', content: new SA\JsonContent(ref: '#/components/schemas/LoginSchema'))]
    public function login()
    {
        return $this->response->success();
    }
}

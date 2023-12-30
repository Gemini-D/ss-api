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

use Hyperf\Swagger\Annotation as HA;

#[HA\HyperfServer('http')]
class LoginController extends Controller
{
    #[HA\Post(path: '/login', description: '小程序登录接口')]
    public function login()
    {
    }
}

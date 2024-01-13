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

namespace App\Constants;

use Hyperf\Constants\Annotation\Constants;
use Hyperf\Constants\EnumConstantsTrait;

#[Constants]
enum ErrorCode: int implements ErrorCodeInterface
{
    use EnumConstantsTrait;

    /**
     * @Message("没有权限")
     */
    case PERMISSION_DENY = 403;

    /**
     * @Message("Server Error")
     */
    case SERVER_ERROR = 500;

    /**
     * @Message("Token 已失效")
     */
    case TOKEN_INVALID = 700;

    /**
     * @Message("授权失败")
     */
    case OAUTH_FAILED = 701;

    /**
     * @Message("密码已失效")
     */
    case SECRET_INVALID = 702;

    /**
     * @Message("访问米哈游服务器报错")
     */
    case REQUEST_MIHOYO_FAILED = 703;

    /**
     * @Message("米哈游验证码登录失败")
     */
    case REQUEST_MIHOYO_LOGIN_BY_MOBILE_CAPTCHA = 704;

    /**
     * @Message("参数错误")
     */
    case PARAMS_INVALID = 1000;

    /**
     * @Message("用户不存在")
     */
    case USER_NOT_EXIST = 1001;

    /**
     * @Message("密码不存在")
     */
    case SECRET_NOT_EXIST = 1100;

    /**
     * @Message("密码已经存在")
     */
    case SECRET_ALREADY_EXIST = 1101;

    /**
     * @Message("内容不存在")
     */
    case CONTENT_NOT_EXIST = 1200;

    /**
     * @Message("被分享的内容不允许修改")
     */
    case CONTENT_CANNOT_SAVE_CAUSED_BY_SHARE = 1201;

    public function getMessage(array $translate = null): string
    {
        $arguments = [];
        if ($translate) {
            $arguments = [$translate];
        }

        return $this->__call('getMessage', $arguments);
    }
}

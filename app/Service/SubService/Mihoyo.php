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

namespace App\Service\SubService;

use App\Constants\ErrorCode;
use App\Constants\GachaType;
use App\Exception\BusinessException;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Han\Utils\Service;
use Hyperf\Codec\Json;
use Hyperf\Stringable\Str;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @see https://github.com/UIGF-org/mihoyo-api-collect
 */
class Mihoyo extends Service
{
    public function client(): Client
    {
        return new Client([
            'base_uri' => 'https://webapi.account.mihoyo.com/Api/is_mobile_registrable',
            'timeout' => 5,
        ]);
    }

    /**
     * 当前手机号是否未注册.
     */
    #[ArrayShape([
        'is_registable' => 'int', // 0已注册 1未注册
        'msg' => 'string',
        'status' => 'int', // 1表示成功
    ])]
    public function isMobileRegistrable(string $mobile): array
    {
        $result = $this->client()->get('/Api/is_mobile_registrable', [
            'query' => [
                'mobile' => $mobile,
                't' => (int) (microtime(true) * 1000),
            ],
        ]);

        $result = Json::decode((string) $result->getBody());
        if ($result['code'] !== 200) {
            throw new BusinessException(ErrorCode::REQUEST_MIHOYO_FAILED);
        }

        return $result['data'];
    }

    /**
     * 创建mmt（mmt_key）.
     */
    #[ArrayShape([
        'mmt_data' => [
            'mmt_key' => 'string',
        ],
        'mmt_type' => 'int',
        'msg' => 'string',
        'scene_type' => 'int',
        'status' => 'int', // 1表示成功
    ])]
    public function createMmt(
        string $sceneType = '1',
        string $reason = 'user.mihoyo.com#/login/captcha"',
        string $actionType = 'login_by_mobile_captcha'
    ) {
        $result = $this->client()->get('/Api/create_mmt', [
            'query' => [
                'scene_type' => $sceneType,
                'now' => (int) (microtime(true) * 1000),
                'reason' => $reason,
                't' => (int) (microtime(true) * 1000),
                'action_type' => $actionType,
            ],
        ]);

        $result = Json::decode((string) $result->getBody());
        if ($result['code'] !== 200) {
            throw new BusinessException(ErrorCode::REQUEST_MIHOYO_FAILED);
        }

        return $result['data'];
    }

    /**
     * 发送登录验证码.
     */
    #[ArrayShape([
        'info' => 'string', // 非成功时候会返回这个字段
        'msg' => 'string',
        'status' => 'int', // 1表示成功 -1非成功
    ])]
    public function createMobileCaptcha(string $mmtKey, string $mobile, string $actionType = 'login')
    {
        $result = $this->client()->get('/Api/create_mobile_captcha', [
            'query' => [
                'action_type' => $actionType,
                'mmt_key' => $mmtKey,
                'mobile' => $mobile,
                't' => (int) (microtime(true) * 1000),
            ],
        ]);

        $result = Json::decode((string) $result->getBody());
        if ($result['code'] !== 200) {
            throw new BusinessException(ErrorCode::REQUEST_MIHOYO_FAILED);
        }

        return $result['data'];
    }

    /**
     * 验证码登录.
     */
    #[ArrayShape([
        'account_info' => [
            'account_id' => 'int',
            'area_code' => 'string',
            'create_time' => 'int',
            'identity_code' => 'string',
            'is_adult' => 'int',
            'mobile' => 'string',
            'real_name' => 'string',
            'safe_level' => 'int',
            'weblogin_token' => 'string', // RB4wuQESsjrNAjcVFpyBs0HB6XDoYzTX6d3ohjn9
        ],
        'msg' => 'string',
        'status' => 'int',
        'Headers' => [
            'Set-Cookie' => [
                // aliyungf_tc=77ac4b856ca5e7a9a2064deedb81e895e543383ae83217c70ea76495cc0fe0bb; Path=/; HttpOnly
                'aliyungf_tc' => 'string',
                // login_uid=357726953; Path=/; Domain=mihoyo.com
                'login_uid' => 'string',
                // login_ticket=RZw02pc91LiXXvbh24z6uf03kIs5vuDyoa0pE2k3; Path=/; Domain=mihoyo.com
                'login_ticket' => 'string',
            ],
        ],
    ])]
    public function loginByMobilecaptcha(string $mobile, string $mobileCaptcha, string $source = 'user.mihoyo.com')
    {
        $result = $this->client()->post('/Api/login_by_mobilecaptcha', [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF-8',
            ],
            'body' => 'mobile=' . $mobile . '&mobile_captcha=' . $mobileCaptcha . '&source=' . $source . '&t=' . (int) (microtime(true) * 1000),
        ]);

        $result = $result->getHeaders();
        if (count($result['Set-Cookie']) < 2) {
            throw new BusinessException(ErrorCode::REQUEST_MIHOYO_LOGIN_BY_MOBILE_CAPTCHA);
        }

        return $result;
    }

    /**
     * Cookie 登录.
     */
    #[ArrayShape([
        'account_info' => [
            'account_id' => 'int',
            'area_code' => 'string',
            'create_time' => 'int',
            'identity_code' => 'string',
            'is_adult' => 'int',
            'mobile' => 'string',
            'real_name' => 'string',
            'safe_level' => 'int',
            'weblogin_token' => 'string', // iHRQbNldPNJCsP3vHyEqIwJvllRtTW6JfOYhQbl5
        ],
        'game_ctrl_info' => null,
        'notice_info' => [],
        'msg' => 'string',
        'status' => 'int',
    ])]
    public function loginByCookie(string $aliyungfTc, string $loginUid, string $loginTicket)
    {
        $result = $this->client()->get('/Api/login_by_cookie', [
            'query' => [
                't' => (int) (microtime(true) * 1000),
            ],
            'headers' => [
                'Cookie' => 'DEVICEFP=38d7f4821c11f; _MHYUUID=92676215-e49b-494b-a1d7-d6c1f097cf6d; login_ticket=' . $loginTicket . '; login_uid=' . $loginUid . '; aliyungf_tc=' . $aliyungfTc . '; DEVICEFP_SEED_ID=d58edec044f86d54; DEVICEFP_SEED_TIME=' . (int) (microtime(true) * 1000),
            ],
        ]);

        $result = Json::decode((string) $result->getBody());
        if ($result['code'] !== 200) {
            throw new BusinessException(ErrorCode::REQUEST_MIHOYO_FAILED);
        }
        return $result['data'];
    }

    /**
     * 通过登录票获取 stoken 和 ltoken .
     */
    #[ArrayShape([
        'stoken' => 'string',
        'ltoken' => 'string',
    ])]
    public function getMuLtiTokenByLoginTicket(int|string $uid, string $loginTicket, string $tokenTypes = '3')
    {
        $client = new Client([
            'base_uri' => 'https://api-takumi.mihoyo.com',
            'timeout' => 5,
        ]);
        $result = $client->get('/auth/api/getMultiTokenByLoginTicket', [
            'query' => [
                'uid' => $uid,
                'login_ticket' => $loginTicket,
                'token_types' => $tokenTypes,
            ],
        ]);

        $result = Json::decode((string) $result->getBody());
        if ($result['retcode'] !== 0) {
            throw new BusinessException(ErrorCode::REQUEST_MIHOYO_FAILED);
        }

        $res = [];
        foreach ($result['data']['list'] as $datum) {
            $res[$datum['name']] = $datum['token'];
        }

        return $res;
    }

    #[ArrayShape(['ticket' => 'string', 'account_info' => ['account_id' => 'string']])]
    public function getActionTicketBySToken(string $stoken, int|string $stuid)
    {
        $client = new Client([
            'base_uri' => 'https://api-takumi.miyoushe.com',
            'timeout' => 5,
        ]);

        $response = $client->get('/auth/api/getActionTicketBySToken', [
            RequestOptions::HEADERS => [
                'Host' => 'api-takumi.mihoyo.com',
                'Accept' => 'application/json, text/plain, */*',
                'x-rpc-sys_version' => '12',
                'User-Agent' => 'Darwin/23.3.0',
                'X-Requested-With' => 'com.mihoyo.hyperion',
                'Origin' => 'user.mihoyo.com',
                'Referer' => 'user.mihoyo.com',
                'Cookie' => sprintf('stoken=%s;stuid=%s;', $stoken, $stuid),
            ],
            RequestOptions::QUERY => [
                'action_type' => 'game_role',
            ],
        ]);

        $result = Json::decode((string) $response->getBody());
        if ($result['retcode'] !== 0) {
            throw new BusinessException(ErrorCode::REQUEST_MIHOYO_FAILED, $result['message']);
        }
        return $result['data'];
    }

    #[ArrayShape(['game_uid' => 'string'])]
    public function getUserGameRoles(string $actionTicket)
    {
        $client = new Client([
            'base_uri' => 'https://api-takumi.miyoushe.com',
            'timeout' => 5,
        ]);

        $response = $client->get('/binding/api/getUserGameRoles', [
            RequestOptions::QUERY => [
                'action_ticket' => $actionTicket,
                'game_biz' => 'hk4e_cn',
            ],
        ]);

        $result = Json::decode((string) $response->getBody());
        if ($result['retcode'] !== 0) {
            throw new BusinessException(ErrorCode::REQUEST_MIHOYO_FAILED, $result['message']);
        }

        if (empty($result['data']['list'])) {
            throw new BusinessException(ErrorCode::REQUEST_MIHOYO_FAILED, '您没有绑定原神账号');
        }

        return $result['data']['list'][0];
    }

    #[ArrayShape(['authkey' => 'string'])]
    public function getAuthKey(int|string $gameUid, string $stoken, int|string $stuid): array
    {
        $client = new Client([
            'base_uri' => 'https://api-takumi.miyoushe.com',
            'timeout' => 5,
        ]);

        $response = $client->post('/binding/api/genAuthKey', [
            RequestOptions::HEADERS => [
                'Host' => 'api-takumi.mihoyo.com',
                'Accept' => 'application/json, text/plain, */*',
                'x-rpc-sys_version' => '12',
                'User-Agent' => 'Darwin/23.3.0',
                'X-Requested-With' => 'com.mihoyo.hyperion',
                'Origin' => 'user.mihoyo.com',
                'Referer' => 'user.mihoyo.com',
                'Cookie' => sprintf('stoken=%s;stuid=%s;', $stoken, $stuid),
                ...$this->ds1Headers(),
            ],
            RequestOptions::JSON => [
                'game_biz' => 'hk4e_cn',
                'region' => 'cn_gf01',
                'auth_appid' => 'webview_gacha',
                'game_uid' => (string) $gameUid,
            ],
        ]);

        $result = Json::decode((string) $response->getBody());
        if ($result['retcode'] !== 0) {
            throw new BusinessException(ErrorCode::REQUEST_MIHOYO_FAILED, $result['message']);
        }
        return $result['data'];
    }

    #[ArrayShape([
        'list' => [
            0 => [
                'uid' => 'string',
                'gacha_type' => 'string',
                'time' => 'string',
                'name' => 'string',
                'item_type' => 'string',
                'rank_type' => 'string', // 稀有度
                'id' => 'string',
            ],
        ],
    ])]
    public function getGachaLog(string $authkey, GachaType $gachaType, int|string $endId = 0)
    {
        // https://hk4e-api.mihoyo.com/event/gacha_info/api/getGachaLog

        $client = new Client([
            'base_uri' => 'https://hk4e-api.mihoyo.com',
            'timeout' => 5,
        ]);

        $response = $client->get('/event/gacha_info/api/getGachaLog', [
            RequestOptions::QUERY => [
                'authkey_ver' => '1',
                'authkey' => $authkey,
                'lang' => 'zh-cn',
                'size' => 20,
                'page' => 1,
                'end_id' => $endId,
                'gacha_type' => $gachaType->value,
            ],
        ]);

        $result = Json::decode((string) $response->getBody());
        if ($result['retcode'] !== 0) {
            throw new BusinessException(ErrorCode::REQUEST_MIHOYO_FAILED, $result['message']);
        }
        return $result['data'];
    }

    /**
     * @see https://github.com/UIGF-org/mihoyo-api-collect/issues/1
     */
    protected function ds1Headers(): array
    {
        $salt = 'N50pqm7FSy2AkFz2B3TqtuZMJ5TOl3Ep';
        $r = Str::random(6);
        $t = time();
        $sign = md5(sprintf('salt=%s&t=%s&r=%s', $salt, $t, $r));
        return [
            'DS' => sprintf('%s,%s,%s', $t, $r, $sign),
            'x-rpc-app_version' => '2.35.2',
            'x-rpc-client_type' => '5',
        ];
    }
}

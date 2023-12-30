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

namespace HyperfTest\Cases;

use App\Model\Secret;
use App\Service\SubService\UserAuth;
use HyperfTest\HttpTestCase;

/**
 * @internal
 * @coversNothing
 */
class SecretTest extends HttpTestCase
{
    public function testSecretCreate()
    {
        Secret::query()->where('user_id', 1)->where('secret', md5('1234'))->delete();

        $res = $this->json('/secret/create', [
            'secret' => '1234',
        ], [
            UserAuth::X_TOKEN => self::$token,
        ]);

        $this->assertSame(0, $res['code']);
    }

    public function testSecretCheck()
    {
        $res = $this->json('/secret/check', [
            'secret' => '6666',
        ], [
            UserAuth::X_TOKEN => self::$token,
        ]);

        $this->assertSame(0, $res['code']);
    }
}

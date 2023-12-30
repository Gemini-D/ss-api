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

namespace Cases;

use App\Service\SubService\UserAuth;
use HyperfTest\HttpTestCase;

/**
 * @internal
 * @coversNothing
 */
class ContentTest extends HttpTestCase
{
    public function testContentSave()
    {
        $res = $this->json('/content/save', [
            'id' => 0,
            'secret_id' => 1,
            'title' => 'Hello',
            'content' => 'World',
        ], [
            UserAuth::X_TOKEN => self::$token,
        ]);

        $this->assertSame(0, $res['code']);
    }
}

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

namespace HyperfTest;

use App\Service\SubService\WeChatService;
use Hyperf\Testing;
use Mockery;
use PHPUnit\Framework\TestCase;

use function Hyperf\Support\make;

/**
 * Class HttpTestCase.
 * @method get($uri, $data = [], $headers = [])
 * @method post($uri, $data = [], $headers = [])
 * @method json($uri, $data = [], $headers = [])
 * @method file($uri, $data = [], $headers = [])
 */
abstract class HttpTestCase extends TestCase
{
    public static bool $init = false;

    public static string $token = '';

    /**
     * @var Testing\Client
     */
    protected $client;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->client = make(Testing\Client::class);
        // $this->client = make(Testing\HttpClient::class, ['baseUri' => 'http://127.0.0.1:9501']);
    }

    public function __call($name, $arguments)
    {
        return $this->client->{$name}(...$arguments);
    }

    protected function setUp(): void
    {
        if (! self::$init) {
            self::$init = true;

            di()->set(WeChatService::class, $chat = Mockery::mock(WeChatService::class));
            $chat->shouldReceive('login')->with('1234567890')->andReturn(['openid' => 'ohjUY0TB_onjcaH2ia06HgGOC4CY']);

            $res = $this->json('/login', [
                'code' => '1234567890',
            ]);

            self::$token = $res['data']['token'];
        }
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }
}

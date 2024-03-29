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

use App\Service\SubService\UserAuth;
use GeminiD\PltCommon\RPC\User\UserInterface;
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

    public function __construct(string $name)
    {
        parent::__construct($name);
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

            di()->set(UserInterface::class, $user = Mockery::mock(UserInterface::class));
            $user->shouldReceive('firstByCode')->withAnyArgs()->andReturn(['id' => 186864216584192]);

            $res = $this->json('/login', [
                'code' => '1234567890',
            ]);

            self::$token = $res['data']['token'];

            $this->json('/secret/check', [
                'secret' => '6666',
            ], [
                UserAuth::X_TOKEN => self::$token,
            ]);
        }
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }
}

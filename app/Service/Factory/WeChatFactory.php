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

namespace App\Service\Factory;

use EasyWeChat\MiniApp\Application;
use Hyperf\Contract\ConfigInterface;
use Psr\Container\ContainerInterface;

class WeChatFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get(ConfigInterface::class)->get('wechat.default');

        return new Application($config);
    }
}

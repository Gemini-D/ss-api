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

namespace App\Command;

use App\Model\Secret;
use App\Service\Dao\ContentDao;
use App\Service\Dao\SecretDao;
use Hyperf\Command\Annotation\Command;
use Hyperf\Command\Command as HyperfCommand;
use Psr\Container\ContainerInterface;

#[Command]
class RunCommand extends HyperfCommand
{
    public function __construct(protected ContainerInterface $container)
    {
        parent::__construct('run:read_content');
    }

    public function configure()
    {
        parent::configure();
        $this->setDescription('读取用户内容');
    }

    public function handle()
    {
        $userId = (int) $this->ask('请输入用户ID', '497327017984');

        $secret = [];

        $models = di()->get(SecretDao::class)->findByUserId($userId);
        foreach ($models as $model) {
            $secret[$model->id] = $model->id;
        }

        $id = (int) $this->choice('请选择密钥ID', $secret);

        $contents = di()->get(ContentDao::class)->findBySecretId($id);

        /** @var Secret $secret */
        $secret = $models->get($id);

        foreach ($contents as $content) {
            $this->output->writeln($content->getContent($secret->secret));
        }
    }
}

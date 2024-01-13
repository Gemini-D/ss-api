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

namespace App\Service;

use App\Constants\GachaType;
use App\Model\YsGachaLog;
use App\Service\Dao\YsUserDao;
use App\Service\SubService\Mihoyo;
use Han\Utils\Service;
use Hyperf\AsyncQueue\Annotation\AsyncQueueMessage;
use Hyperf\Redis\Redis;
use Throwable;

class YsGachaLogService extends Service
{
    #[AsyncQueueMessage]
    public function load(int $userId): void
    {
        $key = 'load:gacha:' . $userId;
        if (di()->get(Redis::class)->set($key, '1', ['EX' => 3600, 'NX'])) {
            try {
                $user = di()->get(YsUserDao::class)->first($userId);
                if (! $user) {
                    return;
                }

                foreach (GachaType::enums() as $type) {
                    $id = 0;
                    while (true) {
                        $result = di()->get(Mihoyo::class)->getGachaLog($user->auth_key, $type, $id);
                        if (empty($result['list'])) {
                            break;
                        }
                        foreach ($result['list'] as $re) {
                            $id = $re['id'];

                            $model = new YsGachaLog();
                            $model->id = $re['id'];
                            $model->uid = $re['uid'];
                            $model->gacha_type = $re['gacha_type'];
                            $model->time = $re['time'];
                            $model->name = $re['name'];
                            $model->item_type = $re['item_type'];
                            $model->rank_type = $re['rank_type'];
                            try {
                                $model->save();
                            } catch (Throwable) {
                                break 2;
                            }
                        }
                    }
                }
            } finally {
                di()->get(Redis::class)->del($key);
            }
        }
    }
}

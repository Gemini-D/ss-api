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

namespace App\Model;

use App\Constants\ContentType;
use App\Service\SubService\Encrypter;
use App\Service\SubService\UserAuth;
use Carbon\Carbon;

/**
 * @property int $id
 * @property int $user_id 用户 ID
 * @property int $secret_id 密码 ID
 * @property string $title 标题
 * @property string $content 内容
 * @property ContentType $type 类型 0 文本 1 音频 2 视频
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Content extends Model
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'content';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = ['id', 'user_id', 'secret_id', 'title', 'content', 'type', 'created_at', 'updated_at'];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'integer', 'user_id' => 'integer', 'secret_id' => 'integer', 'type' => ContentType::class, 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    public function getContent()
    {
        $secret = UserAuth::instance()->build()->getSecret();
        return di()->get(Encrypter::class)->decrypt($this->content, $secret);
    }

    public function isYuanShen(): bool
    {
        return $this->type === ContentType::YUAN_SHEN;
    }
}

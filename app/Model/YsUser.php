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

use Carbon\Carbon;

/**
 * @property int $id
 * @property int $content_id 内容 ID
 * @property int $uid 原神 UID
 * @property int $mid 米游社 UID
 * @property string $stoken SToken
 * @property string $auth_key AuthKey
 * @property int $status SToken 是否失效
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class YsUser extends Model
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'ys_user';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = ['id', 'content_id', 'uid', 'mid', 'stoken', 'auth_key', 'status', 'created_at', 'updated_at'];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'int', 'content_id' => 'integer', 'uid' => 'integer', 'mid' => 'integer', 'status' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}

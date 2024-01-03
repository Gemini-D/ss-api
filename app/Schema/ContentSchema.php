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

namespace App\Schema;

use App\Constants\ContentType;
use App\Model\Content;
use Hyperf\Swagger\Annotation\Property;
use Hyperf\Swagger\Annotation\Schema;
use JsonSerializable;

#[Schema(title: '内容详情')]
class ContentSchema implements JsonSerializable
{
    #[Property(property: 'id', title: '', type: 'int')]
    public ?int $id;

    #[Property(property: 'user_id', title: '用户 ID', type: 'int')]
    public ?int $userId;

    #[Property(property: 'secret_id', title: '密码 ID', type: 'int')]
    public ?int $secretId;

    #[Property(property: 'title', title: '标题', type: 'string')]
    public ?string $title;

    #[Property(property: 'content', title: '内容', type: 'string')]
    public ?string $content;

    #[Property(property: 'type', title: '类型 0 文本 1 音频 2 视频', type: 'int')]
    public ?ContentType $type;

    #[Property(property: 'created_at', title: '', type: 'mixed')]
    public mixed $createdAt;

    #[Property(property: 'updated_at', title: '', type: 'mixed')]
    public mixed $updatedAt;

    public function __construct(Content $model, bool $withContent = false)
    {
        $this->id = $model->id;
        $this->userId = $model->user_id;
        $this->secretId = $model->secret_id;
        $this->title = $model->title;
        $this->content = null;
        if ($withContent) {
            $this->content = $model->getContent();
        }
        $this->type = $model->type;
        $this->createdAt = $model->created_at?->toDateTimeString();
        $this->updatedAt = $model->updated_at?->toDateTimeString();
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'user_id' => $this->userId,
            'secret_id' => $this->secretId,
            'title' => $this->title,
            'content' => $this->content,
            'type' => $this->type?->value,
            'type_str' => $this->type?->getName(),
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];
    }
}

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

namespace App\Controller;

use App\Constants\ContentType;
use App\Constants\GachaType;
use App\Schema\SavedSchema;
use App\Service\ContentService;
use App\Service\SubService\UserAuth;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Swagger\Annotation as SA;
use Hyperf\Swagger\Request\SwaggerRequest;

#[SA\HyperfServer('http')]
class ContentController extends Controller
{
    #[Inject]
    protected ContentService $service;

    #[SA\Post('/content/save', summary: '创建内容', tags: ['内容管理'])]
    #[SA\RequestBody(content: new SA\JsonContent(properties: [
        new SA\Property(property: 'id', description: '内容 ID', type: 'integer', rules: 'required|integer'),
        new SA\Property(property: 'secret_id', description: '密码 ID', type: 'integer', rules: 'required|integer'),
        new SA\Property(property: 'title', description: '标题', type: 'string', rules: 'required|string'),
        new SA\Property(property: 'content', description: '内容', type: 'string', rules: 'required|string'),
        new SA\Property(property: 'type', description: '类型 0 文本 1 音频 2 视频 3 图片', type: 'integer', rules: 'integer'),
    ]))]
    #[SA\Response(response: '200', content: new SA\JsonContent(ref: '#/components/schemas/SavedSchema'))]
    public function save(SwaggerRequest $request)
    {
        $id = (int) $request->input('id');
        $userAuth = UserAuth::instance();

        $result = $this->service->save($id, $request->all(), $userAuth);

        return $this->response->success(new SavedSchema($result));
    }

    #[SA\Get('/content/list', summary: '内容列表', tags: ['内容管理'])]
    #[SA\RequestBody(content: new SA\JsonContent(properties: [
        new SA\Property(property: 'secret_id', description: '密码 ID', type: 'integer', rules: 'required|integer'),
    ]))]
    #[SA\Response(response: '200', content: new SA\JsonContent(ref: '#/components/schemas/ContentListSchema'))]
    public function list(SwaggerRequest $request)
    {
        $secretId = (int) $request->input('secret_id');
        $userAuth = UserAuth::instance();

        $result = $this->service->list($secretId, $userAuth);

        return $this->response->success($result);
    }

    #[SA\Get('/content/info', summary: '内容详情', tags: ['内容管理'])]
    #[SA\RequestBody(content: new SA\JsonContent(properties: [
        new SA\Property(property: 'id', description: '内容 ID', type: 'integer', rules: 'required|integer'),
    ]))]
    #[SA\Response(response: '200', content: new SA\JsonContent(ref: '#/components/schemas/ContentSchema'))]
    public function info(SwaggerRequest $request)
    {
        $id = (int) $request->input('id');
        $userAuth = UserAuth::instance();

        $result = $this->service->info($id, $userAuth);

        return $this->response->success($result);
    }

    #[SA\Get('/content/type-list', summary: '内容类型列表', tags: ['内容管理'])]
    #[SA\Response(response: '200', content: new SA\JsonContent(type: 'array', items: new SA\Items(ref: '#/components/schemas/ContentTypeSchema')))]
    public function contentType()
    {
        return $this->response->success(
            ContentType::all()
        );
    }

    #[SA\Get('/content/gacha', summary: '抽卡汇总', tags: ['内容管理'])]
    #[SA\RequestBody(content: new SA\JsonContent(properties: [
        new SA\Property(property: 'id', description: '内容 ID', type: 'integer', rules: 'required|integer'),
        new SA\Property(property: 'gacha_type', description: '祈愿类型', type: 'integer', rules: 'required|integer'),
    ]))]
    #[SA\Response(response: '200', content: new SA\JsonContent(type: 'array', items: new SA\Items(ref: '#/components/schemas/YsGachaSchema')))]
    public function gacha(SwaggerRequest $request)
    {
        $id = (int) $request->input('id');
        $gachaType = GachaType::from((int) $request->input('gacha_type'));
        $userAuth = UserAuth::instance();

        $result = $this->service->gacha($id, $gachaType, $userAuth);

        return $this->response->success($result);
    }

    #[SA\Post('/content/fresh-gacha', summary: '读取最新抽卡记录', tags: ['内容管理'])]
    #[SA\RequestBody(content: new SA\JsonContent(properties: [
        new SA\Property(property: 'id', description: '内容 ID', type: 'integer', rules: 'required|integer'),
    ]))]
    #[SA\Response(response: '200', content: new SA\JsonContent(ref: '#/components/schemas/SavedSchema'))]
    public function freshGacha(SwaggerRequest $request)
    {
        $id = (int) $request->input('id');
        $userAuth = UserAuth::instance();

        $result = $this->service->freshGacha($id, $userAuth);

        return $this->response->success(new SavedSchema($result));
    }
}

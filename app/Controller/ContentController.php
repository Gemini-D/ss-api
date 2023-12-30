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
    ]))]
    #[SA\Response(response: '200', content: new SA\JsonContent(ref: '#/components/schemas/SavedSchema'))]
    public function save(SwaggerRequest $request)
    {
        $id = (int) $request->input('id');
        $userAuth = UserAuth::instance();

        $result = $this->service->save($id, $request->all(), $userAuth);

        return $this->response->success(new SavedSchema($result));
    }
}

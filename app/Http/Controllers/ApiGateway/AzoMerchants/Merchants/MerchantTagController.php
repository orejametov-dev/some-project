<?php

declare(strict_types=1);

namespace App\Http\Controllers\ApiGateway\AzoMerchants\Merchants;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApiGateway\Tags\ShowTagResource;
use App\Http\Resources\ApiGateway\Tags\TagResource;
use App\Models\Tag;
use App\UseCases\MerchantTags\FindMerchantTagByIdUseCase;
use App\UseCases\MerchantTags\RemoveMerchantTagUseCase;
use App\UseCases\MerchantTags\StoreMerchantTagUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MerchantTagController extends Controller
{
    public function index(Request $request): JsonResource
    {
        $merchant_tag_query = Tag::query();
        if ($request->query('object') == 'true') {
            return new TagResource($merchant_tag_query->first());
        }

        return TagResource::collection($merchant_tag_query->paginate($request->query('per_page') ?? 15));
    }

    public function store(Request $request, StoreMerchantTagUseCase $storeMerchantTagUseCase): TagResource
    {
        $this->validate($request, [
            'title' => 'required|unique:merchant_tags,title|min:5|max:255',
        ]);

        $merchant_tags = $storeMerchantTagUseCase->execute($request->input('title'));

        return new TagResource($merchant_tags);
    }

    public function show(int $id, FindMerchantTagByIdUseCase $findMerchantTagByIdUseCase): ShowTagResource
    {
        $tag = $findMerchantTagByIdUseCase->execute($id);

        return new ShowTagResource($tag->load('merchants'));
    }

    public function removeTag($id, RemoveMerchantTagUseCase $removeMerchantTagUseCase): JsonResponse
    {
        $removeMerchantTagUseCase->execute((int) $id);

        return new JsonResponse(['message' => 'Тэг успешно удалён.']);
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Controllers\ApiGateway\AzoMerchants\Merchants;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use App\UseCases\MerchantTags\FindMerchantTagByIdUseCase;
use App\UseCases\MerchantTags\RemoveMerchantTagUseCase;
use App\UseCases\MerchantTags\StoreMerchantTagUseCase;
use Illuminate\Http\Request;

class MerchantTagController extends Controller
{
    public function index(Request $request)
    {
        $merchant_tag_query = Tag::query();
        if ($request->query('object') == 'true') {
            return $merchant_tag_query->first();
        }

        return $merchant_tag_query->paginate($request->query('per_page') ?? 15);
    }

    public function store(Request $request, StoreMerchantTagUseCase $storeMerchantTagUseCase)
    {
        $this->validate($request, [
            'title' => 'required|unique:merchant_tags,title|min:5|max:255',
        ]);

        return $storeMerchantTagUseCase->execute($request->input('title'));
    }

    public function show($id, FindMerchantTagByIdUseCase $findMerchantTagByIdUseCase)
    {
        $tag = $findMerchantTagByIdUseCase->execute((int) $id);

        return $tag->merchants;
    }

    public function removeTag($id, RemoveMerchantTagUseCase $removeMerchantTagUseCase)
    {
        $removeMerchantTagUseCase->execute((int) $id);

        return response()->json(['message' => 'Тэг успешно удалён.']);
    }
}

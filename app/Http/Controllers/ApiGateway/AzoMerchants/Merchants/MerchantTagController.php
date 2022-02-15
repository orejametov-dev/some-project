<?php

namespace App\Http\Controllers\ApiGateway\AzoMerchants\Merchants;

use App\Http\Controllers\Controller;
use App\Modules\Merchants\Models\Tag;
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

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|unique:merchant_tags,title|min:5|max:255',
        ]);

        $merchant_tag = new Tag();
        $merchant_tag->title = $request->input('title');
        $merchant_tag->save();

        return $merchant_tag;
    }

    public function show($tag_id)
    {
        $tag = Tag::query()->findOrFail($tag_id);

        return $tag->merchants;
    }

    public function removeTag($tag_id)
    {
        $tag = Tag::query()->findOrFail($tag_id);

        if ($tag->merchants()->count()) {
            return response()->json(['message' => 'Тег невозможно удалить.']);
        }

        $tag->delete();

        return response()->json(['message' => 'Тэг успешно удалён.']);
    }
}

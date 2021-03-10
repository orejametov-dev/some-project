<?php


namespace App\Http\Controllers\ApiGateway\Core;


use App\Http\Controllers\Controller;
use App\Modules\Core\Models\ModelHook;
use Illuminate\Http\Request;

class ModelHooksController extends Controller
{
    public function index(Request $request)
    {
        $this->validate($request, [
            'hookable_id' => 'integer',
            'hookable_type' => 'in:merchants',
        ]);
        $hooksQuery = ModelHook::query();
        if ($request->query('hookable_type')) {
            $hooksQuery->where('hookable_type', $request->query('hookable_type'));
        }
        if ($request->query('hookable_id')) {
            $hooksQuery->where('hookable_id', $request->query('hookable_id'));
        }
        $hooksQuery->with('created_by', 'created_from')->orderByDesc('id');
        if ($request->query('object') == 'true') {
            return $hooksQuery->first();
        }
        return $hooksQuery->paginate($request->query('per_page'));
    }
}

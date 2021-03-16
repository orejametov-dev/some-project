<?php


namespace App\Http\Controllers\ApiGateway\Core;


use App\Http\Controllers\Controller;
use App\Modules\Core\Models\ModelHook;
use App\Services\Core\ServiceCore;
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
        $hooksQuery->with('created_from')->orderByDesc('id');
        if ($request->query('object') == 'true') {
            return $hooksQuery->first();
        }

        $paginatedHooks = $hooksQuery->paginate($request->query('per_page'));

        $users = ServiceCore::request('GET', 'users', new Request([
            'user_ids' => implode(';', $paginatedHooks->pluck('created_by_id')->toArray()),
        ]));

        foreach ($paginatedHooks as $hook) {
            $hook->created_by = collect($users)->where('id', $hook->created_by_id)->first();
        }

        return $paginatedHooks;
    }
}

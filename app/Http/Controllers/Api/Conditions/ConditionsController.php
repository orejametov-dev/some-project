<?php


namespace App\Http\Controllers\Api\Conditions;


use App\Http\Controllers\Controller;
use App\Modules\Merchants\Models\Condition;
use Illuminate\Http\Request;

class ConditionsController extends Controller
{
    public function index(Request $request)
    {
        $this->validate($request, [
            'relations' => 'nullable|array'
        ]);

        $conditions = Condition::query()->with($request->query('relations') ?? [])
            ->active()
            ->filterRequest($request);

        if ($request->query('object') == 'true') {
            return $conditions->first();
        }

        if ($request->has('paginate') && $request->query('paginate') == false) {
            return $conditions->get();
        }

        return $conditions->paginate($request->query('per_page'));
    }

    public function show(Request $request, $id)
    {
        $this->validate($request, [
            'relations' => 'nullable|array'
        ]);

        $condition = Condition::with($request->query('relations') ?? [])
            ->filterRequest($request)
            ->findOrFail($id);

        return $condition;
    }
}
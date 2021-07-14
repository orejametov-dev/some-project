<?php


namespace App\Http\Controllers\ApiGate\Conditions;


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

        $conditions = Condition::query();

        if($request->input('relations')) {
            $conditions->with($request->input('relations'));
        }

        $conditions = $conditions->active()->filterRequest($request);

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

        $condition = Condition::query()->filterRequest($request);

        if($request->input('relations')) {
            $condition->with($request->input('relations'));
        }

        $condition = $condition->findOrFail($id);

        return $condition;
    }
}

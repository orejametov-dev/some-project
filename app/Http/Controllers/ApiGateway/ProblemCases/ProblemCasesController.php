<?php


namespace App\Http\Controllers\ApiGateway\ProblemCases;


use App\Http\Controllers\Controller;
use App\Modules\Merchants\Models\ProblemCase;
use App\Services\Core\ServiceCore;
use Illuminate\Http\Request;

class ProblemCasesController extends Controller
{
    public function index(Request $request)
    {
        $problemCases = ProblemCase::query()
            ->filterRequests($request);

        if ($request->has('object') and $request->query('object') == true) {
            return $problemCases->first();
        }

        if ($request->has('object') and $request->query('paginate') == false) {
            return $problemCases->get();
        }
        return $problemCases->paginate($request->query('per_page') ?? 15);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'created_from_name' => 'required|string',
            'credit_number' => 'required_without:application_id|string',
            'application_id' => 'required_without:credit_number|integer',
            'assigned_to_id' => 'required|integer',
            'assigned_to_name' => 'required|string',
            'search_index' => 'required|string'
        ]);
        $problemCase = new ProblemCase();

        if ($request->has('credit_number') and $request->input('credit_number')) {
            $data = ServiceCore::request('GET', 'info-applications/' . $request->input('credit_number'), null);
            $problemCase->credit_number = $request->input('credit_number');
        } elseif ($request->has('application_id') and $request->input('application_id')) {
            $data = ServiceCore::request('GET', 'applications/' . $request->input('application_id'), null);
            $problemCase->application_id = $request->input('application_id');
        }

        $problemCase->merchant_id = $data->merchant_id;
        $problemCase->store_id = $data->store_id;
        $problemCase->client_id = $data->client_id;
        $problemCase->application_items = $data->application_items;

        $problemCase->created_by_id = $data->merchant_engaged_by->id;
        $problemCase->created_by_name = $data->merchant_engaged_by->name;
        $problemCase->created_from_name = $request->input('created_from_name');

        $problemCase->search_index = $request->input('search_index');

        $problemCase->assigned_to_id = $request->input('assigned_to_id');
        $problemCase->assigned_to_name = $request->input('assigned_to_name');
        $problemCase->setStatusNew();
        $problemCase->save();


        return $problemCase;
    }

    public function show($id)
    {
        $problemCase = ProblemCase::findOrFail($id);

        return $problemCase;
    }

    public function update($id, Request $request)
    {

    }
}

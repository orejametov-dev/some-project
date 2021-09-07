<?php


namespace App\Http\Controllers\ApiGateway\ProblemCases;


use App\Http\Controllers\Controller;
use App\Modules\Merchants\Models\ProblemCase;
use App\Modules\Merchants\Models\ProblemCaseTag;
use App\Services\Core\ServiceCore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProblemCasesController extends Controller
{
    public function index(Request $request)
    {
        $problemCases = ProblemCase::with('tags')
            ->filterRequests($request)
            ->orderBy('created_at', 'DESC');

        if ($request->has('object') and $request->query('object') == true) {
            return $problemCases->first();
        }

        if ($request->has('paginate') and $request->query('paginate') == false) {
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
            'search_index' => 'required|string',
        ]);
        $problemCase = new ProblemCase();

        if ($request->has('credit_number') and $request->input('credit_number')) {
            $data = ServiceCore::request('GET', 'applications/' . $request->input('credit_number'), null);
            $problemCase->credit_number = $request->input('credit_number');
        } elseif ($request->has('application_id') and $request->input('application_id')) {
            $data = ServiceCore::request('GET', 'applications/' . $request->input('application_id'), null);
            $problemCase->application_id = $request->input('application_id');
        }

        $problemCase->merchant_id = $data->merchant_id;
        $problemCase->store_id = $data->store_id;
        $problemCase->client_id = $data->client->id;

        $problemCase->search_index = $data->client->name
            . ' ' . $data->client->surname
            . ' ' . $data->client->patronymic
            . ' ' . $data->client->phone;

        $problemCase->application_items = $data->application_items;

        $problemCase->created_by_id = $data->merchant_engaged_by->id;
        $problemCase->created_by_name = $data->merchant_engaged_by->name;
        $problemCase->created_from_name = $request->input('created_from_name');

        $problemCase->assigned_to_id = $request->input('assigned_to_id');
        $problemCase->assigned_to_name = $request->input('assigned_to_name');
        $problemCase->description = $request->input('description');

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
        $this->validate($request, [
            'manager_comment' => 'nullable|string',
            'merchant_comment' => 'nullable|string',
            'deadline' => 'nullable|date_format:Y-m-d',
        ]);

        $problemCase = ProblemCase::findOrFail($id);
        $problemCase->manager_comment = $request->input('manager_comment');
        $problemCase->merchant_comment = $request->input('merchant_comment');
        $problemCase->deadline = $request->input('deadline');

        $problemCase->save();

        return $problemCase;
    }

    public function attachTags(Request $request, $id)
    {
        $request->validate([
            'tags' => 'required|array',
            'tags.*.name' => 'required|string',
            'tags.*.type_id' => 'required|integer|in:' . ProblemCaseTag::BEFORE_TYPE .', '. ProblemCaseTag::AFTER_TYPE
        ]);

        $problemCase = ProblemCase::findOrFail($id);
        $problemCase->tags()->detach();
        $tags = [];
        foreach ($request->input('tags') as $item) {
            $tag = ProblemCaseTag::query()->firstOrCreate(['body' => $item['name'], 'type_id' => $item['type_id']]);
            $tags[] = $tag->id;
        }
        $problemCase->tags()->attach($tags);


        return response()->json($problemCase->load('tags'));
    }

    public function setStatus(Request $request, $id)
    {
        $this->validate($request, [
            'status_id' => 'required|integer|in:'
                . ProblemCase::NEW . ','
                . ProblemCase::IN_PROCESS . ','
                . ProblemCase::DONE . ','
                . ProblemCase::FINISHED
        ]);
        $problemCase = ProblemCase::findOrFail($id);
        $problemCase->setStatus($request->input('status_id'));
        $problemCase->save();

        return $problemCase;
    }
}

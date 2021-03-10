<?php


namespace App\Http\Controllers\ApiGateway\Dashboard;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CreditsController extends Controller
{
    public function index(Request $request)
    {
        $this->validate($request, [
            'interval' => 'required|integer'
        ]);

        $core_db_name = app('db.connection')->getDatabaseName();
        $merchant_trends = DB::connection('service_credit')->table('credits')
            ->select(
                DB::raw('merchants.id'),
                DB::raw('merchants.name AS merchant_name'),
                DB::raw('IFNULL((select SUM(total_amount) as amount from credits where credits.merchant_id = merchants.id and contract_date BETWEEN DATE_SUB(NOW(), INTERVAL ' . $request->interval . ' DAY) AND NOW()), 0) as first_interval'),
                DB::raw('IFNULL((select SUM(total_amount) as amount from credits where credits.merchant_id = merchants.id and contract_date BETWEEN DATE_SUB(NOW(), INTERVAL ' . 2 * $request->interval . ' DAY) AND DATE_SUB(NOW(), INTERVAL ' . $request->interval . ' DAY)), 0) as second_interval'),
                DB::raw('IFNULL((select SUM(total_amount) as amount from credits where credits.merchant_id = merchants.id and contract_date BETWEEN DATE_SUB(NOW(), INTERVAL ' . $request->interval . ' DAY) AND NOW()), 0) as total_amount'),
                DB::raw('IFNULL(ROUND((select first_interval) / (select SUM(total_amount) from credits where contract_date BETWEEN DATE_SUB(NOW(), INTERVAL ' . $request->interval . ' DAY) AND NOW()) * 100, 2), 0) as total_percentage'),
                DB::raw('(select first_interval - second_interval) AS trade'),
                DB::raw('IFNULL(ROUND((select ((first_interval/second_interval) * 100) - 100), 2), 0) as diff_trade_on_percentage')
            )
            ->rightJoin("{$core_db_name}.merchants as " . 'merchants', 'credits.merchant_id', '=', 'merchants.id');

        if($request->query('maintainer_id')) {
            $merchant_trends->where('merchants.maintainer_id', $request->query('maintainer_id'));
        }

        if ($q = $request->query('q')) {
            $merchant_trends->where('name', 'like', '%' . $q . '%');
        }

        $merchant_trends = $merchant_trends->groupBy('merchants.id')
            ->paginate($request->query('per_page'));

        return $merchant_trends;
    }

    public function show(Request $request, $id)
    {
        $this->validate($request, [
            'interval' => 'required|integer'
        ]);

        $core_db_name = app('db.connection')->getDatabaseName();
        $merchant_trend = DB::connection('service_credit')->table('credits')
            ->select(
                DB::raw('merchants.id'),
                DB::raw('merchants.name AS merchant_name'),
                DB::raw('IFNULL((select SUM(total_amount) as amount from credits where credits.merchant_id = merchants.id and contract_date BETWEEN DATE_SUB(NOW(), INTERVAL ' . $request->interval . ' DAY) AND NOW()), 0) as first_interval'),
                DB::raw('IFNULL((select SUM(total_amount) as amount from credits where credits.merchant_id = merchants.id and contract_date BETWEEN DATE_SUB(NOW(), INTERVAL ' . 2 * $request->interval . ' DAY) AND DATE_SUB(NOW(), INTERVAL ' . $request->interval . ' DAY)), 0) as second_interval'),
                DB::raw('IFNULL((select SUM(total_amount) as amount from credits where credits.merchant_id = merchants.id and contract_date BETWEEN DATE_SUB(NOW(), INTERVAL ' . $request->interval . ' DAY) AND NOW()), 0) as total_amount'),
                DB::raw('IFNULL(ROUND((select first_interval) / (select SUM(total_amount) from credits where contract_date BETWEEN DATE_SUB(NOW(), INTERVAL ' . $request->interval . ' DAY) AND NOW()) * 100, 2), 0) as total_percentage'),
                DB::raw('(select first_interval - second_interval) AS trade'),
                DB::raw('IFNULL(ROUND((select ((first_interval/second_interval) * 100) - 100), 2), 0) as diff_trade_on_percentage')
            )
            ->rightJoin("{$core_db_name}.merchants as " . 'merchants', 'credits.merchant_id', '=', 'merchants.id')
            ->where('merchant_id', $id)
            ->groupBy('merchants.id')
            ->first();

        return collect($merchant_trend);
    }
}

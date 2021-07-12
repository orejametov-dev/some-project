<?php


namespace App\Services;


use Carbon\Carbon;
use DB;

class TimeLogger
{
    public $name;
    public $started_at;
    public $finished_at;
    public $diff;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function start()
    {
        $this->started_at = microtime(true) * 1000;
    }

    public function end()
    {
        $this->finished_at = microtime(true) * 1000;
        $this->diff = round($this->finished_at - $this->started_at, 3);
        $this->save();
    }

    private function save()
    {
        if(config('local_services.time_logger')){
            DB::connection('logs')->table('logs')->insert([
                'name' => $this->name,
                'started_at' => $this->started_at,
                'finished_at' => $this->finished_at,
                'diff' => $this->diff,
            ]);
        }
    }
}

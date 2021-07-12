<?php


namespace App\Services;


use Carbon\Carbon;
use DB;

class TimeLoger
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
        DB::connection('logs')->table('logs')->insert([
            'name' => $this->name,
            'started_at' => $this->started_at,
            'finished_at' => $this->finished_at,
            'diff' => $this->diff,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}

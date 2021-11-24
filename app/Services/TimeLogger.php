<?php


namespace App\Services;


use Illuminate\Support\Facades\Cache;

class TimeLogger
{
    private $key;
    private $cached_info;
    public $name;
    public $started_at;
    public $finished_at;
    public $diff;

    public function __construct($key, $name)
    {
        $this->key = $key;
        $this->name = $name;
        $this->cached_info = Cache::tags(CacheService::LOGS)->get($this->key);
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

        if (empty($this->cached_info)) {
            Cache::tags(CacheService::LOGS)->put($this->key, [
                [
                    'name' => $this->name,
                    'started_at' => $this->started_at,
                    'finished_at' => $this->finished_at,
                    'diff' => $this->diff,
                ]
            ]);
        } else {
            Cache::tags(CacheService::LOGS)->put($this->key, array_merge($this->cached_info,[
                [
                    'name' => $this->name,
                    'started_at' => $this->started_at,
                    'finished_at' => $this->finished_at,
                    'diff' => $this->diff,
                ]
            ]));
        }
        dd(Cache::tags(CacheService::LOGS)->get($this->key));
    }
}

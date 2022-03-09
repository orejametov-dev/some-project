<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class TimeLogger
{
    public const CACHE_KEY = 'logs_';
    public const CACHE_TTL = 24 * 60;
    public string $name;
    public float $started_at;
    public float $finished_at;
    public float $diff;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function start(): void
    {
        $this->started_at = microtime(true) * 1000;
    }

    public function end(): void
    {
        $this->finished_at = microtime(true) * 1000;
        $this->diff = round($this->finished_at - $this->started_at, 3);
        $this->save();
    }

    private function save(): void
    {
        if (config('local_services.time_logger')) {
            $cached_info = Cache::get(self::CACHE_KEY);

            if (empty($cached_info)) {
                Cache::put(self::CACHE_KEY, [
                    [
                        'name' => $this->name,
                        'started_at' => $this->started_at,
                        'finished_at' => $this->finished_at,
                        'diff' => $this->diff,
                    ],
                ], self::CACHE_TTL);
            } else {
                Cache::put(self::CACHE_KEY, array_merge($cached_info, [
                    [
                        'name' => $this->name,
                        'started_at' => $this->started_at,
                        'finished_at' => $this->finished_at,
                        'diff' => $this->diff,
                    ],
                ]), self::CACHE_TTL);
            }
        }
    }
}

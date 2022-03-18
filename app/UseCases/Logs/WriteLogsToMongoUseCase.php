<?php

declare(strict_types=1);

namespace App\UseCases\Logs;

use App\Models\Log;
use App\Services\TimeLogger;
use Illuminate\Support\Facades\Cache;

class WriteLogsToMongoUseCase
{
    public function execute(): void
    {
        try {
            $logs = Cache::get(TimeLogger::CACHE_KEY);
            if (!empty($logs)) {
                foreach (array_chunk($logs, 10000) as $chunk_logs) {
                    Log::query()->insert($chunk_logs);
                }

                Cache::forget(TimeLogger::CACHE_KEY);
            }
        } catch (\Exception $e) {
            if (app()->bound('sentry')) {
                app('sentry')->captureException($e);
            }

            Cache::forget(TimeLogger::CACHE_KEY);
        }
    }
}

<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;

trait CacheModel
{
    public static function cachedKey()
    {
        return (new self)->getTable() . ':table';
    }

    public static function updateCache()
    {
        $allEntities = self::all();
        Cache::put(self::cachedKey(), $allEntities, 3600);
    }

    public static function allCached()
    {
        if (!Cache::has(self::cachedKey())) {
            self::updateCache();
        }

        return Cache::get(self::cachedKey());
    }

    public static function findCached($value, $key = 'id')
    {
        $allEntities = self::allCached();

        return $allEntities->where($key, $value)->first();
    }

    public static function firstCached()
    {
        return self::allCached()->first();
    }
}

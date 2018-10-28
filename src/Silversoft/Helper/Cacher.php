<?php

namespace Silversoft\Helper;

use Cache;
use Carbon\Carbon;

/**
 * Class Cacher
 *
 * @package Silversoft\Helper
 * @author Silversoft, contact@silversoft.nl
 *
 */
class Cacher
{

    /**
     * Get cache value
     *
     * @param array $tags
     * @param string $keys
     * @return mixed
     */
    public static function get($tags, $keys)
    {
        $cacheDriver = config('cache.default');
        if (!empty($tags)) {
            if ($cacheDriver == 'redis') {
                return Cache::tags($tags)->get($keys);
            } else {
                $keys = implode('_', $tags).'_'.$keys;
            }
        }
        return Cache::get($keys);
    }

    /**
     * Has cache value
     *
     * @param array $tags
     * @param string $keys
     * @return string|null
     */
    public static function has($tags, $keys)
    {
        $cacheDriver = config('cache.default');
        if (!empty($tags)) {
            if ($cacheDriver == 'redis') {
                return Cache::tags($tags)->has($keys);
            } else {
                $keys = implode('_', $tags).'_'.$keys;
            }
        }
        return Cache::has($keys);
    }

    /**
     * Put cache data
     *
     * @param array $tags
     * @param string $keys
     * @param mixed $value
     * @param int $minute
     */
    public static function put($tags, $keys, &$value, $minute)
    {
        $date        = Carbon::now()->addMinute($minute);
        $cacheDriver = config('cache.default');
        if (!empty($tags)) {
            if ($cacheDriver == 'redis') {
                Cache::tags($tags)->put($keys, $value, $date);
                return;
            } else {
                $keys = implode('_', $tags).'_'.$keys;
            }
        }
        Cache::put($keys, $value, $date);
    }

    /**
     * Forever cache data
     *
     * @param array $tags
     * @param string $keys
     * @param mixed $value
     */
    public static function forever($tags, $keys, &$value)
    {
        $cacheDriver = config('cache.default');
        if (!empty($tags)) {
            if ($cacheDriver == 'redis') {
                Cache::tags($tags)->forever($keys, $value);
                return;
            } else {
                $keys = implode('_', $tags).'_'.$keys;
            }
        }
        Cache::forever($keys, $value);
    }

    /**
     * Clear cache data
     * 
     * @param array $tags
     * @param string $keys
     */
    public static function clear($tags, $keys = null)
    {
        $cacheDriver = config('cache.default');
        if (empty($tags)) {
            return;
        }
        if ($cacheDriver == 'redis') {
            if (!empty($keys)) {
                Cache::tags($tags)->forget($keys);
            } else {
                Cache::tags($tags)->flush();
            }
        } else {
            $keys = implode('_', $tags).'_'.$keys;
            Cache::forget($keys);
        }
    }
}
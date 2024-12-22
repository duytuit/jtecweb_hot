<?php

namespace App\Helpers;


use Illuminate\Support\Facades\Redis;

class RedisHelper
{
    public static function queueSet($key, $data)
    {
        return Redis::command('rpush', [$key, [$data]]);
    }

    public static function queuePop($key = [])
    {
        return Redis::command('lpop', $key);
    }

    public static function setKey($key, $data)
    {
        return Redis::command('set', [$key, $data]);
    }

    public static function getKey($key)
    {
        return Redis::command('get', [$key]);
    }

    public static function delKey($key)
    {
        return Redis::command('del', [$key]);
    }

    public static function exitsKey($key)
    {
        return Redis::command('exists', $key);
    }

    public static function queueRange($key, $start, $end)
    {
        return Redis::command('lrange', [$key, $start, $end]);
    }
    public static function sAdd($key, $value)
    {
        return Redis::command('SADD', [$key, $value]);
    }

    public static function sRem($key, $value)
    {
        return Redis::command('SREM', [$key, $value]);
    }

    public static function sMembers($key)
    {
        return Redis::command('SMEMBERS', [$key]);
    }

    public static function sCard($key)
    {
        return Redis::command('SCARD', [$key]);
    }

    public static function sIsMember($key, $value)
    {
        return Redis::command('SISMEMBER', [$key, $value]);
    }

    public static function hSET($key, $field, $value)
    {
        return Redis::hset($key, $field, $value);
    }

    public static function hGelAll($key)
    {
        return Redis::hgetall($key);
    }

    public static function get($key)
    {
        $rs = Redis::command('GET', [$key]);
        return unserialize($rs);
    }

    public static function setAndExpire($key, $value, $expire)
    {
        return Redis::command('SETEX', [$key, $expire, serialize($value)]);
    }

    public static function set($key, $value)
    {
        return Redis::command('SET', [$key, serialize($value)]);
    }

    public static function del($key)
    {
        return Redis::command('DEL', [$key]);
    }

    public static function getLenList($key)
    {
        return Redis::command('LLEN', [$key]);
    }

    public static function getDataList($key, $start, $end)
    {
        return Redis::command('LRANGE', [$key, $start, $end]);
    }

    public static function zAdd($key, $score, $value)
    {
        return Redis::command('ZADD', [$key, $score, $value]);
    }

    public static function zRANGE($key, $min, $max)
    {
        return Redis::command('ZRANGE', [$key, $min, $max]);
    }

    public static function zRANGEBYSCORE($key, $min, $max)
    {
        return Redis::command('ZRANGEBYSCORE', [$key, $min, $max]);
    }

    public static function zRem($key, $value)
    {
        return Redis::command('ZREM', [$key, $value]);
    }

    public static function zCOUNT($key, $min, $max)
    {
        return Redis::command('ZCOUNT', [$key, $min, $max]);
    }
}

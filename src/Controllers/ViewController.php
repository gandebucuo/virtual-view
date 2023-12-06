<?php

namespace VirtualCloud\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redis;

class RedisController extends Controller
{
    protected $request;

    public function __construct()
    {
        $this->request = \request();
        $db = $this->request->db ?? 0;
        $db = str_replace('db', '', $db);
        Redis::select($db);
    }

    public function index()
    {
        return view('redis-view::index');
    }

    public function redis_dbs_index()
    {
        Redis::set("name2", '张三');
        Redis::hmset("room2", "class1", "班级1", "class2", "班级2");
        Redis::rpush("names2", "小明", "小红");
        Redis::sadd("set2", "1111", "2222", "3333");
        Redis::zadd("zset2", 1, "小刚", 2, "小花", 3, "小子");
        $dbs = [];
        for ($i = 0; $i < 16; $i++) {
            $dbs[] = ['label' => "db$i"];
        }
        return success('库列表', $dbs);
    }

    //keys列表
    public function redis_keys_index()
    {
        $searchKey = $this->request->searchKey;
        $types = [
            'string' => '字符串',
            'list' => '列表',
            'set' => '无序集合',
            'zset' => '有序集合',
            'hash' => '哈希',
        ];
        $keys = Redis::keys($searchKey ? "*$searchKey*" : "*");
        $data = [];
        foreach ($keys as $v) {
            $key = str_replace('laravel_database_', '', $v);
            $key_type = Redis::type($key)->getPayload();
            $size = 0;
            switch ($key_type) {
                case 'string':
                    $size = Redis::strlen($key);
                    break;
                case 'list':
                    $size = Redis::llen($key);
                    break;
                case 'hash':
                    $size = Redis::hlen($key);
                    break;
                case 'set':
                    $size = Redis::scard($key);
                    break;
                case 'zset':
                    $size = Redis::zcard($key);
                    break;
                default:
                    break;
            }
            $data[] = ['name' => $v, 'type' => $types[$key_type], 'size' => $size, 'key' => $key];
        }
        return success('keys列表', $data);
    }

    //展示key详情
    public function redis_key_show()
    {
        $key = $this->request->key;
        $key = str_replace('laravel_database_', '', $key);
        $key_type = Redis::type($key)->getPayload();
        $data = [];
        switch ($key_type) {
            case 'string':
                $data = Redis::get($key);
                break;
            case 'list':
                $data = Redis::lrange($key, 0, -1);
                break;
            case 'set':
                $data = Redis::smembers($key);
                break;
            case 'zset':
                $data = Redis::zrange($key, 0, -1, 'withscores');
                break;
            case 'hash':
                $data = Redis::hgetall($key);
                break;
            default:
                break;
        }
        $expire_time = Redis::ttl($key);
        return success('key详情', ['val' => $data, 'expire_time' => $expire_time > 0 ? $expire_time : '', 'key_type' => $key_type, 'key' => $key]);
    }

    //保存redis值
    public function redis_val_save()
    {
        $key = str_replace('laravel_database_', '', $this->request->key);
        $expire_time = 0;
        $data = [];
        switch ($this->request->key_type) {
            case '字符串':
                Redis::set($key, $this->request->val);
                break;
            case '哈希':
                $val = $this->request->newHashValue ?? $this->request->oldHashValue;
                if ($this->request->newHashKey) {
                    Redis::hdel($key, $this->request->oldHashKey);
                    Redis::hset($key, $this->request->newHashKey, $val);
                } else {
                    Redis::hset($key, $this->request->oldHashKey, $val);
                }
                $data = Redis::hgetall($key);
                break;
            case '列表':
                $val = $this->request->val;
                $index = $this->request->index;
                Redis::lset($key, $index, $val);
                $data = Redis::lrange($key, 0, -1);
                break;
            case '无序集合':
                $val = $this->request->val;
                $setValue = $this->request->setValue;
                Redis::srem($key, $val);
                Redis::sadd($key, $setValue);
                $data = Redis::smembers($key);
                break;
            case '有序集合':
                $value = $this->request->zsetValue ?? $this->request->val;
                $index = $this->request->zsetKey ?? $this->request->index;
                if ($this->request->zsetKey) {
                    Redis::zrem($key, $this->request->index);
                }
                Redis::zadd($key, $value, $index);
                $data = Redis::zrange($key, 0, -1, 'withscores');
                break;
            default:
                break;
        }
        return success('保存key', ['val' => $data, 'expire_time' => $expire_time > 0 ? $expire_time : '']);
    }

    //刷新redis值
    public function redis_val_refresh()
    {
        $key = str_replace('laravel_database_', '', $this->request->key);
        $data = [];
        switch ($this->request->key_type) {
            case '字符串':
                $data = Redis::get($key);
                break;
            case '列表':
                $data = Redis::lrange($key, 0, -1);
                break;
            case '无序集合':
                $data = Redis::smembers($key);
                break;
            case '哈希':
                $data = Redis::hgetall($key);
                break;
            case '有序集合':
                $data = Redis::zrange($key, 0, -1, 'withscores');
                break;
            default:
                break;
        }
        //返回秒级过期时间
        $expire_time = Redis::ttl($key);
        return success('刷新key详情', ['val' => $data, 'expire_time' => $expire_time > 0 ? $expire_time : '']);
    }

    public function redis_del()
    {
        $key = str_replace('laravel_database_', '', $this->request->key);
        $data = [];
        switch ($this->request->key_type) {
            case '列表':
                $index = $this->request->index;
                $val = Redis::lindex($key, $index);
                Redis::lrem($key, $index, $val);
                $data = Redis::lrange($key, 0, -1);
                break;
            case '无序集合':
                Redis::srem($key, $this->request->val);
                $data = Redis::smembers($key);
                break;
            case '哈希':
                Redis::hdel($key, $this->request->hash_key);
                $data = Redis::hgetall($key);
                break;
            case '有序集合':
                Redis::zrem($key, $this->request->index);
                $data = Redis::zrange($key, 0, -1, 'withscores');
                break;
            default:
                break;
        }
        //返回秒级过期时间
        $expire_time = Redis::ttl($key);
        return success('删除key', ['val' => $data, 'expire_time' => $expire_time > 0 ? $expire_time : '']);
    }

    public function redis_expire()
    {
        $key = str_replace('laravel_database_', '', $this->request->key);
        $data = [];
        switch ($this->request->key_type) {
            case '字符串':
                $data = Redis::get($key);
                break;
            case '列表':
                $data = Redis::lrange($key, 0, -1);
                break;
            case '无序集合':
                $data = Redis::smembers($key);
                break;
            case '哈希':
                $data = Redis::hgetall($key);
                break;
            default:
                break;
        }
        if ($this->request->expire_time > 0) {
            Redis::expire($key, $this->request->expire_time);
        } else {
            Redis::persist($key);
        }
        //返回秒级过期时间
        $expire_time = Redis::ttl($key);
        return success('删除key', ['val' => $data, 'expire_time' => $expire_time > 0 ? $expire_time : '']);
    }
}


<?php
/**
 * Created by: PhpStorm.
 * User: yj
 * Date: 2019/12/3
 * Time: 23:42
 */

namespace App\Common;


use Illuminate\Support\Facades\Redis;

//缓存操作工具类
class UtilRedis
{
    const redisTimes = 420;           //redis缓存有效期 秒

    //根据key返回value 没有返回null
    public static function get($key) : ?string
    {
        if (Redis::exists($key)) return Redis::get($key);
        return null;
    }

    //删除值
    public static function del($key) : bool
    {
        return Redis::del($key);
    }

    //key是否存在
    public static function exists($key) : bool
    {
        return Redis::exists($key);
    }

    //保存键值对，默认时间为7分钟
    public static function setEx($key, $val, $time = null)
    {
        $exTime = null == $time ? self::redisTimes : $time;
        //setex, 如果$key值已经存在，命令将会替换旧值
        Redis::setex($key, $exTime, $val);
    }

    //自动递增，并且返回递增后值,$key值不存在会默认初始化为0，并且进行递增 。$inc：递增多少
    public static function incrBy($key, $inc): int
    {
        return Redis::INCRBY($key, $inc);
    }

    //保存值，默认时间为7分钟。为null不保存
    public static function setExNotNull($key, $val, $time = null)
    {
        if (null == $val) return;
        self::setex($key, serialize($val), $time);
    }

    //组装key值
    public static function getRedisKey0($class, $func)
    {
        $class = Str::afterLast($class, "\\");
        return $class.":".$func;
    }

    //组装key值
    public static function getRedisKey1($class, $func, $key1)
    {
        $class = Str::afterLast($class, "\\");
        $key1 = Str::getStr($key1);
        return $class.":".$func.":".$key1;
    }

    //组装key值
    public static function getRedisKey2($class, $func, $key1, $key2)
    {
        $class = Str::afterLast($class, "\\");
        $key1 = Str::getStr($key1);
        $key2 = Str::getStr($key2);
        return $class.":".$func.":".$key1.":".$key2;
    }

    //组装key值
    public static function getRedisKey3($class, $func, $key1, $key2, $key3)
    {
        $class = Str::afterLast($class, "\\");
        $key1 = Str::getStr($key1);
        $key2 = Str::getStr($key2);
        $key3 = Str::getStr($key3);
        return $class.":".$func.":".$key1.":".$key2.":".$key3;
    }

    //组装key值
    public static function getRedisKey4($class, $func, $key1, $key2, $key3, $key4)
    {
        $class = Str::afterLast($class, "\\");
        $key1 = Str::getStr($key1);
        $key2 = Str::getStr($key2);
        $key3 = Str::getStr($key3);
        $key4 = Str::getStr($key4);
        return $class.":".$func.":".$key1.":".$key2.":".$key3.":".$key4;
    }

    /**
     * @param $key:要锁定的key
     * @param $time:锁定时间 秒
     * @return bool:  true:锁定成功  false:不能锁定
     */
    public static function tryKeyLock($key, $time) : bool
    {
        //nx:不能覆盖的锁，ex 超时时间为秒
        if (!Redis::set($key, "", "nx", "ex", $time)) return false;
        return true;
    }

    /** 把数据保存到hash类型数据集中,没有$key的情况下新增key val值，已有key情况下修改对应的val值
     * @param $hashKey:hash类型数据集key，所有数据会被放到这个名字的set数据集中
     * @param $key:保存的key值，是唯一的
     * @param $val:保存的值
     * @param $time:有效时间，秒
     */
    public static function hashAdd($hashKey, $key, $val, $time = null)
    {
        Redis::hset($hashKey, $key, $val);
        if ($time == null) $time = self::redisTimes;
        Redis::expire($hashKey, $time);//设置redis有效时间
    }

    /** 删除hash类型中数据集的数据
     * @param $hashKey:hash数据集的key值
     * @param $key:数据的key值
     */
    public static function hashDel($hashKey, $key)
    {
        Redis::hdel($hashKey, $key);
    }

    /** 返回hash表中所有数据
     * @param $hashKey:hash数据集的key值
     * @return array:返回此key值对应的所有键值对数据
     */
    public static function getHashAll($hashKey) : array
    {
        return Redis::hgetall($hashKey);
    }

    /**返回hash表中key值对应的数据
     * @param $hashKey:hash表的名称，key
     * @param $key:key值
     * @return mixed:返回key值对应的value
     */
    public static function getHash($hashKey, $key)
    {
        return Redis::hget($hashKey, $key);
    }

    /**返回哈希表中是否已经有了某个值
     * @param $hashKey:哈希表的名称，key
     * @param $key:需要查询的key值
     * @return bool:key是否已经存在  true:存在  false:不存在
     */
    public static function haveHashKey($hashKey, $key) : bool
    {
        return null != self::getHash($hashKey, $key);
    }

    /**查询出所有前缀包含  $keyPri 的key
     * @param $keyPri: 包含的前缀
     * @return mixed
     */
    public static function  getKeysByKeyPri($keyPri)
    {
        $likeKey = $keyPri . "*";
        return Redis::keys($likeKey);
    }

    /**删除所有前缀包含 $keyPri 的key
     * @param $keyPri: 包含的key
     * @return bool
     */
    public static function delKeysByKeyPri($keyPri): bool
    {
        $keys = self::getKeysByKeyPri($keyPri);
        foreach ($keys as $iKey)
        {
            if (!self::del($iKey)) return false;
        }
        return true;
    }

    /**把数据添加到队列的最后
     * @param string:$key:队列的key
     * @param string:$val:队列保存的值
     * @param $time:有效时间
     */
    public static function rPush(string $key, string $val, $time = null)
    {
        Redis::rPush($key, $val);
        if ($time == null) $time = self::redisTimes;
        Redis::expire($key, $time);//设置redis有效时间
    }

    /** 弹出队列最左边的值，每弹出一个，队列内个数减1。
     * @param string $key:要弹出的队列
     * @return mixed:队列弹出的值，当弹出的是 null 时表示队列已经空了
     */
    public static function lPop(string $key)
    {
        return Redis::lPop($key);
    }

    /**返回队列的长度
     * @param string $key
     * @return int
     */
    public static function lLen(string $key):int
    {
        return Redis::lLen($key);
    }

    /**返回队列的指定范围的数组
     * @param string $key
     * @param int $indexFrom 开始索引，基于0
     * @param int $indexTo 结束索引，基于0
     * @return Array
     */
    public static function lRange(string $key, $indexFrom, $indexTo):Array
    {
        return Redis::lrange ($key, $indexFrom, $indexTo);
    }

    /**返回队列的全部范围的数组
     * @param string $key
     * @return Array
     */
    public static function listAll(string $key):Array
    {
        return self::lrange ($key, 0, -1);
    }

    /**返回队列的指定索引的值
     * @param string $key
     * @param index $index
     * @return
     */
    public static function lIndex(string $key, $index)
    {
        return Redis::lindex ($key, $index);
    }

    // 队列指定位置设置值
    public static function lSet(string $key, $index, $val)
    {
        return Redis::lset ($key, $index, $val);
    }

    ///  集合操作

    // 集合增加
    public static function sAdd(string $key, $val, $time = null)
    {
        $ret=  Redis::sadd ($key, $val);
        if ($time != null)
        {
            $time = self::redisTimes;
            Redis::expire($key, $time);//设置redis有效时间
        }
        return $ret;
    }

    // 集合删除
    public static function sRem(string $key, $val)
    {
        return Redis::srem ($key, $val);
    }

    // 返回当前set表元素个数
    public static function sCount(string $key)
    {
        return Redis::scard ($key);
    }

    // 返判断元素是否属于当前set集合
    public static function sismember(string $key, $val) : bool
    {
        return Redis::sismember ($key, $val);
    }

    // 返回集合里面所有元素数组
    public static function smembers(string $key) : Array
    {
        return Redis::smembers ($key);
    }

    // 设置key超时时间
    public static function expire(string $key, $timeOutInSec)
    {
        return Redis::expire ($key, $timeOutInSec);
    }

    public static function expireat(string $key, $timeStamp)
    {
        return Redis::expireat ($key, $timeStamp);
    }

    public static function prolong_from_now(string $key, $timeInSecs)
    {
        return Redis::expireat ($key, time() + $timeInSecs);
    }
}

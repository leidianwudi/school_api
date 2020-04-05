<?php
/**
 * Created by PhpStorm.
 * User: yj
 * Date: 2019/7/13
 * Time: 11:12
 */

namespace App\Common;

//数组操作类
class Arr
{
    /**生成不可重复的数据
     * @param $min : 最小值
     * @param $max : 最大值
     * @param $num : 指定生成数量
     * @return array|null 生成指定数量的不重复数字
     */
    public static function unique_rand($min, $max, $num)
    {
        $count = 0;
        $return = array();
        while ($count < $num) {
            $return[] = mt_rand($min, $max);
            $return = array_flip(array_flip($return));
            $count = count($return);
        }
        shuffle($return);
        return $return;
    }

    /**生成可重复的数据
     * @param $min:最小值
     * @param $max:最大值
     * @param $num:指定生成数量
     * @return array:生成指定数量的可重复数字
     */
    public static function rand($min, $max, $num)
    {
        $count = 0;
        $return = array();
        while ($count < $num) {
            $return[] = mt_rand($min, $max);
            $count = count($return);
        }
        shuffle($return);
        return $return;
    }

    /**判断数组中是否存在某key
     * @param array $arr:要判断的数组
     * @param $key:要判断的key
     * @return bool:是否存在  false:不存在  true:存在
     */
    public static function isHaveKey(array $arr, $key): bool
    {
        return array_key_exists($key, $arr);
    }

    /** 判断数组中是否存在某元素
     * @param array $arr:要判断的数组
     * @param $val:要判断的元素
     * @return bool:是否存在  false:不存在  true:存在
     */
    public static function isHaveVal(array $arr, $val): bool
    {
        return in_array($val, $arr);
    }

    /**
     * @param $arr:要组合的数组
     * @param $m:组合元素个数
     * @return array:返回数组中所有元素的 m个数组合情况
     */
    static function getCombinationToArr($arr, $m) {
        if ($m == 1) {
            return $arr;
        }
        $result = array();

        for($i=0;$i<count($arr);$i++) {
            $s = $arr[$i];
            $tmpArr = array_slice($arr, $i + 1, count($arr) -1 - $i);//每次都取后面的数据
            $ret = self::getCombinationToArr(array_values($tmpArr), ($m-1));

            foreach($ret as $row) {
                $result[] = $s.",".$row;
            }
        }
        return $result;
    }

    /**
     * @param $arr:要排列的数组
     * @param $m:组合元素个数
     * @return array:返回数组中所有元素的 m个数 排列情况
     */
    static function getCombinationToArr2($arr, $m) {
        if ($m == 1) {
            return $arr;
        }
        $result = array();

        $tmpArr = $arr;
        unset($tmpArr[0]);//unset 删除后key保存不变  array_splice删除后key会发生变化
        for($i=0;$i<count($arr);$i++) {
            $s = $arr[$i];
            $ret = self::getCombinationToArr(array_values($tmpArr), ($m-1));

            foreach($ret as $row) {
                $result[] = $s .",". $row;
            }
        }

        return $result;
    }

    //返回数组第i个元素的key
    public static function getArrKey($arr, $index) : string
    {
        $arrIndex =  array_slice($arr,$index, 1);//取第几个数组
        $keyArr = array_keys($arrIndex);
        return $keyArr[0];
    }

    //返回数组第i个元素的value
    public static function getArrValue($arr, $index) : string
    {
        $arrIndex =  array_slice($arr,$index, 1);//取第几个数组
        $keyArr = array_values($arrIndex);
        return $keyArr[0];
    }

    //把数组反向排序
    public static function arrReverse(array $array): array
    {
        return array_reverse($array);
    }

    //以键从小到大排序
    public static function sortByKeyAsc(array &$array)
    {
        ksort($array);
        return $array;
    }

    //以键从大到小排序
    public static function sortByKeyDesc(array &$array)
    {
        krsort($array);
        return $array;
    }

    //以值从小到大排序  并保持索引关系
    public static function sortByValueAsc(array &$array)
    {
        asort($array);
        return $array;
    }

    //以值从小到大排序  不保持索引关系
    public static function sortByValueAsc2(array &$array)
    {
        sort($array);
        return $array;
    }

    //以值从大到小排序  并保持索引关系
    public static function sortByValueDesc(array &$array)
    {
        arsort($array);
        return $array;
    }

    //以值从大到小排序  不保持索引关系
    public static function sortByValueDesc2(array &$array)
    {
        rsort($array);
        return $array;
    }

    //计算数组里所有字符串的和
    public static function getArrSum(array $arr) : int
    {
        $sum = 0;
        foreach ($arr as $val)
        {
            $sum += $val;
        }
        return $sum;
    }

    //计算数组里所有字符串的积
    public static function getArrMul(array $arr) : int
    {
        $sum = 1;
        foreach ($arr as $val)
        {
            $sum *= $val;
        }
        return $sum;
    }

    //比较两数组中是否有相同元素
    public static function arrHaveSameVal(array $arr1, array $arr2) : bool
    {
        foreach ($arr1 as $v1)
        {
            foreach ($arr2 as $v2)
            {
                if ($v1 == $v2) return true;//有相同元素
            }
        }
        return false;//没有相同元素
    }

    //数组中是否包含字符串
    public static function arrHaveStr(array $arr, string $str): bool
    {
        foreach ($arr as $v)
        {
            if ($v == $str) return true;//包含
        }
        return false;//不包含
    }

    //返回新数组，新字符串不包含输入的字符串
    public static function getNewArrNotStr(array $arr, $notStr)
    {
        $new = [];
        foreach ($arr as $val)
        {
            if ($val != $notStr) $new[] = $val;
        }
        return $new;
    }

    //2个数组组合成新数组
    public static function arrayMerge(array $array1, array $array2)
    {
        return array_merge($array1, $array2);
    }

    /** 取数组内元素得最大长度
     * @param $arr: 数组
     * @return int: 数组元素最大长度
     */
    public static function getArrColumnMaxLength($arr): int
    {
        $len = 0;
        foreach ($arr as $col)
        {
            $i = strlen($col);
            $len = $i > $len ? $i : $len;
        }
        return $len;
    }

    /** 数组祛除重复项
     * @param $arr
     * @return array
     */
    public static function arrUnique($arr):array
    {
        return array_unique($arr);
    }
}

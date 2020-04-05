<?php
/**
 * Created by PhpStorm.
 * User: FBI
 * Date: 2018/5/7
 * Time: 13:24
 */

namespace App\Common;

use Symfony\Component\HttpFoundation\Request;
use App\InterfaceEntity\BaseEntity;

//数字类，可以金额float的金额计算
class Num
{
    const scale = 8;//操作精度

    //金额是否为0
    public static function isZero($val) : bool
    {
        $b1 = self::comp($val,  "0");
        return ($b1 == 0);
    }

    //金额是否为 [0-0.001)，不包括0.001
    public static function isZero_001($val) : bool
    {
        if (self::isLessZero($val))
            return false;//小于0，就不在区间

        //小于0.001就处于区间
        return (self::isLess($val, "0.001")) ? true : false;
    }

    //金额是否大于0
    public static function isMoreZero($val) : bool
    {
        $b1 = self::comp($val,  "0");
        return ($b1 == 1);
    }

    //金额是否小于0
    public static function isLessZero($val) : bool
    {
        $i = self::comp($val, "0");
        return ($i == -1);
    }

    //金额是否大于某值  $va1 > $va2 返回true
    public static function isGreat($va1, $val2) : bool
    {
        $res = self::comp($va1, $val2);
        return $res == 1;
    }

    //金额是否小于某值  $va1 < $va2 返回true
    public static function isLess($va1, $val2) : bool
    {
        $res = self::comp($va1, $val2);
        return $res == -1;
    }

    //金额是否大于某值  $va1 >= $va2 返回true
    public static function isGreatEqual($va1, $val2) : bool
    {
        $res = self::comp($va1, $val2);
        return $res >= 0;
    }

    //金额是否小于某值  $va1 <= $va2 返回true
    public static function isLessEqual($va1, $val2) : bool
    {
        $res = self::comp($va1, $val2);
        return $res <= 0;
    }

    //金额比较 a>b返回1   a=b返回0  a<b返回-1
    public static function comp($a, $b)
    {
        return bccomp($a, $b, self::scale);
    }

    //相加
    public static function add($a, $b)
    {
        return bcadd($a, $b, self::scale);
    }

    //减法
    public static function sub($a, $b)
    {
        return bcsub($a, $b, self::scale);
    }

    //乘法
    public static function nul($a, $b)
    {
        return bcmul($a, $b, self::scale);
    }

    //乘法 3个数相乘
    public static function nul3($a, $b, $c)
    {
        $t = bcmul($a, $b, self::scale);
        return bcmul($t, $c, self::scale);
    }

    //除法
    public static function div($a, $b)
    {
        return bcdiv($a, $b, self::scale);
    }

    //取模运算
    public static function mod($a, $b)
    {
        return bcmod($a, $b);
    }

    //取结对值
    public static function abs($a)
    {
        return abs($a);
    }

    //任意精度数字的乘方
    public static function pow($a, $b)
    {
        return bcpow($a, $b, self::scale);
    }

    //金额除以100后保留2位小数，分转为元使用
    public static function div100_2d($a)
    {
        $b = Num::div($a, 100);//除以100
        return self::format2Dec($b);//保留2位小数
    }

    //金额乘以100后转为int类型，元转为分使用
    public static function mul100_0d($a)
    {
        $b = Num::nul($a, 100);//乘以100
        return (int)($b);//不要小数
    }

    //两数相乘后，除以1000,保留3位小数
    public static function mul_div1000_3d($a, $b)
    {
        $c = self::nul($a, $b);     //乘
        $d = self::div($c, 1000);   //除1000
        return self::format3Dec($d);//保留2位小数
    }

    //两数差的绝对值
    public static function getSubJdz($a, $b)
    {
        $num = $a - $b;
        return abs($num);
    }

    //数字是否在某范围内
    public static function intIsIn($num, $min, $max) : bool
    {
        if ((int)$num >= (int)$min && (int)$num <= (int)$max)
            return true;
        else
            return false;
    }

    /**
     * @param $num :被格式化的数字
     * @param $length:需要保持的长度
     * @return string:返回字符串
     */
    public static function formatNum($num, $length)
    {
        $mat = Str::format("%0{0}d", $length);
        return sprintf($mat, $num);
    }

    //金额保留2位小数
    public static function format2Dec($num)
    {
        return sprintf("%.2f",$num);
    }

    //金额保留3位小数
    public static function format3Dec($num)
    {
        return sprintf("%.3f",$num);
    }
}

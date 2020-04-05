<?php


namespace App\Common;

//随机工具类
class Random
{
    //获取指定范围内的随机数
    public static function randomNum($min, $max)
    {
        return mt_rand($min, $max);
    }

    //生成随机用户昵称，前后2位，中间***    $num:生成的随机用户名个数
    public static function getRandomNiceArr(int  $num): array
    {
        $arr = [];
        for($i = 0; $i < $num; ++$i)
        {
            $v = "";
            do
            {
                $v = self::getRandomNice();
            }
            while(Arr::arrHaveStr($arr, $v));
            $arr[] = $v;
        }
        return $arr;
    }

    //生成随机用户昵称，前后2位，中间***
    public static function getRandomNice(): string
    {
        $key = "123456789abcdefghijklmnpqrstuvwxyz";
        $v1 = self::randomStr(2, $key);
        $v2 = self::randomStr(2, $key);
        return $v1."***".$v2;
    }

    //生成从大到小的数字随机数(数字可以重复)    $num:随机数个数   $min:最小值   $max:最大值
    public static function getRandomNumArrDesc(int $num, int $min, int $max): array
    {
        $arr = [];
        for($i = 0; $i < $num; ++$i)
        {
            $arr[] = self::randomNum($min, $max);
        }
        //从大到小排序，不保存索引关系
        return Arr::sortByValueDesc2($arr);
    }

    //生成从大到小的数字随机数(数字不能重复)    $num:随机数个数   $min:最小值   $max:最大值
    public static function getRandomNumArrDesc2(int $num, int $min, int $max): array
    {
        $arr = [];
        for($i = 0; $i < $num; ++$i)
        {
            $v = "";
            do
            {
                $v = self::randomNum($min, $max);
            }
            while(Arr::arrHaveStr($arr, $v));
            $arr[] = $v;
        }
        //从大到小排序，不保存索引关系
        return Arr::sortByValueDesc2($arr);
    }

    //获取随机字符串  $length:字符串个数
    public static function randomStr(int $length, string $chars = null)
    {
        if (null == $chars)
            $chars = "123456789abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ";

        $hash = '';
        $max = strlen($chars) - 1;
        for($i = 0; $i < $length; $i++) {
            $hash .= $chars[mt_rand(0, $max)];
        }
        return $hash;
    }

    //把名字进行格式化，中间加***
    public static function getRandName(string $account)
    {
        $max = strlen($account) - 1;
        $name1 = "";
        $name2 = "";
        for ($i = 0; $i < 2; ++$i) $name1 .= $account[$i];
        for ($i = $max - 2; $i < $max; ++$i) $name2 .= $account[$i];
        return $name1."***".$name2;
    }

    //生成随机红包金额，生成的数组 index从0开始，金额单位是分
    public static function getRedPackRandMoney($moneySum, $numberSum) : array
    {
        $moneySum = $moneySum * 100;//精确到分
        $ave = (int)$moneySum / (int)$numberSum;//平均金额
        $aveMin = $ave / 2; //最小值是平均值的一半
        $aveMax = $ave + ($ave - $aveMin);   //加上最小值被扣除的金额，是最大金额
        $arr = [];//随机数据
        $randSum = 0; //已经随机的总和
        for ($i = 0; $i < $numberSum; ++$i)
        {
            $arr[$i] = self::randomNum($aveMin, $aveMax);//随机值
            $randSum += $arr[$i];//总额累加
        }

        $sp = $moneySum - $randSum;//还差多少钱
        self::checkRedMoney($arr, $sp, $ave);//把差的钱调整到玩家身上
        return $arr;
    }

    //当随机总额不足，或太多时，随机把剩下的钱都加到某一个人身上，或者从N个人身上扣钱
    private static function checkRedMoney(&$arr, $changeMoney, $ave)
    {
        if ($changeMoney == 0) return;//不用调整
        $length = count($arr);  //总个数

        if ($changeMoney > 0)//需要加钱
        {
            $i = self::randomNum(0, $length - 1);//随机某个人
            $arr[$i] += $changeMoney;   //把钱加到这个人身上
        }
        else//需要扣钱
        {
            $changeMoney = -$changeMoney;//负数变正数
            for ($i = 0; $i < $length; ++$i)
            {
                if ($arr[$i] > $ave)//大于平均值才减
                {
                    $more = $arr[$i] - $ave;//大于平均值的金额
                    if ($more > $changeMoney)
                    {
                        $arr[$i] -= $changeMoney;//只要减去差额
                        break;//钱都减完，不用调整
                    }
                    else
                    {
                        $arr[$i] -= $more;//减去大于平均数的钱
                        $changeMoney -= $more;//需要调整的钱变少
                    }
                }
            }
        }
    }
}

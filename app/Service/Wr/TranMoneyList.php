<?php
/**
 * Created by: PhpStorm.
 * User: yj
 * Date: 2020/1/9
 * Time: 0:04
 */

namespace App\Service\Wr;


class TranMoneyList
{
    //数据转化，转为英文
    public static function tran($list)
    {
        $res = [];
        foreach ($list as $data)
        {
            $v = [];
            $v["COD"] = $data->COD;
            $v["PH"] = $data->PH;
            $v["PHMax"] = $data->PHMax;
            $v["PHMin"] = $data->PHMin;
            $v["SS"] = $data->SS;
            $v["SSMax"] = $data->SSMax;
            $v["SSMin"] = $data->SSMin;
            $v["day"] = $data->day;
            $v["yuLv"] = $data->余氯;
            $v["yuLvMax"] = $data->余氯Max;
            $v["yuLvMin"] = $data->余氯Min;
            $v["hourWater"] = $data->时流量;
            $v["anDan"] = $data->氨氮;
            $v["temperature"] = $data->温度;
            $v["temperatureMax"] = $data->温度Max;
            $v["temperatureMin"] = $data->温度Min;
            $v["eleLv"] = $data->电导率;
            $v["eleLvMax"] = $data->电导率Max;
            $v["eleLvMin"] = $data->电导率Min;
            $v["dleAddWater"] = $data->累计流量;
            $res[] = $v;
        }
        return $res;
    }
}

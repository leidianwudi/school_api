<?php
/**
 * Created by: PhpStorm.
 * User: yj
 * Date: 2020/1/8
 * Time: 23:06
 */

namespace App\Service\Wr;


class TranNowList
{
    //数据转化，转为英文
    public static function tran($list)
    {
        $res = [];
        foreach ($list as $data)
        {
            $v = [];
            $v["PH"] = $data->PH;
            $v["SS"] = $data->SS;
            $v["enterpriceId"] = $data->enterpriceId;
            $v["enterpriceName"] = $data->enterpriceName;
            $v["id"] = $data->id;
            $v["pId"] = $data->pId;
            $v["zhgyServerId"] = $data->zhgyServerId;
            $v["zhgyServerName"] = $data->zhgyServerName;
            $v["yuLv"] = $data->余氯;
            $v["closeFail"] = $data->关阀超时故障;
            $v["closeTimeSet"] = $data->关阀超时时间设置;
            $v["cityEle"] = $data->市电;
            $v["cityFail"] = $data->市电故障;
            $v["openFail"] = $data->开阀超时故障;
            $v["openTimeSet"] = $data->开阀超时时间设置;
            $v["allBalance"] = $data->总余额;
		    $v["failRst"] = $data->故障复位;
		    $v["dayAmount"] = $data->日配额;
		    $v["dayAmountRemain"] = $data->日配额余量;
		    $v["dayAmountMin"] = $data->日配额分;
		    $v["dayAmountHour"] = $data->日配额时;
		    $v["time"] = $data->时间;
		    $v["millisecond"] = $data->毫秒;
		    $v["anDan"] = $data->氨氮;
		    $v["waterMoney"] = $data->流量吨水收费;
		    $v["waterSp"] = $data->流量结算间隔;
		    $v["waterFail"] = $data->流量计通讯故障;
		    $v["temperature"] = $data->温度;
		    $v["eleLv"] = $data->电导率;
		    $v["nowWater"] = $data->瞬时流量;
		    $v["addWater"] = $data->累计流量;
		    $v["networkStatus"] = $data->网络状态;
		    $v["overLimit"] = $data->透支额度;
		    $v["doorOpen"] = $data->门禁开;
		    $v["doorCloseTime"] = $data->门禁自动关时间;
		    $v["gateClose"] = $data->阀门关到位;
		    $v["gateOpen"] = $data->阀门开到位;
		    $v["gateControl"] = $data->阀门控制模式;
		    $v["gateFail"] = $data->阀门故障;
		    $v["gateAuto"] = $data->阀门自动;
		    $v["gateFarClose"] = $data->阀门远控关;
		    $v["gateFarOpen"] = $data->阀门远控开;
            $res[] = $v;
        }
        return $res;
    }
}

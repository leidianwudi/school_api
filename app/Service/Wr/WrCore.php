<?php
/**
 * Created by: PhpStorm.
 * User: yj
 * Date: 2020/1/7
 * Time: 14:48
 */

namespace App\Service\Wr;


use App\Common\Http;
use App\Common\Tran;
use App\Common\Util;
use App\InterfaceEntity\InputEntity\User\InUserRegister;
use App\InterfaceEntity\InputEntity\Wr\InWr;
use App\InterfaceEntity\OutputEntity\ErrorMsg;
use App\InterfaceEntity\OutputEntity\ResultData;
use App\Model\User\User_modelSp;
use App\Service\External\StorageCore;
use App\Service\Log\User\LogUserLoginCore;
use App\Service\User\UserDelImport;
use phpDocumentor\Reflection\Type;

class WrCore
{
    //请求数据，只进行转发
    public static function request(InWr $in): ResultData
    {
        $res = new ResultData();                //创建返回类

        $data = http_build_query($in->data);    //发送数据
        $head = Tran::obj2Arr($in->head);
        $result = null;
        switch ($in->type)
        {
            case "get":
            {
                $result = Tran::json2Obj(Http::getHead($in->url, $data, $head));
                //$res->setData(Tran::json2Obj($result));
            }
            case "post":
            {
                $result = Tran::json2Obj(Http::postHead($in->url, $data, $head));
            }
        }

        //dd($result);
        if ($result->errCode == 0)
            $result->resp = self::tranRes($result->resp, $in->url);

        //var_dump($result);
        return  $res->setData($result);
    }

    //请求数据，只进行转发
    public static function request2(InWr $in): ResultData
    {
        $res = new ResultData();                //创建返回类

        $data = http_build_query($in->data);    //发送数据
        $head = Tran::obj2Arr($in->head);
        $result = null;
        switch ($in->type)
        {
            case "get":
            {
                $result = Tran::json2Obj(Http::getHead($in->url, $data, $head));
            }
            case "post":
            {
                $result = Tran::json2Obj(Http::postHead($in->url, $data, $head));
            }
        }
        //var_dump($result);
        return  $res->setData($result);
    }

    //数据格式转化。转为字母
    private static function tranRes($res, $url)
    {
        $resultV = null;
        switch ($url)
        {
            //瞬时数据
            case "http://124.72.169.195:8899/zhgyData/NowDataList":
                $resultV = TranNowList::tran($res);
                break;

             //企业日报表
            case "http://124.72.169.195:8899/zhgyData/EnterpriceDayDataList":
                $resultV = TranDayList::tran($res);
                break;

            //企业月报表
            case "http://124.72.169.195:8899/zhgyData/EnterpriceMonthDataList":
                $resultV = TranMoneyList::tran($res);
                break;

            default:
                return $res;
        }
        return $resultV;
    }
}

<?php
/**
 * Created by: PhpStorm.
 * User: yj
 * Date: 2019/12/21
 * Time: 13:56
 */

namespace App\Service\Gate;


use App\InterfaceEntity\InputEntity\Gate\InGroupIn;
use App\InterfaceEntity\InputEntity\Gate\InLoginGate;
use App\InterfaceEntity\OutputEntity\ResultData;

//网关
class GateCore
{
    //登录到网关
    public static function loginGate(InLoginGate $in): ResultData
    {
        $res = new ResultData();
        Gate::bindAccount($in->account, $in->clientId);  //注册
        return $res->success();
    }

    //进入某个房间
    public static function groupIn(InGroupIn $in): ResultData
    {
        $res = new ResultData();
        Gate::groupIn($in->account, $in->groupId);  //进入群
        return $res->success();
    }
}

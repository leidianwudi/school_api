<?php
/**
 * Created by: PhpStorm.
 * User: yj
 * Date: 2020/2/13
 * Time: 22:57
 */

namespace App\Service\User;


use App\InterfaceEntity\InputEntity\User\InUserSetting;
use App\InterfaceEntity\OutputEntity\ResultData;
use App\Model\User\User_setting_modelSp;

//用户设置
class UserSettingCore
{
    //根据用户名查询用户设置
    public static function getUserSetting(InUserSetting $in): ResultData
    {
        $res = new ResultData();                        //创建返回类
        $arr = User_setting_modelSp::getByAccountPage($in->account, $in->page, $in->count);
        return  $res->setData($arr);
    }

    //修改用户设置
    public static function setUserSetting(InUserSetting $in): ResultData
    {
        $res = new ResultData();                        //创建返回类
        $arr = User_setting_modelSp::setByAccount($in);
        return  $res->setData($arr);
    }
}

<?php
/**
 * Created by: PhpStorm.
 * User: yj
 * Date: 2019/12/2
 * Time: 22:13
 */

namespace App\Service\Log\User;


use App\Common\Time;
use App\Common\Util;
use App\Model\Log\User\Log_user_login_entity;
use App\Model\Log\User\Log_user_login_modelSp;
use App\Model\User\User_entity;

class LogUserLoginCore
{
    //添加登录日志
    public static function addLog(User_entity $user): bool
    {
        $en = new Log_user_login_entity();
        $en->account    = $user->account;           //用户名
        $en->ip         = Util::getClientIp();      //ip地址
        $en->city       = Util::getIpCity($en->ip); //登录所在地
        $en->time       = Time::getNowYMD_HIS();    //时间
        return Log_user_login_modelSp::insEntity($en);  //添加进数据库
    }
}

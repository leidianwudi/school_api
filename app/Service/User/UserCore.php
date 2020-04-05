<?php
/**
 * Created by: PhpStorm.
 * User: yj
 * Date: 2019/12/2
 * Time: 20:45
 */

namespace App\Service\User;


use App\Common\Str;
use App\Common\Util;
use App\InterfaceEntity\InputEntity\User\InUser;
use App\InterfaceEntity\InputEntity\User\InUserRegister;
use App\InterfaceEntity\OutputEntity\ErrorMsg;
use App\InterfaceEntity\OutputEntity\ResultData;
use App\Model\User\User_modelSp;
use App\Service\External\StorageCore;
use App\Service\Log\User\LogUserLoginCore;

//用户操作
class UserCore
{
    //用户登录
    public static function login(InUserRegister $in): ResultData
    {
        $res = new ResultData();                        //创建返回类

        $userEn = User_modelSp::getEntityByAccount($in->account);
        if (Util::isEmpty($userEn)) return $res->setError(ErrorMsg::accountNotFind);  //账户不存在
        if ($in->pwd != $userEn->pwd) return $res->setError(ErrorMsg::pwdError);      //密码错误

        LogUserLoginCore::addLog($userEn);                                            //写登录日志
        $userEn->token = Util::createToken();                                         //生成token
        if (!User_modelSp::setToken($userEn->id, $userEn->token)) return $res;        //修改数据库中token

        UserDelImport::delImportDataUser1($userEn);                                   //数据1次脱敏
        $userEn->head = StorageCore::getHeadUrl($userEn->head);                       //头像
        return  $res->setData($userEn);
    }

    //查询自己的信息
    public static function getMyInfo(InUserRegister $in): ResultData
    {
        $res = new ResultData();                        //创建返回类
        $userEn = User_modelSp::getEntityByAccount($in->account);
        UserDelImport::delImportDataUser1($userEn);                                   //数据1次脱敏
        $userEn->head = StorageCore::getHeadUrl($userEn->head);                       //头像
        return  $res->setData($userEn);
    }

    //根据用户名查询用户信息
    public static function getUserByAccount(InUserRegister $in): ResultData
    {
        $res = new ResultData();                        //创建返回类
        $userEn = User_modelSp::getEntityByAccount($in->account);
        //设置好友头像url
        if (!Util::isEmpty($userEn)) $userEn->head = StorageCore::getHeadUrl($userEn->head);
        UserDelImport::delImportDataUser2($userEn);     //数据2次脱敏
        return  $res->setData($userEn);
    }

    //用户修改自己信息
    public static function updMyInfo(InUser $in): ResultData
    {
        $res = new ResultData();                        //创建返回类

        $oldEn = User_modelSp::getEntityByAccount($in->account);
        if (Util::isEmpty($oldEn)) return $res->setError(ErrorMsg::accountNotFind);  //账户不存在

        $en = $in->getMyUpdateEntity();
        $en->id = $oldEn->id;                               //记录id
        if (User_modelSp::updEntity($en)) $res->success();  //修改数据
        return  $res;
    }
}

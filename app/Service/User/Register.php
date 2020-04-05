<?php
/**
 * Created by: PhpStorm.
 * User: yj
 * Date: 2019/12/2
 * Time: 20:45
 */

namespace App\Service\User;

use App\Common\Log;
use App\Common\Str;
use App\InterfaceEntity\InputEntity\User\InUserRegister;
use App\InterfaceEntity\OutputEntity\ErrorMsg;
use App\InterfaceEntity\OutputEntity\ResultData;
use App\Model\User\User_modelSp;
use Illuminate\Support\Facades\DB;

//用户注册
class Register
{
    //用户注册
    public static function userRegister(InUserRegister $in): ResultData
    {
        $res = new ResultData();               //创建返回类

        if (!Str::isCharNum($in->account))
            return $res->setError(ErrorMsg::accountMustCharNum);    //用户名只能包含字母或数字

        if (null != User_modelSp::getEntityByAccount($in->account))
            return $res->setError(ErrorMsg::accountHasExist);       //账户已经存在

        if (self::userRegisterDo($in)) return UserCore::login($in); //注册账户成功就进行登录

        return $res;
    }

    //进行注册操作
    private static function userRegisterDo(InUserRegister $in): bool
    {
        DB::beginTransaction();
        try
        {
            $userEn = $in->getAddEntity();              //转换为数据库表数据
            if (!User_modelSp::insEntity($userEn))      //添加失败回滚
            {
                DB::rollBack();
                return false;
            }

            DB::commit();
            return true;
        }
        catch (\Exception $e) {
            DB::rollBack();
            Log::errorTrace($e);
            return false;
        }
    }
}

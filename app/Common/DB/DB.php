<?php
/**
 * Created by: PhpStorm.
 * User: yj
 * Date: 2019/12/12
 * Time: 18:05
 */

namespace App\Common\DB;

//事务操作封装
class DB
{
    //开始事务
    public static function begTran()
    {
        \Illuminate\Support\Facades\DB::beginTransaction();
    }

    //提交事务，返回成功
    public static function commitTran(): bool
    {
        \Illuminate\Support\Facades\DB::commit();
        return true;
    }

    //回滚事务，返回失败
    public static function rollTran(): bool
    {
        \Illuminate\Support\Facades\DB::rollBack();
        return false;
    }
}

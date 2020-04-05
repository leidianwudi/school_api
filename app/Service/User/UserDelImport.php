<?php
/**
 * Created by: PhpStorm.
 * User: yj
 * Date: 2019/12/3
 * Time: 16:19
 */

namespace App\Service\User;


use App\Common\Util;
use App\Model\User\User_entity;

//用户工具类
class UserDelImport
{
    private const delStr = "******";    //替换字符串

    //把用户数据，进行1次脱敏
    public static function delImportDataUser1(?User_entity $en)
    {
        if (Util::isEmpty($en)) return;     //容错
        if (!Util::isEmpty($en->pwd)) $en->pwd   = self::delStr;
        if (!Util::isEmpty($en->pwd2)) $en->pwd2 = self::delStr;
    }

    //把用户数据，进行2次脱敏
    public static function delImportDataUser2(?User_entity $en)
    {
        if (Util::isEmpty($en)) return;     //容错
        self::delImportDataUser1($en);
        if (!Util::isEmpty($en->upAccount)) $en->upAccount  = self::delStr;
        if (!Util::isEmpty($en->tel))       $en->tel        = self::delStr;
        if (!Util::isEmpty($en->email))     $en->email      = self::delStr;
        if (!Util::isEmpty($en->weiXin))    $en->weiXin     = self::delStr;
        if (!Util::isEmpty($en->qq))        $en->qq         = self::delStr;
        if (!Util::isEmpty($en->address))   $en->address    = self::delStr;
        if (!Util::isEmpty($en->invCode))   $en->invCode    = self::delStr;
        if (!Util::isEmpty($en->token))     $en->token      = self::delStr;
        if (!Util::isEmpty($en->desc))      $en->desc       = self::delStr;
    }
}

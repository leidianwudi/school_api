<?php
/**
 * Created by: PhpStorm.
 * User: yj
 * Date: 2019/12/4
 * Time: 20:40
 */

namespace App\Service\External;

use App\Common\Str;
use App\Common\Util;
use App\Common\UtilStorage;
use App\InterfaceEntity\OutputEntity\External\OutUploadFile;
use App\InterfaceEntity\OutputEntity\ResultData;
use Symfony\Component\HttpFoundation\Request;

//文件保存操作
class StorageCore
{
    const defaultHead = "system/default_head.jpg";  //玩家默认头像

    const cache     = "cache/";     //临时文件存放的路径
    const head      = "head/";      //头像存放的路径

    const fileKey   = "file";       //上传时表单存放文件指定的key

    //上传文件到临时目录
    public static function uploadFileToCache(Request $rep): ResultData
    {
        $out = new OutUploadFile();
        $out->url = self::saveCache($rep);//上传文件，返回路径
        $res = new ResultData();
        return $res->setData($out);
    }

    //把文件转移到保存玩家头像目录，返回移到后的路径，$pullPath可以是包括http的完整路径
    public static function moveToHead($pullPath): ?string
    {
        $path = Str::after($pullPath, UtilStorage::extPath);
        return UtilStorage::moveTo($path, self::head);
    }

    //删除文件，不会删除系统文件
    public static function delFileNotSystem($path): bool
    {
        $path = UtilStorage::getLocalUrl($path);
        //是系统文件，不删除
        if (Str::contains(self::defaultHead, $path)) return true;
        return UtilStorage::delFile($path);
    }

    //返回头像的路径，若没有头像会返回默认头像
    public static function getHeadUrl($path): string
    {
        //没有头像返回默认头像
        if (Util::empty($path)) return UtilStorage::getNetUrl(self::defaultHead);
        return UtilStorage::getNetUrl($path);
    }

    //设置数组中的玩家头像字段，为全路径字段。$headKeyArr：需要从新设置头像的key
    public static function setHeadUrlForArr(&$arr, $headKeyArr = ["head"])
    {
        //设置头像
        foreach ($arr as &$val)
        {
            foreach ($headKeyArr as $headKey)
            {
                $val[$headKey] = self::getHeadUrl($val[$headKey]);
            }
        }
        return $arr;
    }

    //保存文件到临时目录，返回保存后的路径
    private static function saveCache(Request $rep): ?string
    {
        $path = self::saveToPath($rep, self::cache);
        return UtilStorage::getNetUrl($path);
    }

    //保存文件到指定路径，返回保存后的路径
    private static function saveToPath(Request $rep, $path): ?string
    {
        $file = $rep->file(self::fileKey);      //取文件
        return UtilStorage::save($file, $path);
    }


}

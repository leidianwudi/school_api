<?php
/**
 * Created by PhpStorm.
 * User: FBI
 * Date: 2018/11/20
 * Time: 15:25
 */

namespace App\Common;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Request;
use App\InterfaceEntity\BaseEntity;
use App\InterfaceEntity\InputEntity\User\InGetUserInfo;
use App\InterfaceEntity;
use App\InterfaceEntity\OutputEntity\ResultData;

//上传文件类
class Upload
{
    //上传文件到cache目录，返回图片连接
    public static function uploadCache(Request $rep) : string
    {
        //上传到cache目录。此配置在 filesystems.php 配置文件的 cache 配置项下指定的 app/cache 位置
        //store ("test1",'cache'); 会把文件传到  storage/app/cache/test1 目录下
        $imgPath = $rep->file("imgFile")->store("public/cache");
        $url = Util::getHttpIp().$imgPath;
        return Str::replaceFirst("public", "storage", $url);//把public字符用storage来替换
    }

    //文件是否在public文件夹已经存在
    public static function fileIsExistInPublic($path) : bool
    {
        $fileName = Str::afterLast($path, "/");  //文件名称
        $fileName = storage_path()."/app/public/".$fileName;
        //return Storage::exists($fileName);
        return file_exists($fileName);
    }

    //把文件从cache目录转移到public目录，并且返回新的图片连接
    public static function cacheMoveToPublic($path) : ?string
    {
        $fileName = Str::afterLast($path, "/");  //文件名称
        try
        {
            Storage::move('public/cache/'.$fileName, 'public/'.$fileName);//转移文件到public目录
        }
        catch(\Exception $e)
        {
            Log::errorTrace($e);
        }
        return $fileName;
        //return "storage/app/public/".$fileName; //返回新文件地址
    }

    //返回文件的全路径下载地址
    public static function getHttpPath($path) : ?string
    {
        if (Util::isEmpty($path)) return null;
        return Util::getHttpIp().$path;
    }

    //把文件从public目录删除，如果文件存在的话
    public static function fileDelFromPublic($path) : bool
    {
        $fileName = Str::afterLast($path, "/");  //文件名称
        $fileName = "public/".$fileName;         //文件路径
        return Storage::delete($fileName);
        //return Storage::($fileName);
    }
}

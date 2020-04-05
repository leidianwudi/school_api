<?php
/**
 * Created by: PhpStorm.
 * User: yj
 * Date: 2019/12/4
 * Time: 20:09
 */

namespace App\Common;


use Illuminate\Support\Facades\Storage;


//文件保存操作类
class UtilStorage
{
    //操作文件盘符。public磁盘在config/filesystems.php文件中指定 disks数组的public项目
    const disk      = "public";

    //完整路径中要添加的扩展子路径
    const extPath   = "storage/";

    /** 保存文件到公共目录
     * @param $file: 要保存的文件  Request->file("imgFile") 类型
     * @param string|null $path: 指定文件要保存的子目录 例如: head/
     * @return string|null: 文件保存后的新路径，返回null保存失败
     */
    public static function save($file, ?string $path = null): ?string
    {
        $ext            = self::getFileType($file);                 //扩展名
        $realPath       = $file->getRealPath();                     //临时绝对路径
        $filename       = Util::getOrderSn24() . '.' . $ext;        //新随机文件名
        if (!Util::isEmpty($path)) $filename = $path . $filename;   //加上子目录
        //public磁盘在config/filesystems.php文件中指定 disks数组的public项目
        $bool = Storage::disk(self::disk)->put($filename, file_get_contents($realPath));
        if ($bool) return $filename;
        return null;
    }

    //返回流文件的扩展名
    private static function getFileType($file): ?string
    {
        $ext = $file->getClientOriginalExtension(); //路径上的扩展名
        //路径没有扩展名再查找流文件类型
        if (Util::isEmpty($ext))
        {
            $mimeStr = $file->getMimeType();
            $mineArr = Str::split($mimeStr, "/");
            $ext = $mineArr[1];     //文件后缀
        }
        return $ext;
    }

    /** 文件转移
     * @param $fromPath:文件原始路径
     * @param $toPath:转到哪个路径
     * @return string|null:转移后的路径(不包括http的路径)，null:移动失败
     */
    public static function moveTo($fromPath, $toPath): ?string
    {
        $fileName = Str::afterLast($fromPath, "/");  //文件名称
        $toPath = $toPath . $fileName;               //全路径
        // 取到磁盘实例
        $disk = Storage::disk(self::disk);
        // 拷贝文件 第一个参数是要移动的文件，第二个参数是移动到哪里
        return $disk->move($fromPath, $toPath) ? $toPath : null;
    }

    //根据文件路径，返回外部网络访问rul
    public static function getNetUrl($path): string
    {
        if (Util::isEmpty($path)) return "";
        $local = self::getLocalUrl($path);  //本地路径
        return Util::getHttpIp() . $local;
    }

    //两个文件url是否一样
    public static function urlIsSame($url1, $url2): bool
    {
        $url1 = self::getLocalUrl($url1);  //文件路径和名称
        $url2 = self::getLocalUrl($url2);  //文件路径和名称
        return $url1 == $url2;
    }

    //根据路径，返回本地文件路径
    public static function getLocalUrl($path): string
    {
        $fileName = Str::afterLast($path, self::extPath);  //文件路径和名称
        return self::extPath . $fileName;
    }

    //删除文件
    public static function delFile($url): bool
    {
        $url = self::getLocalUrl($url);  //文件路径和名称
        $disk = Storage::disk(self::disk);
        return $disk->delete($url);
    }
}

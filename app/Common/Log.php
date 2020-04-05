<?php
/**
 * Created by PhpStorm.
 * User: FBI
 * Date: 2018/5/15
 * Time: 18:21
 */

namespace App\Common;

use Symfony\Component\HttpFoundation\Request;
use App\InterfaceEntity\BaseEntity;


class Log
{
    //写log
    public static function info($str)
    {
        //date_default_timezone_set('PRC');
        \Illuminate\Support\Facades\Log::info($str."   ".date('Y-m-d H:i:s'));
    }

    //写log 写数组
    public static function infoArr($array)
    {
        //date_default_timezone_set('PRC');
        $str = Tran::arr2Str($array, ",");
        \Illuminate\Support\Facades\Log::info($str."   ".date('Y-m-d H:i:s'));
    }

    //写log 写类
    public static function infoObj($obj)
    {
        //date_default_timezone_set('PRC');
        $str = json_encode($obj);
        \Illuminate\Support\Facades\Log::info($str."   ".date('Y-m-d H:i:s'));
    }

    //写错误
    public static function error($str)
    {
        \Illuminate\Support\Facades\Log::error($str."   ".date('Y-m-d H:i:s'));
    }

    //写错误
    public static function error2($class, $fun, $line, $str)
    {
        //date_default_timezone_set('PRC');
        \Illuminate\Support\Facades\Log::error($class." ".$fun." ".$line."行 ".$str."   ".date('Y-m-d H:i:s'));
    }

    //写崩溃详细日志
    public static function errorTrace(\Exception $e)
    {
        self::error($e->getMessage());
        self::error($e->getTraceAsString());
    }

}

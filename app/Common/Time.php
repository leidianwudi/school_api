<?php
/**
 * Created by PhpStorm.
 * User: FBI
 * Date: 2018/5/6
 * Time: 14:47
 */

namespace App\Common;

use Symfony\Component\HttpFoundation\Request;
use App\InterfaceEntity\BaseEntity;
use App\InterfaceEntity;

//时间类封装
class Time
{
    //昨天的时间范围
    public static function getYesStamp()
    {
        //date_default_timezone_set('PRC');
        $begin = strtotime(date('Y-m-d', strtotime('-1 day')));
        return [
            'begin' => $begin,
            'end' => $begin+24*60*60-1
        ];
    }

    //近30天的时间范围
    public static function getMonStamp()
    {
        //date_default_timezone_set('PRC');
        $end = time();
        $begin = strtotime(date('Y-m-d', strtotime('-30 days')));
        return [
            'begin' => $begin,
            'end' => $end
        ];
    }

    //一周的时间范围
    public static function getWeekStamp()
    {
        //date_default_timezone_set('PRC');
        $end = time();
        $begin = strtotime(date('Y-m-d', strtotime('-7 days')));
        return [
            'begin' => $begin,
            'end' => $end
        ];
    }

    //获取当月的开始与结束的时间戳
    public static function getMonthTime()
    {
        //date_default_timezone_set('PRC');
        $begin = mktime(0, 0, 0, date('m'), 1, date('Y'));
        $end = mktime(23, 59, 59, date('m'), date('t'), date('Y'));

        return [
            'begin' => $begin,
            'end' => $end
        ];
    }

    //获取今天开始与结束的时间戳
    public static function getTodayStamp()
    {
        //date_default_timezone_set('PRC');
        $t = time();
        $start = mktime(0, 0, 0, date("m", $t), date("d", $t), date("Y", $t));
        $end = mktime(23, 59, 59, date("m", $t), date("d", $t), date("Y", $t));
        return [
            'begin'=>$start,
            'end'=>$end
        ];
    }

    //返回当前时间，精确到秒
    public static function getTimeNow()
    {
        //date_default_timezone_set('PRC');
        return time();
    }

    //取当前时间毫秒数  1530089455.8467 格式
    public static function getMicroTimeNow()
    {
        return microtime(true);
    }

    //获取当前毫秒时间搓  1586160271537 格式
    public static function getMicroTimeNow2() {
        list($msec, $sec) = explode(' ', microtime());
        return (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
    }


    //返回今天的开始时间戳
    public static function getTodayTimeBegin()
    {
        //date_default_timezone_set('PRC');
        $t = time();
        return mktime(0, 0, 0, date("m", $t), date("d", $t), date("Y", $t));
    }

    //返回今天的结束时间戳
    public static function getTodayTimeEnd()
    {
        //date_default_timezone_set('PRC');
        $t = time();
        return mktime(23, 59, 59, date("m", $t), date("d", $t), date("Y", $t));
    }

    //返回 日期的一天开始时间搓  date格式为 格式为 2018-05-20:08:08:08
    public static function getDateTimeBegin($date)
    {
        //date_default_timezone_set('PRC');
        $t = strtotime($date);
        return mktime(0, 0, 0, date("m", $t), date("d", $t), date("Y", $t));
    }

    //返回 日期的一天结束时间搓 date格式为 格式为 2018-05-20:08:08:08
    public static function getDateTimeEnd($date)
    {
        //date_default_timezone_set('PRC');
        $t = strtotime($date);
        return mktime(23, 59, 59, date("m", $t), date("d", $t), date("Y", $t));
    }

    //时间搓转化为字符串日期格式 有时分秒
    public static function getDateYMD_HIS($time)
    {
        //date_default_timezone_set('PRC');
        return date('Y-m-d H:i:s', $time);
    }

    //把字符串时间格式化
    public static function timeFormat(string $time): string
    {
        $val  = self::getTime($time);//时间
        return self::getDateYMD_HIS($val);
    }

    //取显示的 年月日全格式
    public static function getNowYMD_HIS()
    {
        return date('Y-m-d H:i:s');
    }

    //取显示的 年月日全格式
    public static function getNowYMD_HIS_Pay()
    {
        return date('YmdHis');
    }

    //时间搓转化为字符串日期格式 无时分秒
    public static function getDateYMD($time)
    {
        //date_default_timezone_set('PRC');
        return date('Y-m-d', $time);
    }

    //时间搓转化为字符串日期格式 无时分秒
    // yyyy-MM-DD
    public static function getTodayDateYMD1()
    {
        //date_default_timezone_set('PRC');
        return date('Y-m-d');
    }

    //时间搓转化为字符串日期格式 无时分秒
    public static function getDateY($time)
    {
        //date_default_timezone_set('PRC');
        return date('Y', $time);
    }

    //返回今天的年月日时间，没有分割符     $dayAdd:+1  加上1天
    public static function getTodayDateYMDPress($dayAdd = null)
    {
        //date_default_timezone_set('PRC');
        if (is_null($dayAdd)) return date('Ymd');
        else return date('Ymd', strtotime("$dayAdd day"));
    }

    //返回昨天 年月日 时间
    public static function getYesterdayYMD()
    {
        return self::getTodayDateYMD(-1);
    }

    //返回明天 年月日 时间
    public static function getTomorrowYMD()
    {
        return self::getTodayDateYMD(1);
    }

    //返回现在时间的年月日格式字符串
    public static function getTodayDateYMD($dayAdd = null)
    {
        //date_default_timezone_set('PRC');
        if (is_null($dayAdd)) return date("Y-m-d");//年月日时间
        else return date("Y-m-d", strtotime("$dayAdd day"));
    }

    //取某日期的前几天或后几天 $addDay:1 后一天
    public static function getDateYMDNextPress($date, $addDay)
    {
        $time = strtotime($date) + (3600 * 24 * $addDay);
        return date("Ymd", $time);
    }

    //取某日期的前几天或后几天 $addDay:1 后一天  $dateTime为时间搓格式
    public static function getDateYMDNextPress2($dateTime, $addDay = null)
    {
        if (is_null($addDay)) $addDay = 0;
        $time = $dateTime + (3600 * 24 * $addDay);
        return date("Ymd", $time);
    }

    //取某日期的前几天或后几天 $addDay:1 后一天
    public static function getDateYMDNext($date, $addDay = null)
    {
        if (is_null($addDay)) $addDay = 0;
        $time = strtotime($date) + (3600 * 24 * $addDay);
        return date("Y-m-d", $time);
    }

    //取某日期的前几天或后几天 $addDay:1 后一天  $dateTime为时间搓格式 返回的也是时间搓
    public static function getTimeAddDay($dateTime, $addDay = null)
    {
        if (is_null($addDay)) $addDay = 0;
        return $dateTime + (3600 * 24 * $addDay);
    }

    //返回某日期是一年中第几天
    public static function getDateDayNum($dateStr, $dayAdd)
    {
        //date_default_timezone_set('PRC');
        if (is_null($dayAdd)) return date("z", strtotime($dateStr));//1年中的第几天
        else return date("z", strtotime("$dateStr $dayAdd day"));
    }

    //取2个字符串时间格式相差的天数   $dateBegin:2016-04-10 格式
    public static function getDateDisNum($dateBegin, $dateEnd): int
    {
        $begin = strtotime($dateBegin);
        $end   = strtotime($dateEnd);
        $days  = round(($end - $begin) / 3600 / 24);
        return $days;
    }

    //返回现在时间的时分秒格式字符串
    public static function getTodayDateHIS()
    {
        //date_default_timezone_set('PRC');
        return date("H:i:s");//当天的小时时间
    }

    //时间增加几秒 H:i:s 格式  $addSec: +1 加1秒   -1为减1秒
    public static function getHisAddSecond($t, $addSec)
    {
        //date_default_timezone_set('PRC');
        return date("H:i:s", strtotime("$t $addSec second"));//当天的小时时间
    }

    //截取字符串时间的 小时 分钟 秒 数据
    public static function getStrDateHIS($timeStr)
    {
        //date_default_timezone_set('PRC');
        return date("H:i:s", strtotime($timeStr));//当天的小时时间
    }

    //截取字符串时间的 年 月 日 数据
    public static function getStrDateYMD($timeStr)
    {
        //date_default_timezone_set('PRC');
        return date("Y-m-d", strtotime($timeStr));//年月日时间
    }

    //取时间相差的秒数  $startdate 为字符串格式
    public static function getDateDiffTime($startdate, $enddate)
    {
        //date_default_timezone_set('PRC');
        return strtotime($enddate) - strtotime($startdate);
    }

    //把字符时间格式转化为时间戳
    public static function getTime($date)
    {
        //date_default_timezone_set('PRC');
        return strtotime($date);
    }

    //把秒倒计时转化为时间格式
    public static function getDateHIS($time)
    {
        //date_default_timezone_set('PRC');
        return (new \DateTime('@0')) ->diff(new \DateTime("@$time")) ->format('%H:%I:%S');
    }

    //把秒倒计时转化为天格式
    public static function getDateDayTian($time)
    {
        //date_default_timezone_set('PRC');
        $day = (int)($time / 86400);
        return $day."天";
    }

    //返回最小时间 字符串
    public static function getDateMin()
    {
        return "1800-01-01";
    }

    //返回最小时间
    public static function getYMD_HISMin()
    {
        return "1800-01-01 00:00:01";
    }

    //返回最大时间 字符串
    public static function getDateMax()
    {
        return "2050-01-01";
    }

    //返回最大时间 时间搓
    public static function getTimeMax()
    {
        return self::getTime(self::getDateMax());
    }

    //把 20180601这种时间转为 2018-06-01日期格式
    public static function getDateByStr($str)
    {
        $date = Str::substr($str, 0, 4);
        $date = $date."-".Str::substr($str, 4, 2);
        $date = $date."-".Str::substr($str, 6, 2);
        return $date;
    }

    //日期是星期几，星期一:1，星期天:7
    public static function getWeek(string $time) :int
    {
        $i = date("w", strtotime($time));
        if ($i == 0) $i = 7;
        return $i;
    }

    // 取今天是星期几，1~7
    public static function getDayOfWeekBase1Now()
    {
        $i = date('w');
        if ($i == 0) $i = 7;
        return $i;
    }

}

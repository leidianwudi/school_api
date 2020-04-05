<?php
/**
 * Created by PhpStorm.
 * User: FBI
 * Date: 2018/4/28
 * Time: 9:31
 */
namespace App\Model;

use App\Common\Str;
use App\InterfaceEntity\BaseEntity;
use Illuminate\Database\Eloquent\Model;

//数据库操作基类
class BaseModel extends Model
{
    public static $notInt = -1;//没有传数字
    public static $notStr = "";//没有传字符串

    //设置要保存的数据，$en为object类型
    public function setObject($obj)
    {
        foreach ($obj as $key => $val)
        {
            if (!is_null($val))
            {
                $this->$key = $val;//赋值
            }
        }
    }

    //根据类型查询，$en为object类型
    public static function selByObject($obj)
    {
        $sql = self::getWhereSql($obj);
        return self::whereRaw($sql)->get();
    }

    //返回查询query
    public static function getQueryByObject($obj)
    {
        $query = self::query();
        foreach ($obj as $key => $val)
        {
            //var_dump($key." is ".$val);
            if (CommonTool::empty($val)) continue;//不能为空
            $query = $query->where($key, $val);
        }
        //var_dump($query->toSql());
        return $query;
    }

    //根据obj信息添加查询到 $query 中
    public static function addQueryByObj($query, $obj)
    {
        foreach ($obj as $key => $val)
        {
            //var_dump($key." is ".$val);
            if (is_null($val) || (is_string($val) && ("" == $val))) continue;//不能为空
            $query = $query->where($key, $val);
        }
        //var_dump($query->toSql());
        return $query;
    }

    //反回类中字段对应的where语句
    public static function getWhereSql($en)
    {
        $sql = "";
        foreach ($en as $key => $val)
        {
            if (null == $val) continue;//不能为空
            $where = "";
            if (is_int($val) && $val != self::$notInt)
                $where.= Str::format("{0}={1}", $key, $val);

            if (is_string($val) && $val != self::$notStr)
                $where = Str::format("{0}='{1}'", $key, $val);

            if ($where != "")
            {
                if ($sql != "") $sql .= " and ";
                $sql .= $where;
            }
        }
        if ($sql == "") $sql .= "1=1";
        return $sql;
    }

    //把2维数组数据以某个字段归类成新数组
    protected static function getGroup($inArr, $name)
    {
        if ($inArr == null) return null;
        $outArr = array();
        foreach ($inArr as $val)
        {
            $outArr[$val[$name]][] = $val;
        }
        return $outArr;
    }

}

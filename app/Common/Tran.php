<?php


namespace App\Common;

use App\InterfaceEntity\BaseEntity;
use Symfony\Component\HttpFoundation\Request;

//常用操作工具类
class Tran
{
    /** 循环post数据中的字段，若ent中也有定义该字段则赋值
     * @param Request $rep
     * @param BaseEntity|null $ent
     */
    public static function getEntityFromPost(Request $rep, BaseEntity &$ent = null)
    {
        $strJson = $rep->getContent();//取post内Json格式数据
        $a = self::json2Obj($strJson);//直接转换成类
        self::setObjFromObjAuto($ent, $a);
    }

    /** 把$objLess的字段都填充到$objFull中，$objFull字段必须包括$objLess所有非null字段
     * @param $objFull
     * @param $objLess
     */
    public static function setObjFullFromObjLess(&$objFull, $objLess)
    {
        if (is_object($objLess)) {
            foreach ($objLess as $key => $val)
            {
                if (!property_exists($objFull, $key) && null != $val) {var_dump("字段不存在".$key);}
                if (property_exists($objFull, $key))
                    $objFull->$key = $val;
            }
        }
    }

    /** 从$objFull中找$objLess需要的数据，也支持从数组中取出数据
     * @param $objLess
     * @param $objFull
     */
    public static function setObjLessFromObjFull(&$objLess, $objFull)
    {
        foreach ($objLess as $key => $val) {
            $objLess->$key = $objFull->$key;
        }
    }

    /** 把$obj2的字段都填充到$obj1中存在的字段，也支持从数组中取出数据
     * @param $obj1
     * @param $obj2
     */
    public static function setObjFromObjAuto(&$obj1, $obj2)
    {
        foreach ($obj2 as $key => $val) {
            if (property_exists($obj1, $key)) {
                $obj1->$key = $val;
            }
        }
    }

    /** 把$objFrom直接赋值给$objTo
     * @param $objTo
     * @param $objFrom
     */
    public static function setObjFromObjEquTo(&$objTo, $objFrom)
    {
        $objTo = $objFrom;
    }

    /** 把类转化为可以保存的本地字符串
     * @param $obj
     * @return string
     */
    public static function obj2LocalStr($obj)
    {
        return serialize($obj);
    }

    /** 把本地保存的字符串转为类
     * @param $str
     * @return mixed
     */
    public static function localStr2Obj($str)
    {
        return unserialize($str);
    }

    /** 把字符串转为类
     * @param $str
     * @return mixed
     */
    public static function json2Obj($str)
    {
        return json_decode($str);
    }

    /** 把类转为字符串
     * @param $obj
     * @return false|string
     */
    public static function obj2Json($obj)
    {
        return json_encode($obj);
    }

    /** 数组值转为字符串
     * @param array $arr:数组
     * @param $split:分隔符
     * @return string:
     */
    public static function arr2Str(array $arr, $split) : string
    {
        return implode($split, $arr);
    }

    /** 对象转数组
     * @param $obj
     * @return mixed
     */
    public static function obj2Arr($obj)
    {
        return json_decode( json_encode($obj), true);
    }

}

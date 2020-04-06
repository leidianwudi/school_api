<?php
/**
 * Created by: PhpStorm.
 * User: yj
 * Date: 2020/4/7
 * Time: 0:17
 */

namespace App\Service\Subject;


use App\InterfaceEntity\InputEntity\InPage;
use App\InterfaceEntity\InputEntity\School\InGetSchool;
use App\InterfaceEntity\OutputEntity\ResultData;
use App\Model\Subject\Subject_modelSp;

//学校
class SchoolCore
{
    //查询学校
    public static function getSchool(InGetSchool $in): ResultData
    {
        $res = new ResultData();   //创建返回类
        return  $res->setData(Subject_modelSp::getSchool($in->school, $in->profession, $in->page, $in->count));
    }

    //查询专业
    public static function getProfession(InGetSchool $in): ResultData
    {
        $res = new ResultData();   //创建返回类
        return  $res->setData(Subject_modelSp::getProfession($in->school, $in->profession, $in->page, $in->count));
    }

    //查询首选科目
    public static function getSubject1(InPage $in): ResultData
    {
        $res = new ResultData();   //创建返回类
        return  $res->setData(Subject_modelSp::getSubject1($in->page, $in->count));
    }

    //查询再选科目
    public static function getSubject2(InPage $in): ResultData
    {
        $res = new ResultData();   //创建返回类
        return  $res->setData(Subject_modelSp::getSubject2($in->page, $in->count));
    }
}

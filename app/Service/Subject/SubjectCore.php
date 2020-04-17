<?php
/**
 * Created by: PhpStorm.
 * User: yj
 * Date: 2020/4/7
 * Time: 2:34
 */

namespace App\Service\Subject;


use App\InterfaceEntity\InputEntity\School\InGetSchool;
use App\InterfaceEntity\InputEntity\Subject\InGetSubject;
use App\InterfaceEntity\OutputEntity\ResultData;
use App\Model\Subject\Subject_modelSp;

//课程
class SubjectCore
{
    //查询可选的课程
    public static function getSubject(InGetSubject $in): ResultData
    {
        $res = new ResultData();   //创建返回类
        return  $res->setData(Subject_modelSp::getSubject($in));
    }

    //按首选和再选科目统计符合条件的科目大类及所占百分比
    public static function getProfessionPer(InGetSubject $in): ResultData
    {
        $res = new ResultData();   //创建返回类
        return  $res->setData(Subject_modelSp::getProfessionPer($in));
    }

    //按学校或专业统计符合条件的首选再选科目及其百分比
    public static function getSubjectPer(InGetSubject $in): ResultData
    {
        $res = new ResultData();   //创建返回类
        return  $res->setData(Subject_modelSp::getSubjectPer($in));
    }
}

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
}

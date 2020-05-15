<?php
/**
 * Created by: PhpStorm.
 * User: yj
 * Date: 2020/4/7
 * Time: 2:36
 */

namespace App\InterfaceEntity\InputEntity\Subject;

use App\InterfaceEntity\InputEntity\InPage;

//查询专业
class InGetSubject extends InPage
{
    public $school;     //学校名称
    public $menke;      //学类，类别
    public $profession; //专业名称
    public $subject1;   //首选科目
    public $subject2;   //再选科目
}

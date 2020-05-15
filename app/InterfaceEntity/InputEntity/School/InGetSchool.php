<?php
/**
 * Created by: PhpStorm.
 * User: yj
 * Date: 2020/4/7
 * Time: 0:39
 */

namespace App\InterfaceEntity\InputEntity\School;


use App\InterfaceEntity\InputEntity\InPage;

//查询学校
class InGetSchool extends InPage
{
    public $school;     //学校名称
    public $menke;      //学类
    public $profession; //专业名称
}

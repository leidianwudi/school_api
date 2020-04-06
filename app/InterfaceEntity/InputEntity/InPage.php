<?php
/**
 * Created by: PhpStorm.
 * User: yj
 * Date: 2020/4/7
 * Time: 0:20
 */

namespace App\InterfaceEntity\InputEntity;


use App\InterfaceEntity\BaseEntity;

//分页查询
class InPage extends BaseEntity
{
    public $page;   //第几页
    public $count;  //每页记录数
}

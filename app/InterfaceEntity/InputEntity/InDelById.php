<?php
/**
 * Created by: PhpStorm.
 * User: yj
 * Date: 2020/2/17
 * Time: 18:56
 */

namespace App\InterfaceEntity\InputEntity;


use App\InterfaceEntity\BaseEntity;


//根据id删除
class InDelById extends BaseEntity
{
    public int $id; //记录id
}

//根据id批量删除
class InDelByArr extends BaseEntity
{
    public array $arr; //InDelById 数组
}


<?php
/**
 * Created by: PhpStorm.
 * User: yj
 * Date: 2019/12/2
 * Time: 20:45
 */

namespace App\InterfaceEntity;


use App\Common\Tran;

//用户传输结构数据基类
class BaseEntity
{
    public function getEntityByObj(&$en, $obj)
    {
        Tran::setObjFromObjAuto($en, $obj);
        return $en;
    }
}

<?php
/**
 * Created by: PhpStorm.
 * User: yj
 * Date: 2020/4/6
 * Time: 15:11
 */

namespace App\Model\Setting;

//配置表
class Setting_modelSp extends Setting_model
{
    //根据类型查询培训
    public static function getByType(string $type): ?Setting_entity
    {
        $query = self::query()->where("type", $type);
        return self::getEntityByQuery($query);
    }
}

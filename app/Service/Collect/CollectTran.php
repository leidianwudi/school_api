<?php
/**
 * Created by: PhpStorm.
 * User: yj
 * Date: 2020/4/6
 * Time: 17:01
 */

namespace App\Service\Collect;

use App\Common\Time;
use App\Common\Tran;
use App\Common\Util;
use App\Model\Subject\Subject_entity;

//采集类型转换
class CollectTran
{
    //返回数据库表类型结构
    public static function getEntityByJson($json): Subject_entity
    {
        $obj = self::getCollectData($json);
        return self::getSubjectEntity($obj);
    }

    //取转换后的抓取数据
    public static function getCollectData($json): CollectData
    {
        $obj = new CollectData();
        Tran::setObjLessFromObjFull($obj, $json);
        return $obj;
    }

    //取数据库中类型数据
    public static function getSubjectEntity(CollectData $obj): Subject_entity
    {
        $en = new Subject_entity();
        $en->collectId      = $obj->id;         //采集id
        $en->school         = $obj->school_name;//学校
        $en->profession     = $obj->subject_name;
        $en->professionSub  = $obj->subject_detail;
        $en->subject1       = $obj->fsubject;   //主选
        $en->subject2       = $obj->ssubject;   //副选
        $en->updTime        = Time::timeFormat($obj->update_time);//更新时间
        return $en;
    }
}

//采集后一科目数据
class CollectData
{
    public $id;             //id
    public $school_name;    //学校
    public $subject_name;   //招生专业
    public $subject_detail; //包含专业
    public $fsubject;       //首选科目
    public $ssubject;       //次选科目
    public $update_time;    //更新时间
}

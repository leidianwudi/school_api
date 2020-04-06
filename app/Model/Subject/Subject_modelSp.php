<?php
/**
 * Created by: PhpStorm.
 * User: yj
 * Date: 2020/4/6
 * Time: 14:21
 */

namespace App\Model\Subject;

//科目表操作
use App\Common\Util;
use App\InterfaceEntity\InputEntity\Subject\InGetSubject;

class Subject_modelSp extends Subject_model
{
    //根据采集id查询
    public static function getByCollectId($id): ?Subject_entity
    {
        $query = self::query()->where("collectId", $id);
        return self::getEntityByQuery($query);
    }

    //查询学校
    public static function getSchool($school, $profession, $page, $count)
    {
        $query = self::query();
        if (!Util::isEmpty($school)) $query = $query->where("school", "like", "%".$school."%");
        if (!Util::isEmpty($profession)) $query = $query->where("profession", "like", "%".$profession."%");
        return $query->groupBy("school")->paginate($count, ["school"], "page", $page);
    }

    //查询专业
    public static function getProfession($school, $profession, $page, $count)
    {
        $query = self::query();
        if (!Util::isEmpty($school)) $query = $query->where("school", "like", "%".$school."%");
        if (!Util::isEmpty($profession)) $query = $query->where("profession", "like", "%".$profession."%");
        return $query->groupBy("profession")->paginate($count, ["profession"], "page", $page);
    }

    //查询首选科目
    public static function getSubject1($page, $count)
    {
        return self::query()->groupBy("subject1")->paginate($count, ["subject1"], "page", $page);
    }

    //查询再选科目
    public static function getSubject2($page, $count)
    {
        return self::query()->groupBy("subject2")->paginate($count, ["subject2"], "page", $page);
    }

    //查询可选的课程
    public static function getSubject(InGetSubject $in)
    {
        $query = self::query();
        if (!Util::isEmpty($in->school)) $query = $query->where("school", "like", "%".$in->school."%");
        if (!Util::isEmpty($in->profession)) $query = $query->where("profession", "like", "%".$in->profession."%");
        if (!Util::isEmpty($in->subject1)) $query = $query->where("subject1", $in->subject1);
        if (!Util::isEmpty($in->subject2)) $query = $query->where("subject2", $in->subject2);
        return $query->paginate($in->count, ["*"], "page", $in->page);
    }
}

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
use Illuminate\Support\Facades\DB;

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

    //按首选和再选科目统计符合条件的科目大类及所占百分比
    public static function getProfessionPer(InGetSubject $in)
    {
        $query = self::query();
        if (!Util::isEmpty($in->subject1)) $query = $query->where("subject1", $in->subject1);
        if (!Util::isEmpty($in->subject2)) $query = $query->where("subject2", $in->subject2);
        return $query->groupBy("profession")->orderBy("sum", "desc")->paginate($in->count, DB::raw(
            "profession as pro, count(*) as sum, (select count(*) from  subject as sub
            where  sub.profession = pro) as sumAll"
        ), "page", $in->page);
    }

    //按学校或专业统计符合条件的首选再选科目及其百分比
    public static function getSubjectPer(InGetSubject $in)
    {
        $count = self::query()->count();    //总数量
        $query = self::query();
        if (!Util::isEmpty($in->school)) $query = $query->where("school", $in->school);
        if (!Util::isEmpty($in->profession)) $query = $query->where("profession", $in->profession);
        return $query->groupBy(["subject1", "subject2"])->orderBy("sum", "desc")->paginate($in->count, DB::raw(
            "subject1, subject2, count(*) as sum, count(*) * 100 / 26261 as per"
        ), "page", $in->page);
    }
}

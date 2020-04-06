<?php
/**
由ModelFactory自动生成，请勿手动修改
*/
namespace App\Model\Subject;

use App\Model\BaseModel;
use App\Common\Util;
use App\Common\Tran;


/*科目表 基础操作*/
class Subject_model extends BaseModel
{
    protected $table = "subject";
    protected $primaryKey = "id";
    public $timestamps = false;//不更新时间

    //通用查询操作，返回entity或null
    public static function getEntityBySql($sql) : ?Subject_entity
    {
        $arr = parent::whereRaw($sql)->first();
        return self::getEntityFromAttr($arr);
     }

    //通用查询操作，返回entity或null
    public static function getEntityByQuery($query) : ?Subject_entity
    {
        $arr = $query->first();
         return self::getEntityFromAttr($arr);
     }

    //根据主键id查询，返回entity或null
    public static function getEntityById($id) : ?Subject_entity
    {
        return self::getEntityBySql("id = $id");
    }

    //查询，page分页
    public static function getByIdPage($col, $page, $count)
    {
        $query = parent::query();
        if (!Util::empty($col)) $query = $query->where("id", $col);
        return $query->orderby("id", "desc")->paginate($count, ["*"], "page", $page);
    }

    //添加一条数据操作，returnId:是否返回新添加的主键id true:返回 false:不返回
    public static function insEntity(Subject_entity $en, bool $returnId = false) : bool
    {
        $model = new Subject_model();
        $model->setObject($en);
        $res = $model->save();
        if ($returnId) $en->id = $model->id;
        return $res;
    }

    //修改一条数据操作
    public static function updEntity(Subject_entity $en) : bool
    {
        $model = Subject_model::find($en->id);
        $model->setObject($en);
        return $model->save();
    }

    //添加或修改一条数据操作
    public static function setEntity(Subject_entity $en) : bool
    {
        if (is_null($en->id))
           return self::insEntity($en);
        else
           return self::updEntity($en);
    }

    //删除一条数据操作
    public static function delEntity($id) : bool
    {
        $model = Subject_model::find($id);
        return $model->delete();
    }

    //类型转换函数
    public static function getEntityFromAttr($arr) : ?Subject_entity
    {
        if (Util::isEmpty($arr)) return null;
        $en = new Subject_entity();
        Tran::setObjFromObjAuto($en, $arr->attributes);
        return $en;
    }

}
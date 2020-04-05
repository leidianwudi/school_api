<?php
/**
 * Created by PhpStorm.
 * User: FBI
 * Date: 2018/4/30
 * Time: 14:01
 */

namespace App\Model;

use App\Common\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

//字段创建表的简单查询修改操作
class ModelFactory
{
    private static $s_table = "";//表名

    //自动创建数据库简单操作
    public static function create($nameSpace, $table, $column)
    {
        //$className = Str::replaceFirst("qc_", "", $table);  //去掉qc_
        $className = $table;
        $className = Str::ucfirst($className)."_model";                     //类名，第一个字符大写
        var_dump($className);
        $phpName = $className.".php";                                       //文件名
        $phpPath = app_path()."\\Model\\".$nameSpace."\\".$phpName;         //完整路径

        self::$s_table = $table;//保存表名

        $phpFile = fopen($phpPath, "w");                                    //打开或创建文件
        self::writeHead($phpFile, $nameSpace);                              //写头文件
        self::writeAttribute($phpFile, $table, $className);                 //写属性
        self::writeEntitySelFunction($phpFile, $className);                 //写通用查询操作
        self::writeEntityQueryFunction($phpFile, $className);               //写通用查询操作
        self::writeEntityByIdFunction($phpFile, $className);                //写根据id查询记录方法
        self::writeSelFunction($phpFile, $column);                          //写查询方法page分页
        self::writeInsEntityFunction($phpFile, $className);                 //添加一条数据操作
        self::writeUpdEntityFunction($phpFile, $className);                 //修改一条数据操作
        self::writeSetEntityFunction($phpFile, $className);                 //添加或修改一条数据操作
        self::writeDelEntityFunction($phpFile, $className);                 //删除一条数据操作
        self::writeEntityTran($phpFile, $className);                        //写类转换函数
        self::writeEnd($phpFile);                                           //类结束
        fclose($phpFile);
    }

    /**写头文件内容
     * @param $phpFile:文件类
     * @param $nameSpace:命名空间字符串
     */
    private static function writeHead($phpFile, $nameSpace)
    {
        $txt = "<?php".
            "\n/**".
            "\n由ModelFactory自动生成，请勿手动修改".
            "\n*/".
            "\nnamespace App\\Model\\".$nameSpace.";".
            "\n\n".
            "use App\Model\BaseModel;\n".
            "use App\Common\Util;\n".
            "use App\Common\Tran;\n".
            "\n\n";
        fwrite($phpFile, $txt);
    }

    //取表的主键，为第一个
    private static function getTableKey($table)
    {
        $commentS = DB::Select("show full fields from ".$table);
        //dd($commentS);
        foreach ($commentS as $comment)
        {
            return $comment->Field;
        }
        return var_dump("getTableKey 出错了，请修改！");
    }

    //写属性
    private static function writeAttribute($phpFile, $table, $className)
    {
        $txt = Str::format("/*{0} 基础操作*/\n", ModelEntityFactory::getTableComment($table));
        $txt .= "class ".$className." extends BaseModel\n{\n".
            "    protected \$table = \"".$table."\";\n".
            "    protected \$primaryKey = \"".self::getTableKey($table)."\";\n".
            "    public \$timestamps = false;//不更新时间\n";
        fwrite($phpFile, $txt);
    }

    //写通用查询操作，返回具体类型
    private static function writeEntitySelFunction($phpFile, $className)
    {
        $txt = "\n    //通用查询操作，返回entity或null";
        $className = Str::replaceLast("_model", "_entity", $className);  //数据类型名
        $txt .= Str::format("\n    public static function getEntityBySql(\$sql) : ?{0}\n", $className);
        $txt .= "    {\n";
        $txt .= Str::format("        \$arr = parent::whereRaw(\$sql)->first();\n");
        $txt .= Str::format("        return self::getEntityFromAttr(\$arr);\n");      //返回类型
        $txt .= "     }\n";
        fwrite($phpFile, $txt);
    }

    //写通用查询操作，返回具体类型
    private static function writeEntityQueryFunction($phpFile, $className)
    {
        $txt = "\n    //通用查询操作，返回entity或null";
        $className = Str::replaceLast("_model", "_entity", $className);  //数据类型名
        $txt .= Str::format("\n    public static function getEntityByQuery(\$query) : ?{0}\n", $className);
        $txt .= "    {\n";
        $txt .= Str::format("        \$arr = \$query->first();\n");
        $txt .= Str::format("         return self::getEntityFromAttr(\$arr);\n");      //返回类型
        $txt .= "     }\n";
        fwrite($phpFile, $txt);
    }

    //写根据主键id查数据方法，返回具体类型
    private static function writeEntityByIdFunction($phpFile, $className)
    {
        $txt = "\n    //根据主键id查询，返回entity或null";
        $className = Str::replaceLast("_model", "_entity", $className);  //数据类型名
        $key = self::getTableKey(self::$s_table);//取主键
        $txt .= Str::format("\n    public static function getEntityById(\$id) : ?{0}\n", $className);
        $txt .= "    {\n";
        $txt .= Str::format("        return self::getEntityBySql(\"$key = \$id\");\n");
        $txt .= "    }\n";
        fwrite($phpFile, $txt);
    }

    //写查询操作，page分页
    private static function writeSelFunction($phpFile, $column)
    {
        $key = self::getTableKey(self::$s_table);//取主键
        $txt = "\n    //查询，page分页";
        $txt .= Str::format("\n    public static function getBy{0}Page(\$col, \$page, \$count)\n", Str::ucfirst($column)); //首字母大写
        $txt .= "    {\n";
        $txt .= "        \$query = parent::query();\n";
        $txt .= "        if (!Util::empty(\$col)) \$query = \$query->where(\"$column\", \$col);\n";
        $txt .= Str::format("        return \$query->orderby(\"{0}\", \"desc\")->paginate(\$count, [\"*\"], \"page\", \$page);\n    }\n", $key);
        fwrite($phpFile, $txt);
    }

    //添加一条数据操作
    private static function writeInsEntityFunction($phpFile, $modelClass)
    {
        $key = self::getTableKey(self::$s_table);//取主键
        $txt = "\n    //添加一条数据操作，returnId:是否返回新添加的主键id true:返回 false:不返回";
        $enClass = Str::replaceLast("_model", "_entity", $modelClass);  //数据类型名
        $txt .= "\n";
        $txt .= "    public static function insEntity($enClass \$en, bool \$returnId = false) : bool\n";
        $txt .= "    {\n";
        $txt .= "        \$model = new $modelClass();\n";
        $txt .= "        \$model->setObject(\$en);\n";
        $txt .= "        \$res = \$model->save();\n";
        $txt .= "        if (\$returnId) \$en->$key = \$model->$key;\n";
        $txt .= "        return \$res;\n";
        $txt .= "    }\n";
        fwrite($phpFile, $txt);
    }

    //修改一条数据操作
    private static function writeUpdEntityFunction($phpFile, $modelClass)
    {
        $txt = "\n    //修改一条数据操作";
        $enClass = Str::replaceLast("_model", "_entity", $modelClass);  //数据类型名
        $key = self::getTableKey(self::$s_table);//取主键
        $txt .= "\n";
        $txt .= "    public static function updEntity($enClass \$en) : bool\n";
        $txt .= "    {\n";
        $txt .= "        \$model = $modelClass::find(\$en->$key);\n";
        $txt .= "        \$model->setObject(\$en);\n";
        $txt .= "        return \$model->save();\n";
        $txt .= "    }\n";
        fwrite($phpFile, $txt);
    }

    //添加或修改一条数据操作
    private static function writeSetEntityFunction($phpFile, $modelClass)
    {
        $txt = "\n    //添加或修改一条数据操作";
        $enClass = Str::replaceLast("_model", "_entity", $modelClass);  //数据类型名
        $key = self::getTableKey(self::$s_table);//取主键
        $txt .= "\n";
        $txt .= "    public static function setEntity($enClass \$en) : bool\n";
        $txt .= "    {\n";
        $txt .= "        if (is_null(\$en->$key))\n";
        $txt .= "           return self::insEntity(\$en);\n";
        $txt .= "        else\n";
        $txt .= "           return self::updEntity(\$en);\n";
        $txt .= "    }\n";
        fwrite($phpFile, $txt);
    }

    //删除一条数据操作
    private static function writeDelEntityFunction($phpFile, $modelClass)
    {
        $txt = "\n    //删除一条数据操作";
        $txt .= "\n";
        $txt .= "    public static function delEntity(\$id) : bool\n";
        $txt .= "    {\n";
        $txt .= "        \$model = $modelClass::find(\$id);\n";
        $txt .= "        return \$model->delete();\n";
        $txt .= "    }\n";
        fwrite($phpFile, $txt);
    }

    //写类型转换函数
    private static function writeEntityTran($phpFile, $className)
    {
        $txt = "\n    //类型转换函数";
        $className = Str::replaceLast("_model", "_entity", $className);  //数据类型名
        $txt .= Str::format("\n    public static function getEntityFromAttr(\$arr) : ?{0}\n", Str::ucfirst($className)); //首字母大写
        $txt .= "    {\n";
        $txt .= "        if (Util::isEmpty(\$arr)) return null;\n";
        $txt .= "        \$en = new $className();\n";
        $txt .= "        Tran::setObjFromObjAuto(\$en, \$arr->attributes);\n";
        $txt .= "        return \$en;\n";
        $txt .= "    }\n";
        fwrite($phpFile, $txt);
    }

    //类结束
    private static function writeEnd($phpFile)
    {
        fwrite($phpFile, "\n}");
    }

}

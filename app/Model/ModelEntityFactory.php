<?php
/**
 * Created by PhpStorm.
 * User: FBI
 * Date: 2018/4/29
 * Time: 21:13
 */
namespace App\Model;

use App\Common\Arr;
use App\Common\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

//自动创建数据库表对应类
class ModelEntityFactory
{
    //自动生成表对应数据结构
    public static function create($nameSpace, $table)
    {
        //$className = Str::replaceFirst("qc_", "", $table);  //去掉qc_
        $className = $table;
        $className = Str::ucfirst($className)."_entity";                    //类名，第一个字符大写
        var_dump($className);
        $phpName = $className.".php";                                       //文件名
        $phpPath = app_path()."\\Model\\".$nameSpace."\\".$phpName;         //完整路径

        $phpFile = fopen($phpPath, "w");                             //打开或创建文件
        self::writeHead($phpFile, $nameSpace);                              //写头文件
        self::writeEntity($phpFile, $table, $className);                    //写类定义
        fclose($phpFile);                                                   //关闭
    }

    /**写头文件内容
     * @param $phpFile:文件类
     * @param $nameSpace:命名空间字符串
     */
    private static function writeHead($phpFile, $nameSpace)
    {
        $txt = "<?php".
            "\n/**".
            "\n由ModelEntityFactory自动生成，请勿手动修改".
            "\n*/".
            "\nnamespace App\\Model\\".$nameSpace.";".
            "\n\n";
        fwrite($phpFile, $txt);
    }

    //获取表的所有字段
    private static function getTableColumns($table)
    {
        return Schema::getColumnListing($table);
    }

    //获取某列注释
    private static function getColumnComment($table, $column)
    {
        $commentS = DB::Select("show full fields from ".$table);
        //dd($commentS);
        foreach ($commentS as $comment)
        {
            if ($comment->Field == $column)//找到对应列
            {
                $def = $comment->Default != null ? $comment->Default : "null"; //默认值
                $isKey = $comment->Key != "" ? "isKey; " : "";                      //是否主键
                return $isKey.$comment->Type."; Null:".$comment->Null." ;Default:".$def." ;Comment:".$comment->Comment;
            }
        }
        return var_dump("getColumnComment 出错了，请修改！");
    }

    /**生成表对应的结构类
     * @param $phpFile: 文件类
     * @param $table: 要生成类结构的表
     * @param $className: 生成的类名
     */
    private static function writeEntity($phpFile, $table, $className)
    {
        $tableComment = self::getTableComment($table);                  //表注释
        $arrColumns = self::getTableColumns($table);                    //所有列名
        $lenMax = Arr::getArrColumnMaxLength($arrColumns);        //数组内元素最大长度
        $txt = $tableComment != "" ? "/*".$tableComment."*/" : "";            //表注释不为空就写上
        $txt .= "\nclass ".$className."\n{";
        foreach ($arrColumns as $key=> $val)
        {
            $varFull = str_pad($val.";", $lenMax + 5); //补全空字符
            $txt .= "\n   public $".$varFull;
            $desc = self::getColumnComment($table, $val);                //取本列注释
            $txt .= "/*".$desc."*/";
        }
        $txt .= "\n}";
        fwrite($phpFile, $txt);
    }

    //取表说明
    public static function getTableComment($table)
    {
        $commentS = DB::Select("show TABLE status");
        foreach ($commentS as $comment)
        {
            if ($comment->Name == $table)//找到对应表
            {
                return $comment->Comment;
            }
        }
        return var_dump("getTableComment 出错了，请修改！");
    }

}

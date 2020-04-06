<?php
/**
 * Created by PhpStorm.
 * User: FBI
 * Date: 2018/4/28
 * Time: 11:36
 */

namespace App\Http\Controllers;


use App\Model\ModelEntityFactory;
use App\Model\ModelFactory;
use App\Service\Collect\CollectSubjectCore;

class AutoController extends Controller
{

    //自动创建数据库表字段对应类
    public function createAuto()
    {
        //科目表
        //ModelEntityFactory::create("Subject", "subject");
        //ModelFactory::create("Subject", "subject", "id");

        //配置表
        //ModelEntityFactory::create("Setting", "setting");
        //ModelFactory::create("Setting", "setting", "type");

        //采集数据
        //CollectSubjectCore::autoCollect();

        return '成功';
    }
}

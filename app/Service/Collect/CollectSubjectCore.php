<?php
/**
 * Created by: PhpStorm.
 * User: yj
 * Date: 2020/4/6
 * Time: 14:26
 */

namespace App\Service\Collect;

//采集科目数据接口
use App\Common\Http;
use App\Common\Str;
use App\Common\Time;
use App\Common\Util;
use App\Model\Enum\Setting\EnumSetting;
use App\Model\Setting\Setting_entity;
use App\Model\Setting\Setting_modelSp;
use App\Model\Subject\Subject_modelSp;

//采集科目数据
class CollectSubjectCore
{
    //自动抓取数据
    public static function autoCollect()
    {
        while (true)
        {
            $pageEn = self::getCollectSet();
            $pageAll = (int)$pageEn->val1;       //总页数
            $pageNow = (int)$pageEn->val2;       //当前查询到几几页
            if ($pageNow >= $pageAll) break;     //已经抓取完了
            self::collectPage($pageEn);          //抓取一页数据
        }
    }

    //开始采集一页数据
    private static function collectPage(Setting_entity $pageEn)
    {
        $pageNow = (int)$pageEn->val2;       //当前查询到几几页
        $data = self::getHttpData(++$pageNow);//请求数据
        foreach ($data->items as $val)
        {
            if (!self::saveOne($val)) return;//保存一条数据
        }
        $pageEn->val2 = $pageNow;            //新的页数
        Setting_modelSp::updEntity($pageEn); //保存新页数
    }

    //取配置信息
    private static function getCollectSet(): Setting_entity
    {
        return Setting_modelSp::getByType(EnumSetting::type_collectSubject);
    }

    //请求数据
    private static function getHttpData($page)
    {
        $url  = "https://wjt-subject-tool-api.sdp.101.com/v1/actions/manage?_={0}&page={1}&page_size=100&school_name=&subject_name=";
        $time = Time::getMicroTimeNow2();       //毫秒时间搓
        $url  = Str::format($url, $time, $page); //请求连接
        return Http::getJson($url);            //发送get请求，返回json
    }

    //保存一条数据
    private static function saveOne($val): bool
    {
        $en = CollectTran::getEntityByJson($val);
        //var_dump($en);
        $enOld = Subject_modelSp::getByCollectId($en->collectId);//旧数据

        //已经采集过，且更新时间一样，不用再采集
        if (!Util::isEmpty($enOld) && $enOld->updTime >= $en->updTime) return true;

        if (Util::isEmpty($enOld))
        {
            return Subject_modelSp::insEntity($en);        //添加
        }
        else
        {
            $en->id = $enOld->id;                          //重新设置id
            return Subject_modelSp::updEntity($en);        //修改
        }
    }
}

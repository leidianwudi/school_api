<?php
/**
 * Created by: PhpStorm.
 * User: yj
 * Date: 2019/12/9
 * Time: 16:38
 */

namespace App\InterfaceEntity\OutputEntity;

//分页数据 和数据库查询的 paginate 方法查询后返回的数据对应
class PageData
{
    public $from;           //从第几条数据开开始
    public $to;             //到第几条数据结束
    public $current_page;   //当前第几页
    public $last_page;      //最后一页是多少页
    public $per_page;       //每页多少条记录数
    public $total;          //总记录数
    public $data = null;    //分页的具体数据，是数组。每页数据返回null

    //从数据库的page查询中构造出数据
    public static function createByPage($page): PageData
    {
        $en = new PageData();
        //$en->from           = $page->from();
        //$en->to             = $page["to"];
        $en->current_page   = $page->currentPage();
        $en->last_page      = $page->lastPage();
        $en->per_page       = $page->perPage();
        $en->total          = $page->total();
        return $en;
    }
}

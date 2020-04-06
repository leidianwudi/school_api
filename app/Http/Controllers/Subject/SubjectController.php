<?php
/**
 * Created by: PhpStorm.
 * User: yj
 * Date: 2020/4/7
 * Time: 2:42
 */

namespace App\Http\Controllers\Subject;


use App\Common\Util;
use App\InterfaceEntity\InputEntity\InPage;
use App\InterfaceEntity\InputEntity\Subject\InGetSubject;
use App\Service\Subject\SchoolCore;
use App\Service\Subject\SubjectCore;
use Symfony\Component\HttpFoundation\Request;

//课程
class SubjectController
{
    /**
     * @api {post} subject/getSubject 查询可选的专业课程
     * @apiName getSubject
     * @apiDescription 查询可选的专业课程
     * @apiGroup subject
     * @apiParam {string}  [school]     学校名称
     * @apiParam {string}  [profession] 学校名称
     * @apiParam {string}  [subject1]   学校名称
     * @apiParam {string}  [subject2]   学校名称
     * @apiParam {int}  page            第几页
     * @apiParam {int} count            每页记录数
     * @apiSuccessExample {json}        返回数据
    {
    "id": 981,
    "collectId": 207369,
    "school": "北京交通大学(威海校区)",       //学校
    "profession": "通信工程(中外合作办学)",   //招生专业(类)
    "professionSub": "",                     //包含专业
    "subject1": "仅物理",                    //首选科目要求
    "subject2": "不提再选科目要求",           //再选科目要求
    "updTime": "2019-08-05 12:11:52"
    }
     */
    public function getSubject(Request $rep)
    {
        $in = new InGetSubject();                   //创建请求对应数据类型
        Util::getInputFromPost($rep, $in);         //取数据
        return Response()->json(SubjectCore::getSubject($in));
    }
}

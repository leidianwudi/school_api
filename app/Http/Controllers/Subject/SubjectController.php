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
     * @apiParam {string}  [profession] 专业名称
     * @apiParam {string}  [subject1]   首选科目
     * @apiParam {string}  [subject2]   再选科目
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

    /**
     * @api {post} subject/getProfessionPer 按首选和再选统计百分比
     * @apiName getProfessionPer
     * @apiDescription 按首选和再选科目统计符合条件的科目大类及所占百分比
     * @apiGroup subject
     * @apiParam {string}  [subject1]   首选科目
     * @apiParam {string}  [subject2]   再选科目
     * @apiParam {int}  page            第几页
     * @apiParam {int} count            每页记录数
     * @apiSuccessExample {json}        返回数据
    {
    "pro": "计算机科学与技术",  //专业
    "sum": 401,                //专业类条数
    "sumAll": 401              //该专业类总条数
     注意前端自己再加个字段 per = sum * 100 / sumAll 专业类占总专业类百分比，保留2位小数
    }
     */
    public function getProfessionPer(Request $rep)
    {
        $in = new InGetSubject();                   //创建请求对应数据类型
        Util::getInputFromPost($rep, $in);         //取数据
        return Response()->json(SubjectCore::getProfessionPer($in));
    }

    /**
     * @api {post} subject/getSubjectPer 按学校或专业统计百分比
     * @apiName getSubjectPer
     * @apiDescription 按学校或专业统计符合条件的首选再选科目及其百分比
     * @apiGroup subject
     * @apiParam {string}  [school]       学校
     * @apiParam {string}  [profession]   专业
     * @apiParam {int}  page              第几页
     * @apiParam {int} count              每页记录数
     * @apiSuccessExample {json}          返回数据
    {
    "subject1": "物理或历史均可",      //首选科目
    "subject2": "不提再选科目要求",    //再选科目
    "sum": 11693,                     //符合条件专业
    "per": "44.5261"                  //所占总专业百分比，保留4位小数
    }
     */
    public function getSubjectPer(Request $rep)
    {
        $in = new InGetSubject();                   //创建请求对应数据类型
        Util::getInputFromPost($rep, $in);         //取数据
        return Response()->json(SubjectCore::getSubjectPer($in));
    }
}

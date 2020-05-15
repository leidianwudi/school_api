<?php
/**
 * Created by: PhpStorm.
 * User: yj
 * Date: 2020/4/7
 * Time: 0:30
 */

namespace App\Http\Controllers\School;


use App\Common\Str;
use App\Common\Util;
use App\Http\Controllers\Controller;
use App\InterfaceEntity\InputEntity\InPage;
use App\InterfaceEntity\InputEntity\School\InGetSchool;
use App\Service\Subject\SchoolCore;
use Symfony\Component\HttpFoundation\Request;

//学校
class SchoolController extends Controller
{
    /**
     * @api {post} school/getSchool 查询学校
     * @apiName getSchool
     * @apiDescription 根据名称和页数查询学校
     * @apiGroup school
     * @apiParam {string} [school]      学校名称
     * @apiParam {string} [profession]  专业名称
     * @apiParam {int}  page            第几页
     * @apiParam {int} count            每页记录数
     * @apiSuccessExample {json} 返回数据
    {
    "school": "中国地质大学(北京)"  //学校名称
    }
     */
    public function getSchool(Request $rep)
    {
        $in = new InGetSchool();                   //创建请求对应数据类型
        Util::getInputFromPost($rep, $in);         //取数据
        return Response()->json(SchoolCore::getSchool($in));
    }

    /**
     * @api {post} school/getMenke 查询学类
     * @apiName getMenke
     * @apiDescription 根据名称和页数查询学校
     * @apiGroup school
     * @apiParam {string} [school]      学校名称
     * @apiParam {string} [menke]       学类
     * @apiParam {string} [profession]  专业名称
     * @apiParam {int}  page            第几页
     * @apiParam {int} count            每页记录数
     * @apiSuccessExample {json} 返回数据
    {
    "menke": ""  //学类
    }
     */
    public function getMenke(Request $rep)
    {
        $in = new InGetSchool();                   //创建请求对应数据类型
        Util::getInputFromPost($rep, $in);         //取数据
        return Response()->json(SchoolCore::getMenke($in));
    }

    /**
     * @api {post} school/getProfession 查询专业
     * @apiName getProfession
     * @apiDescription 根据名称和页数查询专业
     * @apiGroup school
     * @apiParam {string} [school]      学校名称
     * @apiParam {string} [profession]  专业名称
     * @apiParam {int}  page            第几页
     * @apiParam {int} count            每页记录数
     * @apiSuccessExample {json}        返回数据
    {
    "profession": "世界史"  //专业名称
    }
     */
    public function getProfession(Request $rep)
    {
        $in = new InGetSchool();                   //创建请求对应数据类型
        Util::getInputFromPost($rep, $in);         //取数据
        return Response()->json(SchoolCore::getProfession($in));
    }

    /**
     * @api {post} school/getSubject1 查询首选科目
     * @apiName getSubject1
     * @apiDescription 查询首选科目
     * @apiGroup school
     * @apiParam {int}  page            第几页
     * @apiParam {int} count            每页记录数
     * @apiSuccessExample {json}        返回数据
    {
    "subject1":   //首选科目
    }
     */
    public function getSubject1(Request $rep)
    {
        $in = new InPage();                   //创建请求对应数据类型
        Util::getInputFromPost($rep, $in);         //取数据
        return Response()->json(SchoolCore::getSubject1($in));
    }

    /**
     * @api {post} school/getSubject2 查询再选科目
     * @apiName getSubject2
     * @apiDescription 查询再选科目
     * @apiGroup school
     * @apiParam {int}  page            第几页
     * @apiParam {int} count            每页记录数
     * @apiSuccessExample {json}        返回数据
    {
    "subject2":   //再选科目
    }
     */
    public function getSubject2(Request $rep)
    {
        $in = new InPage();                   //创建请求对应数据类型
        Util::getInputFromPost($rep, $in);         //取数据
        return Response()->json(SchoolCore::getSubject2($in));
    }
}

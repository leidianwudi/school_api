<?php
/**
 * Created by PhpStorm.
 * User: FBI
 * Date: 2018/6/27
 * Time: 21:46
 */

namespace App\Http\Controllers\External;

use App\Common\Util;
use App\Http\Controllers\Controller;
use App\ServiceCore\External\ValidateCodeCore;
use Symfony\Component\HttpFoundation\Request;

//请求验证码图片
class ValidateCodeController extends Controller
{
    //请求验证码图片
    public function getValidateCodeImg(Request $rep)
    {
        $ip = Util::getClientIp();//$rep->getClientIp(); //ip地址
        return ValidateCodeCore::doCodeImg($ip);
    }

}

<?php
/**
 * Created by PhpStorm.
 * User: FBI
 * Date: 2018/5/21
 * Time: 17:56
 */

namespace App\Http\Middleware;

use App\Common\Arr;
use App\Common\Log;
use App\Common\Str;
use App\Common\Util;
use Closure;
use Symfony\Component\HttpFoundation\Request;

//验证玩家登陆token
class CheckToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(\Illuminate\Http\Request $request, Closure $next)
    {
        //写请求日志
        if (!self::isLogExcept($request))
        {
            $rulFull = $request->fullUrl();//全路径
            //LogTool::info("往 $rulFull 发 $request->getMethod() 请求 数据:$request->getContent()");
        }

        /*
        $token = $request->header('token');
        $userId = UserCore::getUserIdByToken($token);//查找用户

        //不为空就设置玩家为在线状态
        if (!Util::empty($userId)) UserLineCore::setUserOnLine($userId);

        //需要验证token，且userid为空。就是token验证不通过
        if (!self::isTokenExcept($request) && Util::empty($userId))
        {
            $out = new ResultData();
            $out->setError(ErrorMsg::tokenError);//没有找到返回token错误
            return Response()->json($out);
        }
        */
        return $next($request);
    }

    //在请求里根据token取用户id
    public static function getUserIdByToken(Request $request)
    {
        $token = $request->header('token');
        $userId = UserCore::getUserIdByToken($token);//查找用户
        return $userId;
    }

    //在请求里根据token取用户名
    public static function getUserNameByToken(Request $request): ?string
    {
        $userId = self::getUserIdByToken($request);
        $en = RedisUsers::getUserEntityById($userId);
        if (Util::empty($en)) return null;
        return $en->name;
    }

    //排除验证token的请求
    protected $exceptToken = [
        "getConfigSystem",          //系统设置
        "getValidateCodeImg",       //请求验证码图片
        "userRegister",             //注册
        "userLogin",                //玩家登陆
        "adminLogin",               //管理员登陆
        "loginOut",                 //玩家登出
        "createAuto",               //自动代码生成器
        "isRegMustInvite",          //注册是否需要填邀请码
        "uploadImg",                //上传图片取消token验证

        //聊天室。最后要去掉
        "loginChat",
        "getOpenRoom",
        "getUserRoom",
        "addRoom",
        "inGroup",
        "getMessage",
        "getMessageCount",
        "outGroup",
        "sendToRoom",
        "getFriendByUserId",
        "addFriend",
        "delFriend",
        "sendToFriend",
        "setMessageRead",
        "sendRedPack",
        "robRed",
        "robRedLog",
        "getRoomUser",
        "addRoomUser",
        "delRoomUser",
    ];

    //排除写日志的请求
    protected $exceptLog = [
    ];

    //是否要跳过写log
    private function isLogExcept(\Illuminate\Http\Request $request) : bool
    {
        $rulFull = $request->fullUrl();//全路径

        foreach ($this->exceptLog as $url)
        {
            if (Str::contains($rulFull, $url)) return true;
        }
        return false;
    }

    //是否要跳过token验证
    private function isTokenExcept(\Illuminate\Http\Request $request) : bool
    {
        //OPTIONS 请求不验证token
        if ($request->getMethod() == "OPTIONS") return true;
        if ($request->getMethod() == "GET")     return true;

        // 是否开启token验证功能
        $CHECK_TOKEN_ON =  env('CHECK_TOKEN_ON', true);
        if (!$CHECK_TOKEN_ON)
        {
            return true;
        }

        $rulFull = $request->fullUrl();//全路径
        $path = Str::afterLast($rulFull, "/");

        if (Arr::isHaveVal($this->exceptToken, $path))
            return true;//存在就跳过

        return false;//不跳过
    }

}

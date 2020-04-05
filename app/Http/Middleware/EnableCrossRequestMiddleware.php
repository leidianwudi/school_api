<?php

namespace App\Http\Middleware;

use App\Common\Num;
use App\Common\Log;
use App\Common\Time;
use Closure;

class EnableCrossRequestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $beg = Time::getMicroTimeNow();

        $response = $next($request);
        $response->header('Access-Control-Allow-Origin', '*');
        //$response->header('Access-Control-Allow-Headers', 'Origin, Content-Type, Cookie, Accept, multipart/form-data, application/json, token');
        $response->header('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept, token');
        $response->header('Access-Control-Allow-Methods', 'GET, POST, PATCH, PUT, OPTIONS');
        // $response->header('Access-Control-Allow-Credentials', 'true');

        $end = Time::getMicroTimeNow();
        $spec = Num::sub($end, $beg);
        if ($spec > 1000.0)  //大于1秒打印日志
        {
            $rulFull = $request->fullUrl();//全路径
            Log::error("服务执行时间太长:" . $spec . "秒 往" . $rulFull . " 发" . $request->getMethod() . "请求 数据:" . $request->getContent());
        }

        return $response;
    }
}

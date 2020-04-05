<?php
/**
 * Created by PhpStorm.
 * User: FBI
 * Date: 2018/8/17
 * Time: 18:07
 */


namespace App\Common;

use Illuminate\Support\Pluralizer;
use Illuminate\Support\Traits\Macroable;

//http请求数据
class Http {

    //发送get请求，返回json
    public static function getJson($url)
    {

        $body = self::getHttpBody($url);
        return json_decode($body);
    }

    //发送get请求，返回字符串

    //发送get请求，返回json
    public static function getJsonAndRawRsp($url, & $rawRsp)
    {
        $rawRsp = self::getHttpBody($url);
        return json_decode($rawRsp);
    }

    //发送get请求，返回数组
    public static function getJsonArr($url)
    {
        $body = self::getHttpBody($url);
        return json_decode($body, true);
    }

    //发送get请求，返回 主数据
    public static function getHttpBody($url)
    {
        $res = Http::getHttp($url);
        list($header, $body) = explode("\r\n\r\n", $res, 2);
        return $body;
    }

    //发送get请求，返回所有数据
    public static function getHttp($url)
    {
        //初始化
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $url);
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, 1);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        //执行命令
        $res = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);
        return $res;
    }

    //发送get请求，https
    public static function getHttps($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);  // 跳过检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);  // 跳过检查
        $tmpInfo = curl_exec($curl);
        //dd($tmpInfo);
        curl_close($curl);
        return $tmpInfo;   //返回json对象
    }

    //发送post的表单数据
    public static function post($url, $dataString) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_URL, $url);
        //启用时会发送一个常规的POST请求，类型为：application/x-www-form-urlencoded，就像表单提交的一样。
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $dataString);
        $res = curl_exec($curl);
        if (curl_getinfo($curl, CURLINFO_HTTP_CODE) != '200') {
            curl_close($curl);
            return null;
        }
        curl_close($curl);
        list($header, $body) = explode("\r\n\r\n", $res, 2);
        return ['header' => $header, 'body' => $body];
    }

    /**
     * POST请求https接口返回内容
     * @param  string $url [请求的URL地址]
     * @param  string $post [请求的参数]
     * @return  string
     */
    public static function posts($url, $post)
    {
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE); // 对认证证书来源的检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE); // 从证书中检查SSL加密算法是否存在
        /*
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        */
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
        curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post); // Post提交的数据包
        curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
        curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回

        $res = curl_exec($curl); // 执行操作
        if (curl_errno($curl)) {
            echo 'Errno'.curl_error($curl);//捕抓异常
        }
        curl_close($curl); // 关闭CURL会话
        return $res; // 返回数据，json格式

    }

    //发送post的json数据
    public static function postJson($url, $dataString, $token='') {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $dataString);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json; charset=utf-8",
            "Content-Length: " . strlen($dataString),
            "Authorization: " . $token
        ]);
        $res = curl_exec($curl);
        curl_close($curl);
        return $res;
    }

    //发送post的form数据
    public static function postFrom($url, $dataString, $token='') {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $dataString);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            "Authorization: " . $token
        ]);
        $res = curl_exec($curl);
        curl_close($curl);
        return $res;
    }

    //对url进行编码
    public static function urlEncode($url)
    {
        return urlencode($url);
    }

    //对url进行解码
    public static function urlDecode($url)
    {
        return urldecode($url);
    }

    //发送get请求，返回所有数据
    public static function getHead($url, $data, $headArr)
    {
        //初始化
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $url . $data);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headArr);
        //执行命令
        $res = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);
        return $res;
    }

    //发送post数据，指定head信息
    public static function postHead($url, $dataString, $headArr) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $dataString);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headArr);
        $res = curl_exec($curl);
        curl_close($curl);
        return $res;
    }

}

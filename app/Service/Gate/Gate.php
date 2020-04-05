<?php
/**
 * Created by PhpStorm.
 * User: yj
 * Date: 2019/7/22
 * Time: 1:42
 */

namespace App\Service\Gate;
//require_once(dirname(__FILE__).'/GatewayClient/Gateway.php');
require_once('GatewayClient/Gateway.php');

use App\Common\Log;
use App\Common\Str;
use App\Common\Tran;
use App\Common\Util;
use App\Redis\Chat\Group\RedisChatGroupUser;
use App\Redis\Chat\Msg\RedisUserClient;
use GatewayClient\Gateway;

//网关注册机器ip和端口
Gateway::$registerAddress = env('GATE_IP_PORT', "127.0.0.1:1238");

//主动推送功能类
class Gate
{
    //保存用户账户和网关id
    public static function bindAccount($account, $clientId)
    {
        //保存用户id和网络连接id
        RedisUserClient::setUserClient($account, $clientId);
    }

    //删除用户的网络连接id
    public static function unBindAccount($userId)
    {
        //保存用户id和网络连接id
        RedisUserClient::delUserClient($userId);
    }

    //用户进入群
    public static function groupIn($account, $roomIn)
    {
        $clientId = RedisUserClient::getUserClient($account);    //连接id
        if (Util::empty($clientId)) return;               //没有连上服务器，返回

        //self::outGroup($account);//先离开房间
        self::joinGroup($clientId, $roomIn);        //添加进组
        RedisChatGroupUser::groupUserIn($account, $roomIn);//把进入的房间号保存到redis
    }

    //用户离开群
    public static function groupOut($account)
    {
        $clientId = RedisUserClient::getUserClient($account);    //连接id
        if (Util::empty($clientId)) return;                     //没有连上服务器，返回
        $roomIdOld = RedisChatGroupUser::getInGroupIdByUser($account);   //已经在哪个房间

        //var_dump("旧房间".$roomIdOld."离开");
        //有在旧房间就离开
        if (!Util::empty($roomIdOld))
            self::leaveGroup($clientId, $roomIdOld);//离开组
    }

    /**向所有人发消息
     * @param  $message: 消息
     * @param array|null $userIdS: 客户端 id 数组
     * @param array|null $excludeUserIdS:不给这些client_id发
     * @return bool :成功
     */
    public static function sendToAll($message, array $userIdS = null,
        array $excludeUserIdS = null): bool
    {
        try
        {
            $msg = Tran::obj2Json($message);//类转为字符串
            $client_id_array = null;
            $exclude_client_id = null;

            //查找要发送的id数组
            if (!Util::isEmpty($userIdS))
            {
                foreach ($userIdS as $val)
                {
                    $clientId = RedisUserClient::getUserClient($val);
                    if (!Util::isEmpty($clientId)) $client_id_array[] = $clientId;
                }
            }

            //查找要过滤的id数组
            if (!Util::isEmpty($excludeUserIdS))
            {
                foreach ($excludeUserIdS as $val)
                {
                    $clientId = RedisUserClient::getUserClient($val);
                    if (!Util::isEmpty($clientId)) $exclude_client_id[] = $clientId;
                }
            }
            Gateway::sendToAll($msg."\r\n", $client_id_array, $exclude_client_id);
        }
        catch (\Exception $e)
        {
            Log::errorTrace($e);
        }
        return true;
    }

    /**向某个房间的人发消息
     * @param string $groupId:房间id
     * @param $message:发送信息
     * @return bool :成功
     */
    public static function sendToGroup(string $groupId, $message)
    {
        if (Util::empty($message)) return true;
        $msg = Tran::obj2Json($message);//类转为字符串
        Gateway::sendToGroup($groupId, $msg);
        return true;
    }

    /**向某个人发消息
     * @param string $account:玩家id
     * @param $message:消息内容
     * @return bool :成功
     */
    public static function sendToAccount(string $account, $message)
    {
        $clientId = RedisUserClient::getUserClient($account);
        if (Util::isEmpty($clientId))
        {
            Log::error("sendToClient error $account not clientId");
            return true;
        }
        $msg = Tran::obj2Json($message);//类转为字符串
        Gateway::sendToClient($clientId, $msg);
        //Log::error("sendToClient $clientId" . Tran::obj2Json($msg));
        return true;
    }

    /** 加入某个组
     * @param string $clientId:客户id
     * @param int|string $group:用户组
     */
    private static function joinGroup(string $clientId, $group)
    {
        Gateway::joinGroup($clientId, $group);
    }

    /** 从某个组离开
     * @param string $clientId:客户id
     * @param $group:用户组id
     */
    private static function leaveGroup(string $clientId, $group)
    {
        Gateway::leaveGroup($clientId, $group);
    }

}

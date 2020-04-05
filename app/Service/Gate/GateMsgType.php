<?php
/**
 * Created by PhpStorm.
 * User: yj
 * Date: 2019/7/24
 * Time: 17:22
 */

namespace App\Service\Gate;

//消息类型
use App\Model\Enum\Chat\Friend\EnumChatFriendMsg;
use App\Model\Enum\Chat\Group\EnumChatGroupMsg;

//消息转换
class GateMsgType
{
    const app_permission        = 0;       //app权限
    const friend_chat           = 10;      //好友聊天消息
    const friend_red            = 11;      //好友红包消息
    const friend_last           = 99;      //好友最后未读消息
    const group_chat            = 100;     //用户群聊天消息
    const group_red             = 101;     //用户群聊天消息
    const group_system          = 102;     //群系统消息
    const group_last            = 199;     //群聊最后未读消息

    //从用户聊天消息类型，返回ws发送的数据类
    public static function getTypeFromFriendMsg(int $type): int
    {
        switch ($type)
        {
            case EnumChatFriendMsg::type_chat: return GateMsgType::friend_chat;
            case EnumChatFriendMsg::type_red:  return GateMsgType::friend_red;
        }
        return -1;//错误
    }

    //从用户聊天消息类型，返回ws发送的数据类
    public static function getTypeFromGroupMsg(int $type): int
    {
        switch ($type)
        {
            case EnumChatGroupMsg::type_chat: return GateMsgType::group_chat;
            case EnumChatGroupMsg::type_red:  return GateMsgType::group_red;
            case EnumChatGroupMsg::type_system:  return GateMsgType::group_system;
        }
        return -1;//错误
    }
}

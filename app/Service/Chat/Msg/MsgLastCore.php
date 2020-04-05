<?php
/**
 * Created by: PhpStorm.
 * User: yj
 * Date: 2019/12/8
 * Time: 22:54
 */

namespace App\Service\Chat\Msg;

use App\Common\Str;
use App\Common\Time;
use App\Common\Util;
use App\InterfaceEntity\InputEntity\Chat\Friend\InChatFriendApply;
use App\InterfaceEntity\InputEntity\Chat\Group\InFriendJoinGroup;
use App\InterfaceEntity\InputEntity\Chat\Group\InGroup;
use App\InterfaceEntity\InputEntity\Chat\Msg\InMsgLast;
use App\InterfaceEntity\OutputEntity\ResultData;
use App\Model\Chat\Friend\Chat_friend_msg_entity;
use App\Model\Chat\Friend\Chat_friend_msg_modelSp;
use App\Model\Chat\Friend\Chat_friend_user_entity;
use App\Model\Chat\Friend\Chat_friend_user_modelSp;
use App\Model\Chat\Group\Chat_group_entity;
use App\Model\Chat\Group\Chat_group_msg_entity;
use App\Model\Chat\Group\Chat_group_msg_modelSp;
use App\Model\Chat\Group\Chat_group_user_entity;
use App\Model\Chat\Msg\Chat_msg_last_entity;
use App\Model\Chat\Msg\Chat_msg_last_modelSp;
use App\Model\Enum\Chat\Msg\EnumChatMsgLast;
use App\Model\User\User_entity;
use App\Redis\Chat\Group\RedisChatGroup;
use App\Redis\Chat\Group\RedisChatGroupUser;

//最后一条聊天数据操作
class MsgLastCore
{
    //取用户的好友和群的最后一条聊天信息
    public static function getLastMsgByAccount(InMsgLast $in): ResultData
    {
        $res = new ResultData();
        $groupIdArr = RedisChatGroupUser::getGroupIdArrByUser($in->account);//玩家群id数组
        $arr = Chat_msg_last_modelSp::getMsgByAccount($in->account,
            $groupIdArr, $in->updTime, $in->page, $in->count);
        return $res->setData($arr);
    }

    //加好友成功时，最后一条聊天记录表，添加好友聊天的第一条记录(同时会把信息写入聊天表)
    public static function addMsgLastForFriend(Chat_friend_user_entity $user, Chat_friend_user_entity $friend): bool
    {
        //好友聊天表，添加打招呼记录
        $msg = InChatFriendApply::getMsg($user);
        if (!Chat_friend_msg_modelSp::insEntity($msg)) return false;

        //最后一条聊天记录表，添加打招呼记录
        $msgLast = InChatFriendApply::getMsgLast($user, $friend, $msg);
        return Chat_msg_last_modelSp::insEntity($msgLast);
    }

    //好友聊天时，最后一条聊天记录表，修改好友聊天的最后一条记录(同时会把信息写入聊天表)
    public static function updMsgLastForFriend(Chat_friend_msg_entity $msg): bool
    {
        //好友聊天表，添加聊天记录
        if (!Chat_friend_msg_modelSp::insEntity($msg, true)) return false;

        //最后一条聊天记录表，修改聊天记录
        return Chat_msg_last_modelSp::updMsgForFriend($msg);
    }

    //创建群时，最后一条聊天记录表，添加群聊天的第一条记录(同时会把信息写入群聊天表)
    public static function addMsgLastForGroup(User_entity $user, Chat_group_entity $group): bool
    {
        //群聊天表，添加创建群成功系统提示消息
        $msg = InGroup::getMsg($user, $group);
        if (!Chat_group_msg_modelSp::insEntity($msg)) return false;

        //最后一条聊天记录表，添加创建群成功系统提示消息
        $msgLast = InGroup::getMsgLast($group, $msg);
        return Chat_msg_last_modelSp::insEntity($msgLast);
    }

    //拉用户入群时，最后一条聊天记录表。修改群聊天的最后一条记录(同时会把信息写入群聊天表)
    public static function updMsgLastForGroupApply(User_entity $userHost,
        User_entity $user, $groupId): bool
    {
        //群聊天表，添加拉好友入群系统提示消息
        $msg = InFriendJoinGroup::getMsgApply($userHost, $user, $groupId);
        if (!Chat_group_msg_modelSp::insEntity($msg)) return false;

        //最后一条聊天记录表，修改群聊天记录
        return Chat_msg_last_modelSp::updMsgForGroup($msg);
    }

    //用户退出群时，最后一条聊天记录表。修改群聊天的最后一条记录(同时会把信息写入群聊天表)  $msg:退出提示
    public static function updMsgLastForGroupOut(Chat_group_user_entity $user, $OutTip): bool
    {
        //群聊天表，退出群系统提示消息
        $msg = InFriendJoinGroup::getMsgOut($user, $OutTip);
        if (!Chat_group_msg_modelSp::insEntity($msg)) return false;

        //最后一条聊天记录表，修改群聊天记录
        return Chat_msg_last_modelSp::updMsgForGroup($msg);
    }

    //群聊天时，最后一条聊天记录表，修改聊天的最后一条记录(同时会把信息写入聊天表)
    public static function updMsgLastForGroup(Chat_group_msg_entity $msg): bool
    {
        //群聊表，添加聊天记录
        if (!Chat_group_msg_modelSp::insEntity($msg, true)) return false;

        //最后一条聊天记录表，修改聊天记录
        return Chat_msg_last_modelSp::updMsgForGroup($msg);
    }
}

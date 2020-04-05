<?php
/**
 * Created by: PhpStorm.
 * User: yj
 * Date: 2019/12/3
 * Time: 17:07
 */

namespace App\Service\Chat\Group;


use App\Common\Util;
use App\InterfaceEntity\InputEntity\Chat\Friend\InChatFriendMsg;
use App\InterfaceEntity\InputEntity\Chat\Group\InChatGroupMsg;
use App\InterfaceEntity\OutputEntity\ErrorMsg;
use App\InterfaceEntity\OutputEntity\ResultData;
use App\Model\Chat\Friend\Chat_friend_msg_modelSp;
use App\Model\Chat\Group\Chat_group_msg_modelSp;
use App\Redis\Chat\Group\RedisChatGroup;
use App\Service\Chat\Friend\PowerChatFriend;
use App\Service\Chat\Msg\MsgCore;

//群聊天数据
class ChatGroupMsgCore
{
    //查询群聊天数据
    public static function getGroupMsg(InChatGroupMsg $in): ResultData
    {
        $res = new ResultData();

        //判断群是否已经被解散
        $group = RedisChatGroup::getEntityById($in->groupId);
        if (Util::isEmpty($group)) return $res->setError(ErrorMsg::groupIsDissolve);

        return $res->setData(Chat_group_msg_modelSp::getGroupMsg($in));
    }

    //在群里发聊天记录
    public static function sendMsgToGroup(InChatGroupMsg $in): ResultData
    {
        $res = new ResultData();

        $en = $in->getAddEntity();//取数据库中对应数据
        if (Util::isEmpty($en)) return $res->setError(ErrorMsg::postDataError);//请求数据错误

        if (!PowerChatGroup::isCanSendMsg($en->account, $en->groupId))
            return $res->setError(ErrorMsg::canNotSendMsg);    //没有发言权限

        if (MsgCore::sendToGroup($en)) $res->setData($en);//发送成功返回数据
        return $res;           //返回数据
    }
}

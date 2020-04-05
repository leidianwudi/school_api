<?php
/**
 * Created by: PhpStorm.
 * User: yj
 * Date: 2019/12/5
 * Time: 10:23
 */

namespace App\Service\Chat\Msg;


use App\InterfaceEntity\InputEntity\Gate\InLoginGate;
use App\InterfaceEntity\OutputEntity\Chat\Msg\OutMsgLast;
use App\InterfaceEntity\OutputEntity\ResultData;
use App\Model\Chat\Friend\Chat_friend_msg_entity;
use App\Model\Chat\Friend\Chat_friend_msg_modelSp;
use App\Model\Chat\Group\Chat_group_msg_entity;
use App\Redis\Chat\Msg\RedisMsgLast;
use App\Service\External\StorageCore;
use App\Service\Gate\Gate;
use App\Service\Gate\GateMsg;
use App\Service\Gate\GateMsgType;
use App\Service\Gate\MsgData\MsgFriend;
use App\Service\Gate\MsgData\MsgGroup;

//发送消息操作
class MsgCore
{
    //发送信息给好友  $isIns:消息是否要同时添加到数据库
    public static function sendToFriend(Chat_friend_msg_entity $en, bool $isIns = true):bool
    {
        //添加信息到数据库
        if ($isIns)
        {
            //修改最后一条聊天记录数据
            if (!MsgLastCore::updMsgLastForFriend($en)) return false;
            //通过网关发送最后一条聊天记录index的信息
            self::sendFriendMsgIndex($en);
        }

        //发送信息
        $data = MsgFriend::fromChatMessage($en);  //好友消息
        $msg = GateMsg::getMsg(GateMsgType::getTypeFromFriendMsg($en->type), $data);
        Gate::sendToAccount($en->account, $msg);    //通过网关发送消息给自己
        Gate::sendToAccount($en->toAccount, $msg);  //通过网关发送消息给好友
        return true;
    }

    //发送信息到群里  $isIns:消息是否要同时添加到数据库
    public static function sendToGroup(Chat_group_msg_entity $en, bool $isIns = true):bool
    {
        //添加信息到数据库
        if ($isIns)
        {
            //修改最后一条聊天记录数据
            if (!MsgLastCore::updMsgLastForGroup($en)) return false;
            //通过网关发送最后一条聊天记录index的信息
            self::sendGroupMsgIndex($en);
        }

        $en->head = StorageCore::getHeadUrl($en->head);//重新赋值头像
        //发送信息
        $data = MsgGroup::fromChatMessage($en);  //群聊消息
        $msg = GateMsg::getMsg(GateMsgType::getTypeFromGroupMsg($en->type), $data);
        Gate::sendToGroup($en->groupId, $msg);  //通过网关发送消息给群内用户
        return true;
    }

    //发送信息到未读消息数量信息
    private static function sendFriendMsgIndex(Chat_friend_msg_entity $en)
    {
        $msgEn = RedisMsgLast::getFriendMsgLast($en->account, $en->toAccount);//记录
        $msgIndex = RedisMsgLast::getFriendIndexAfterAdd_once($en->account, $en->toAccount);//第几条聊天记录
        $msgEn->sendAccount = $en->account;     //发送者用户名
        $msgEn->msg         = $en->msg;         //发送内容
        $msgEn->msgIndex    = $msgIndex;        //第几条聊天信息
        $msgEn->updTime     = $en->addTime;     //发送时间

        $msgOutSend = OutMsgLast::getFromEntity($msgEn, $en->account);//发送人接受的数据
        $msgSend = GateMsg::getMsg(GateMsgType::friend_last, $msgOutSend);
        Gate::sendToAccount($en->account, $msgSend);    //通过网关发送消息给自己

        $msgOutTo = OutMsgLast::getFromEntity($msgEn, $en->toAccount);//接收人接受的数据
        $msgTo = GateMsg::getMsg(GateMsgType::friend_last, $msgOutTo);
        Gate::sendToAccount($en->toAccount, $msgTo);  //通过网关发送消息给好友
    }

    //发送信息到未读消息数量信息
    private static function sendGroupMsgIndex(Chat_group_msg_entity $en)
    {
        $msgEn = RedisMsgLast::getGroupMsgLast($en->groupId);//记录
        $msgIndex = RedisMsgLast::getGroupIndexAfterAdd_once($en->groupId);//第几条聊天记录
        $msgEn->sendAccount = $en->account;     //发送者用户名
        $msgEn->msg         = $en->msg;         //发送内容
        $msgEn->msgIndex    = $msgIndex;        //第几条聊天信息
        $msgEn->updTime     = $en->addTime;     //发送时间

        $msgOut =  OutMsgLast::getFromEntity($msgEn);//前端需要的数据
        $msg = GateMsg::getMsg(GateMsgType::group_last, $msgOut);
        Gate::sendToGroup($en->groupId, $msg);    //通过网关发送消息给群内用户
    }
}

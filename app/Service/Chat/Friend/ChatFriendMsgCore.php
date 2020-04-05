<?php
/**
 * Created by: PhpStorm.
 * User: yj
 * Date: 2019/12/3
 * Time: 17:04
 */

namespace App\Service\Chat\Friend;


use App\Common\Tran;
use App\Common\Util;
use App\InterfaceEntity\InputEntity\Chat\Friend\InChatFriendMsg;
use App\InterfaceEntity\InputEntity\Chat\Msg\InDelMsg;
use App\InterfaceEntity\InputEntity\InDelByArr;
use App\InterfaceEntity\InputEntity\InDelById;
use App\InterfaceEntity\OutputEntity\ErrorMsg;
use App\InterfaceEntity\OutputEntity\ResultData;
use App\Model\Chat\Friend\Chat_friend_msg_modelSp;
use App\Model\Chat\Friend\Chat_friend_user_modelSp;
use App\Service\Chat\Msg\MsgCore;

//好友聊天信息
class ChatFriendMsgCore
{
    //向好友发送聊天信息
    public static function sendMsgToFriend(InChatFriendMsg $in): ResultData
    {
        $res = new ResultData();

        $en = $in->getAddEntity();//取数据库中对应数据
        if (Util::isEmpty($en)) return $res->setError(ErrorMsg::postDataError);//请求数据错误

        if (!PowerChatFriend::isFriend($en->account, $en->toAccount))
            return $res->setError(ErrorMsg::accountNotFind);    //您和对方不是好友

        if (MsgCore::sendToFriend($en)) $res->setData($en);//发送成功返回数据
        return $res;           //返回数据
    }

    //查询和好友的聊天记录
    public static function getFriendMsg(InChatFriendMsg $in): ResultData
    {
        $res = new ResultData();
        return $res->setData(Chat_friend_msg_modelSp::getFriendMsg($in));
    }

    //好友双方只删除一个人的聊天记录
    public static function delFriendMsgInAccount(InDelMsg $in): ResultData
    {
        $res = new ResultData();
        $arrId = [];
        foreach ($in->arr as $val)
        {
            $en = new InDelById();
            Tran::setObjFromObjAuto($en, $val);
            $arrId[] = $en->id;//要删除的id数组
        }
        return $res->setData(Chat_friend_msg_modelSp::delMsgInAccount($in->account, $arrId));
    }

    //好友双方的聊天记录都删除
    public static function delFriendMsgByArr(InDelMsg $in): ResultData
    {
        $res = new ResultData();
        $arrId = [];
        foreach ($in->arr as $val)
        {
            $en = new InDelById();
            Tran::setObjFromObjAuto($en, $val);
            $arrId[] = $en->id;//要删除的id数组
        }
        return $res->setData(Chat_friend_msg_modelSp::delMsgByArr($arrId));
    }
}

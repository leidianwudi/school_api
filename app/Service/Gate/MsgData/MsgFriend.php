<?php
/**
 * Created by PhpStorm.
 * User: yj
 * Date: 2019/7/25
 * Time: 18:27
 */

namespace App\Service\Gate\MsgData;

use App\Common\Tran;
use App\Common\Util;
use App\Model\Chat\Friend\Chat_friend_msg_entity;

//好友私聊消息
class MsgFriend
{
    public $id;           /*isKey; int(11); Null:NO ;Default:null ;Comment:id*/
    public $account;      /*isKey; char(32); Null:NO ;Default:null ;Comment:发送人用户名*/
    public $toAccount;    /*char(32); Null:NO ;Default:null ;Comment:接收者用户名*/
    public $addTime;      /*datetime; Null:NO ;Default:null ;Comment:发送时间*/
    public $type;         /*tinyint(1); Null:NO ;Default:null ;Comment:消息类型0:普通消息;1:红包消息*/
    public $msg;          /*varchar(512); Null:NO ;Default:null ;Comment:消息内容*/


    //从用户上传的发送信息内构造出实际要发送的信息
    public static function fromChatMessage(Chat_friend_msg_entity $in): MsgFriend
    {
        $en = new MsgFriend();
        Tran::setObjFromObjAuto($en, $in);  //自动设置数据
        return $en;
    }
}

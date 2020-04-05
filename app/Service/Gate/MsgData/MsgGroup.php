<?php
/**
 * Created by: PhpStorm.
 * User: yj
 * Date: 2019/12/10
 * Time: 17:53
 */

namespace App\Service\Gate\MsgData;


use App\Common\Tran;
use App\Model\Chat\Group\Chat_group_msg_entity;

//群聊消息
class MsgGroup
{
    public $id;         /*isKey; int(11); Null:NO ;Default:null ;Comment:id*/
    public $groupId;    /*int(1); Null:NO ;Default:null ;Comment:聊天群id*/
    public $account;    /*char(32); Null:NO ;Default:null ;Comment:发言人账户*/
    public $nickTip;    /*varchar(32); Null:NO ;Default:null ;Comment:用户在群里的昵称*/
    public $head;       /*varchar(255); Null:NO ;Default:null ;Comment:用户头像*/
    public $addTime;    /*datetime; Null:NO ;Default:null ;Comment:发送时间*/
    public $type;       /*tinyint(1); Null:NO ;Default:null ;Comment:消息类型*/
    public $msg;        /*varchar(512); Null:NO ;Default:null ;Comment:发送内容*/


    //从用户上传的发送信息内构造出实际要发送的信息
    public static function fromChatMessage(Chat_group_msg_entity $in): MsgGroup
    {
        $en = new MsgGroup();
        Tran::setObjFromObjAuto($en, $in);  //自动设置数据
        return $en;
    }
}

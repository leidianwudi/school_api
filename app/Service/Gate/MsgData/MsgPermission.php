<?php
/**
 * Created by PhpStorm.
 * User: yj
 * Date: 2019/8/6
 * Time: 19:59
 */

namespace App\Service\Gate\MsgData;

//用户聊天室权限
class MsgPermission
{
    public $isChatOpen;     //聊天室是否开放，0:关闭，显示聊天室关闭。1:开放
    public $isHallCanSend;  //大厅是否可以发信息 0:不可以(可以看记录，不能发言)   1:可以
    public $isRoomsCanSend; //所有的群聊(不包括大厅)是否可以发信息 0:不可以   1:可以
    public $canAddChat;     //是否可以加好友 0:不可以   1:可以
    public $canAddRoom;     //是否可以创建群 0:不可以   1:可以
}

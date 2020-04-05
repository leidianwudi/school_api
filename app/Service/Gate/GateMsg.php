<?php
/**
 * Created by PhpStorm.
 * User: yj
 * Date: 2019/7/24
 * Time: 16:28
 */

namespace App\Service\Gate;

//发送信息
class GateMsg
{
    //public $code;       //成功或者失败  0：正常
    public $type;       //消息类型 GateMsgType类型
    public $data;       //消息数据，MsgBase类型

    //由消息类型和消息内容构造出发送完整消息
    public static function getMsg($type, $data): GateMsg
    {
        $en = new GateMsg();
        $en->type = $type;
        $en->data = $data;
        return $en;
    }
}

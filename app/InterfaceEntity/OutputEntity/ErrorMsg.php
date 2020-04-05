<?php
/**
 * Created by: PhpStorm.
 * User: yj
 * Date: 2019/12/2
 * Time: 20:49
 */

namespace App\InterfaceEntity\OutputEntity;

//错误数据类型定义
class ErrorMsg
{
    const success                   = 0;        //成功
    const fail                      = -1;       //失败
    const tokenError                = 10000;     //token错误
    const accountMustCharNum        = -10000;   //用户名只能包含字母或数字
    const accountHasExist           = -10001;   //账户已经存在
    const accountNotFind            = -10002;   //账户不存在
    const pwdError                  = -10003;   //密码错误
    const friendNotFind             = -10004;   //好友不存在
    const notFriend                 = -10005;   //您和对方不是好友
    const postDataError             = -10006;   //请求数据错误
    const chatGroupNotFind          = -10007;   //用户群不存在
    const notPower                  = -10008;   //没有权限
    const canNotSendMsg             = -10009;   //没有发言权限
    const notGroupAdmin             = -10010;   //不是群管理
    const groupIsDissolve           = -10011;   //群已经被解散

    //错误定义说明
    const msgArr =
        [
            self::success                   => "成功",
            self::fail                      => "失败",
            self::tokenError                => "token错误",
            self::accountMustCharNum        => "用户名只能包含字母或数字",
            self::accountHasExist           => "账户已经存在",
            self::accountNotFind            => "账户不存在",
            self::pwdError                  => "密码错误",
            self::friendNotFind             => "好友不存在",
            self::notFriend                 => "您和对方不是好友",
            self::postDataError             => "请求数据错误",
            self::chatGroupNotFind          => "用户群不存在",
            self::notPower                  => "没有权限",
            self::canNotSendMsg             => "没有发言权限",
            self::notGroupAdmin             => "不是群管理",
            self::groupIsDissolve           => "群已经被解散",
        ];
}

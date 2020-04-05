<?php
/**
 * Created by: PhpStorm.
 * User: yj
 * Date: 2019/12/3
 * Time: 17:03
 */

namespace App\Service\Chat\Friend;


use App\Common\Log;
use App\InterfaceEntity\InputEntity\Chat\Friend\InChatFriendUser;
use App\InterfaceEntity\OutputEntity\ResultData;
use App\Model\Chat\Friend\Chat_friend_user_entity;
use App\Model\Chat\Friend\Chat_friend_user_modelSp;
use App\Model\Chat\Msg\Chat_msg_last_modelSp;
use App\Redis\Chat\Friend\RedisChatFriendUser;
use App\Service\Chat\Msg\MsgLastCore;
use Illuminate\Support\Facades\DB;

//好友操作
class ChatFriendUserCore
{
    //查询用户的多个好友
    public static function getFriendsByAccount(InChatFriendUser $in): ResultData
    {
        $res = new ResultData();
        $arr = RedisChatFriendUser::getFriendsByAccount($in->account, $in->page, $in->count);
        return $res->setData($arr);           //返回数据
    }

    //查询特定好友
    public static function getFriendByAccount(InChatFriendUser $in): ResultData
    {
        $res = new ResultData();
        $en = Chat_friend_user_modelSp::getFriendByAccount($in->account, $in->friendAccount);
        return $res->setData($en);           //返回数据
    }

    //修改好友的备注昵称
    public static function updFriendNickTip(InChatFriendUser $in): ResultData
    {
        $res = new ResultData();
        $en = Chat_friend_user_modelSp::getFriendByAccount($in->account, $in->friendAccount);
        $enNew = new Chat_friend_user_entity();
        $enNew->id = $en->id;   //id
        $enNew->friendNickTip = $in->friendNickTip; //新昵称
        if (Chat_friend_user_modelSp::updEntity($enNew)) $res->success();//进行修改
        return $res;           //返回数据
    }

    //删除好友
    public static function delFriend(InChatFriendUser $in): ResultData
    {
        $res = new ResultData();
        if (self::delFriendDo($in)) $res->success();//开始删除
        return $res;           //返回数据
    }

    //开始删除好友
    private static function delFriendDo(InChatFriendUser $in): bool
    {
        DB::beginTransaction();
        try
        {
            //查询删除人和好友的记录
            $enFriend = Chat_friend_user_modelSp::getEntityByAccountFriend($in->account, $in->friendAccount);
            if (!Chat_friend_user_modelSp::delEntity($enFriend->id))
            {
                DB::rollBack();
                return false;
            }

            //查询好友和删除人的记录
            $enFriend2 = Chat_friend_user_modelSp::getEntityByAccountFriend($in->friendAccount, $in->account);
            if (!Chat_friend_user_modelSp::delEntity($enFriend2->id))
            {
                DB::rollBack();
                return false;
            }

            //删除最好聊天记录表数据
            if (!Chat_msg_last_modelSp::delMsgForFriend($in->account, $in->friendAccount))
            {
                DB::rollBack();
                return false;
            }

            RedisChatFriendUser::delFriendAccounts($enFriend->account);//删除好友缓存
            RedisChatFriendUser::delFriendAccounts($enFriend2->account);//删除好友缓存

            DB::commit();
            return true;
        }
        catch (\Exception $e) {
            DB::rollBack();
            Log::errorTrace($e);
            return false;
        }
    }
}

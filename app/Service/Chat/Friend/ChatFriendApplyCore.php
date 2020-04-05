<?php
/**
 * Created by: PhpStorm.
 * User: yj
 * Date: 2019/12/3
 * Time: 17:02
 */

namespace App\Service\Chat\Friend;


use App\Common\Log;
use App\Common\Util;
use App\InterfaceEntity\InputEntity\Chat\Friend\InApplyFriend;
use App\InterfaceEntity\InputEntity\Chat\Friend\InChatFriendApply;
use App\InterfaceEntity\OutputEntity\ErrorMsg;
use App\InterfaceEntity\OutputEntity\ResultData;
use App\Model\Chat\Friend\Chat_friend_apply_modelSp;
use App\Model\Chat\Friend\Chat_friend_user_modelSp;
use App\Model\Enum\Chat\Friend\EnumChatFriendApply;
use App\Model\User\User_modelSp;
use App\Redis\Chat\Friend\RedisChatFriendUser;
use App\Service\Chat\Msg\MsgLastCore;
use Illuminate\Support\Facades\DB;

//好友申请操作
class ChatFriendApplyCore
{
    //向某人申请加为好友
    public static function applyFriend(InApplyFriend $in): ResultData
    {
        $res = new ResultData();
        $userEn = User_modelSp::getEntityByAccount($in->account);
        if (Util::isEmpty($userEn)) return $res->setError(ErrorMsg::accountNotFind);    //用户不存在
        $applyEn = $in->getApplyEntity();
        if (Util::isEmpty($applyEn)) return $res->setError(ErrorMsg::accountNotFind);   //用户不存在

        $oldEn = Chat_friend_apply_modelSp::getByUserAndFrom($in->account, $in->fromAccount);
        if (!Util::isEmpty($oldEn))
        {
            $applyEn->id = $oldEn->id;  //从新设置id，修改旧数据
            return $res->setData(Chat_friend_apply_modelSp::updEntity($applyEn));
        }

        return $res->setData(Chat_friend_apply_modelSp::insEntity($applyEn));           //添加申请信息到数据库
    }

    //查询和用户相关的好友申请记录
    public static function getApplyListByUser(InChatFriendApply $in): ResultData
    {
        $res = new ResultData();
        $arr = Chat_friend_apply_modelSp::getApplyListByUser($in->account, $in->status, $in->page, $in->count);
        return $res->setData($arr);           //返回数据
    }

    //审核好友申请记录为已添加状态
    public static function applyFriendAudit(InChatFriendApply $in): ResultData
    {
        $res = new ResultData();

        $en = Chat_friend_apply_modelSp::getEntityById($in->id);
        if (Util::isEmpty($en)) return $res;    //数据不存在

        $enFriend = User_modelSp::getEntityByAccount($en->fromAccount);
        if (Util::isEmpty($enFriend)) return $res->setError(ErrorMsg::friendNotFind); //好友不存在

        if (self::applyFriendAuditDo($in)) $res->success();
        return $res;           //返回数据
    }

    //审核好友申请记录为已添加状态 开始事务
    private static function applyFriendAuditDo(InChatFriendApply $in): bool
    {
        DB::beginTransaction();
        try
        {
            //修改申请记录为已添加
            if (!Chat_friend_apply_modelSp::setStatusById($in->id, EnumChatFriendApply::status_dispose))
            {
                DB::rollBack();
                return false;
            }

            //要添加的好友信息
            $enFriend = $in->getNewFriend();
            if (!Chat_friend_user_modelSp::insEntity($enFriend))    //添加失败回滚
            {
                DB::rollBack();
                return false;
            }

            //要添加的好友信息2，因为好友是双方的
            $enFriend2 = $in->getNewFriend2();
            if (!Chat_friend_user_modelSp::insEntity($enFriend2))   //添加失败回滚
            {
                DB::rollBack();
                return false;
            }

            //添加好友的第1条聊天信息
            if (!MsgLastCore::addMsgLastForFriend($enFriend, $enFriend2))
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

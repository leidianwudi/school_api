<?php
/**
 * Created by: PhpStorm.
 * User: yj
 * Date: 2019/12/3
 * Time: 17:08
 */

namespace App\Service\Chat\Group;


use App\Common\Log;
use App\Common\Str;
use App\Common\Time;
use App\Common\Util;
use App\InterfaceEntity\InputEntity\Chat\Group\InFriendJoinGroup;
use App\InterfaceEntity\InputEntity\Chat\Group\InGroupUser;
use App\InterfaceEntity\OutputEntity\ErrorMsg;
use App\InterfaceEntity\OutputEntity\ResultData;
use App\Model\Chat\Group\Chat_group_apply_entity;
use App\Model\Chat\Group\Chat_group_apply_modelSp;
use App\Model\Chat\Group\Chat_group_entity;
use App\Model\Chat\Group\Chat_group_modelSp;
use App\Model\Chat\Group\Chat_group_user_entity;
use App\Model\Chat\Group\Chat_group_user_modelSp;
use App\Model\Enum\Chat\Group\EnumChatGroup;
use App\Model\Enum\Chat\Group\EnumChatGroupApply;
use App\Model\Enum\Chat\Group\EnumChatGroupMsg;
use App\Model\Enum\Chat\Group\EnumChatGroupUser;
use App\Model\Enum\Chat\Msg\EnumChatMsgLast;
use App\Model\User\User_entity;
use App\Redis\Chat\Group\RedisChatGroup;
use App\Redis\Chat\Group\RedisChatGroupUser;
use App\Redis\User\RedisUser;
use App\Service\Chat\Friend\PowerChatFriend;
use App\Service\Chat\Msg\MsgLastCore;
use Illuminate\Support\Facades\DB;

//群用户
class ChatGroupUserCore
{
    //查询群内所有用户
    public static function getUserByGroupId(InGroupUser $in): ResultData
    {
        $res = new ResultData();
        $arr = RedisChatGroupUser::getUserByGroupId($in->groupId, $in->page, $in->count);
        return $res->setData($arr);
    }

    //查询用户的所有群
    public static function getGroupByAccount(InGroupUser $in): ResultData
    {
        $res = new ResultData();
        $arr = RedisChatGroupUser::getGroupByAccount($in->account, $in->page, $in->count);//查询用户的所有群
        return $res->setData($arr);
    }

    //直接拉好友入群
    public static function friendJoinGroup(InFriendJoinGroup $in): ResultData
    {
        $res = new ResultData();

        $group = RedisChatGroup::getEntityById($in->groupId);
        if (Util::isEmpty($group)) return $res->setError(ErrorMsg::chatGroupNotFind);//用户群不存在

        $userHost = RedisUser::getUserByAccount($in->account);//拉好友发起人
        if (Util::isEmpty($userHost)) return $res->setError(ErrorMsg::accountNotFind);//账户不存在

        if (!PowerChatFriend::isFriendS($userHost->account, $in->friendAccount))
            return $res->setError(ErrorMsg::notFriend);//您和对方不是好友

        if (!PowerChatGroup::isInGroup($in->account, $in->groupId))
            return $res->setError(ErrorMsg::notPower);//没有权限

        if (self::friendJoinGroupDo($in)) $res->success();//开始拉好友入群

        return $res;
    }

    //开始拉好友入群操作
    private static function friendJoinGroupDo(InFriendJoinGroup $in): bool
    {
        DB::beginTransaction();
        try
        {
            //要加的所有好友
            $arrFriendAccount = Str::split($in->friendAccount, EnumChatGroupUser::friendDiv);
            foreach ($arrFriendAccount as $friendAccount)
            {
                //添加申请记录
                $apply = $in->getApplyEntity($friendAccount); //记录
                if (!Chat_group_apply_modelSp::insEntity($apply))
                {
                    DB::rollBack();
                    return false;
                }

                //把用户加入群
                $user = RedisUser::getUserByAccount($friendAccount);
                if (!self::joinGroup($user, $in->groupId))
                {
                    DB::rollBack();
                    return false;
                }

                //拉用户入群时，最后一条聊天记录表。修改群聊天的最后一条记录(同时会把信息写入群聊天表)
                $userHost = RedisUser::getUserByAccount($in->account);//拉好友发起人
                if (!MsgLastCore::updMsgLastForGroupApply($userHost, $user, $in->groupId))
                {
                    DB::rollBack();
                    return false;
                }
            }

            RedisChatGroupUser::getUserByGroupId_del($in->groupId);//删除群内用户缓存

            DB::commit();
            return true;
        }
        catch (\Exception $e) {
            DB::rollBack();
            Log::errorTrace($e);
            return false;
        }
    }

    //把用户加入群   $isHost:是否群主
    public static function joinGroup(User_entity $user, $groupId, bool $isHost = false): bool
    {
        $en = new Chat_group_user_entity();
        $en->groupId    = $groupId;
        $en->account    = $user->account;
        $en->head       = $user->head;
        $en->nickTip    = $user->nick;
        $en->job = $isHost ? EnumChatGroupUser::job_host : EnumChatGroupUser::job_nor;
        $en->status     = EnumChatGroup::status_nor;    //正常状态
        $en->addTime    = Time::getNowYMD_HIS();        //创建时间
        return Chat_group_user_modelSp::insEntity($en);
    }

    //用户自己退出群
    public static function outGroupByUser(InFriendJoinGroup $in): ResultData
    {
        $res = new ResultData();
        if (!PowerChatGroup::isInGroup($in->account, $in->groupId))
            return $res->success();//用户不在群里直接返回成功

        $user = RedisChatGroupUser::getUserByGroupIdAccount($in->groupId, $in->account);//要退群的用户
        $OutTip = Str::format(EnumChatMsgLast::msg_outGroupSelf, $user->nickTip);   //退群提示
        if (self::outGroupDo($user, $OutTip))
            $res->success();//开始拉好友入群

        return $res;
    }

    //管理员把用户移出群
    public static function outGroupByAdmin(InFriendJoinGroup $in): ResultData
    {
        $res = new ResultData();
        if (!PowerChatGroup::isInGroup($in->friendAccount, $in->groupId))
            return $res->success();//用户不在群里直接返回成功

        $admin = RedisChatGroupUser::getUserByGroupIdAccount($in->groupId, $in->account);//管理员
        if (Util::isEmpty($admin) || $admin->job != EnumChatGroupUser::job_host)
            return $res->setError(ErrorMsg::notGroupAdmin);//不是群管理

        $user = RedisChatGroupUser::getUserByGroupIdAccount($in->groupId, $in->friendAccount);//要退群的用户
        $OutTip = Str::format(EnumChatMsgLast::msg_outGroupByAdmin, $admin->nickTip, $user->nickTip);//移出群提示
        if (self::outGroupDo($user, $OutTip))
            $res->success();//开始拉好友入群

        return $res;
    }

    //开始用户退出群操作
    private static function outGroupDo(Chat_group_user_entity $user, $OutTip): bool
    {
        DB::beginTransaction();
        try
        {
            //群内用户
            $arrGroupUser = RedisChatGroupUser::getAccountArrByGroupId($user->groupId);

            //用户退出群时，最后一条聊天记录表。修改群聊天的最后一条记录(同时会把信息写入群聊天表)
            if (!MsgLastCore::updMsgLastForGroupOut($user, $OutTip))
            {
                DB::rollBack();
                return false;
            }

            //删除群内用户
            if (!Chat_group_user_modelSp::delEntity($user->id))
            {
                DB::rollBack();
                return false;
            }

            //删除缓存
            foreach ($arrGroupUser as $account)
            {
                RedisChatGroupUser::getGroupIdArrByUser_del($account);
                RedisChatGroupUser::getUserByGroupIdAccount_del($user->groupId, $account);
            }
            RedisChatGroupUser::getUserByGroupId_del($user->groupId);//删除群内用户缓存

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

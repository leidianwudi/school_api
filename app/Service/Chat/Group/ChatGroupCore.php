<?php
/**
 * Created by: PhpStorm.
 * User: yj
 * Date: 2019/12/3
 * Time: 17:07
 */

namespace App\Service\Chat\Group;


use App\Common\DB\DB;
use App\Common\Log;
use App\Common\Util;
use App\InterfaceEntity\InputEntity\Chat\Group\InGroup;
use App\InterfaceEntity\OutputEntity\ErrorMsg;
use App\InterfaceEntity\OutputEntity\ResultData;
use App\Model\Chat\Group\Chat_group_entity;
use App\Model\Chat\Group\Chat_group_modelSp;
use App\Model\Chat\Group\Chat_group_msg_modelSp;
use App\Model\Chat\Group\Chat_group_user_modelSp;
use App\Model\Chat\Msg\Chat_msg_last_modelSp;
use App\Model\User\User_modelSp;
use App\Redis\Chat\Group\RedisChatGroup;
use App\Redis\Chat\Group\RedisChatGroupUser;
use App\Redis\User\RedisUser;
use App\Service\Chat\Msg\MsgLastCore;

//聊天群
class ChatGroupCore
{
    //根据群名称，查询聊天群。like查询
    public static function getGroupByName(InGroup $in): ResultData
    {
        $res = new ResultData();
        return $res;
    }

    //查询群详细信息
    public static function getGroupById(InGroup $in): ResultData
    {
        $res = new ResultData();
        return $res->setData(RedisChatGroup::getEntityById($in->id));
    }

    //修改群通知
    public static function updGroupByHost(InGroup $in): ResultData
    {
        $res = new ResultData();
        $group = RedisChatGroup::getEntityById($in->id);//群
        if (Util::isEmpty($group)) return $res->setError(ErrorMsg::postDataError);

        //不是群主，没有权限
        if ($group->hostAccount != $in->hostAccount) return $res->setError(ErrorMsg::notPower);

        $en = new Chat_group_entity();
        $en->id = $in->id;
        $en->groupName = $in->groupName;//群名
        $en->notice = $in->notice;//通知
        if (Chat_group_modelSp::updEntity($en))
        {
            $res->success();//修改
            RedisChatGroup::getEntityById_del($in->id);//删除缓存
        }
        return $res;
    }

    //创建群
    public static function createGroup(InGroup $in): ResultData
    {
        $res = new ResultData();

        $enHost = RedisUser::getUserByAccount($in->hostAccount);
        //没有找到创建者，数据错误
        if (Util::isEmpty($enHost)) return $res->setError(ErrorMsg::postDataError);
        if (self::createGroupDo($in)) $res->success();
        return $res;
    }

    //解散群，只有群主能解散
    public static function dissolveGroup(InGroup $in): ResultData
    {
        $res = new ResultData();

        $group = RedisChatGroup::getEntityById($in->id);//群
        if (Util::isEmpty($group)) return $res->setError(ErrorMsg::postDataError);

        //不是群主，没有权限
        if ($group->hostAccount != $in->hostAccount) return $res->setError(ErrorMsg::notPower);

        if (self::dissolveGroupDo($group)) $res->success();
        return $res;
    }

    //开始创建群
    private static function createGroupDo(InGroup $in): bool
    {
        DB::begTran();
        try
        {
            //创建群
            $enGroup = $in->getAddEntity(); //群数据
            if (!Chat_group_modelSp::insEntity($enGroup, true)) return DB::rollTran();

            //把群主加入群
            $enHost = RedisUser::getUserByAccount($in->hostAccount);
            if (!ChatGroupUserCore::joinGroup($enHost, $enGroup->id, true)) return DB::rollTran();

            //添加群第1条创建成功提示
            if (!MsgLastCore::addMsgLastForGroup($enHost, $enGroup)) return DB::rollTran();

            //清理玩家群id缓存
            RedisChatGroupUser::getGroupIdArrByUser_del($in->hostAccount);

            return DB::commitTran();
        }
        catch (\Exception $e) {
            Log::errorTrace($e);
            return DB::rollTran();
        }
    }

    //开始解散群
    private static function dissolveGroupDo(Chat_group_entity $group): bool
    {
        DB::begTran();
        try
        {
            //群内用户
            $arrGroupUser = RedisChatGroupUser::getAccountArrByGroupId($group->id);

            //删除群内用户
            if (!Chat_group_user_modelSp::delUserByGroupId($group->id)) return DB::rollTran();

            //删除群
            if (!Chat_group_modelSp::delEntity($group->id)) return DB::rollTran();

            //删除群聊天记录表
            if (!Chat_group_msg_modelSp::delMsgByGroupId($group->id)) return DB::rollTran();

            //删除群最后一条聊天记录表
            if (!Chat_msg_last_modelSp::delMsgByGroup($group->id)) return DB::rollTran();

            //清理群缓存
            RedisChatGroup::getEntityById_del($group->id);
            RedisChatGroupUser::getUserByGroupId_del($group->id);
            foreach ($arrGroupUser as $account)
            {
                RedisChatGroupUser::getGroupIdArrByUser_del($account);
                RedisChatGroupUser::getUserByGroupIdAccount_del($group->id, $account);
            }

            return DB::commitTran();
        }
        catch (\Exception $e) {
            Log::errorTrace($e);
            return DB::rollTran();
        }
    }
}

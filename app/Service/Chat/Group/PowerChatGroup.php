<?php
/**
 * Created by: PhpStorm.
 * User: yj
 * Date: 2019/12/9
 * Time: 21:58
 */

namespace App\Service\Chat\Group;


use App\Common\Arr;
use App\Redis\Chat\Group\RedisChatGroupUser;

//群权限
class PowerChatGroup
{
    //用户是否在某个群里
    public static function isInGroup($account, $groupId): bool
    {
        $groupIdArr = RedisChatGroupUser::getGroupIdArrByUser($account);//用户的所有群id数组
        return Arr::isHaveVal($groupIdArr, $groupId);
    }

    //用户是否可以在群聊发言
    public static function isCanSendMsg($account, $groupId): bool
    {
        //现在权限比较简单，在群里就能发言
        return self::isInGroup($account, $groupId);
    }
}

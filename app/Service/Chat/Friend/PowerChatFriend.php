<?php
/**
 * Created by: PhpStorm.
 * User: yj
 * Date: 2019/12/4
 * Time: 9:28
 */

namespace App\Service\Chat\Friend;


use App\Common\Str;
use App\Common\Util;
use App\Model\Chat\Friend\Chat_friend_user_modelSp;
use App\Model\Enum\Chat\Group\EnumChatGroupUser;
use App\Redis\Chat\Friend\RedisChatFriendUser;

//好友权限
class PowerChatFriend
{
    //两个用户是否是好友
    public static function isFriend($account, $accountFriend): bool
    {
        $ids = RedisChatFriendUser::getFriendAccounts($account);
        $div = EnumChatGroupUser::friendDiv; //分割符
        $accountFriend =  $div . $accountFriend . $div; //要加上分割符
        return Str::contains($ids, $accountFriend) ? true : false;
    }

    //查询2个数组中的用户是否全部为好友，支持字符串查询，多个账户以 | 分割线隔开
    public static function isFriendS($arrAccount, $arrAccountFriends): bool
    {
        $div = EnumChatGroupUser::friendDiv; //分割符
        //是字符串的话，先分割程数组
        if (Util::isStr($arrAccount)) $arrAccount = Str::split($arrAccount, $div);
        if (Util::isStr($arrAccountFriends)) $arrAccountFriends = Str::split($arrAccountFriends, $div);

        foreach ($arrAccount as $account)
        {
            foreach ($arrAccountFriends as $friendAccount)
            {
                //只要有一个不是好友，就全部不是好友
                if (!self::isFriend($account, $friendAccount)) return false;
            }
        }
        return true;
    }
}

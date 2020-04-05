<?php
/**
 * Created by PhpStorm.
 * User: FBI
 * Date: 2018/4/28
 * Time: 11:36
 */

namespace App\Http\Controllers;

use App\Common\Calc;
use App\Common\CommonTool;
use App\Common\DataExport;
use App\Common\Http;
use App\Common\Str;
use App\Common\TimeS;
use App\Common\Util;
use App\Common\Upload;
use App\Common\UtilRedis;
use App\InterfaceEntity\InputEntity\Chat\Msg\InDelMsg;
use App\InterfaceEntity\InputEntity\GamesPlay\VGQP\VgLoginGame;
use App\InterfaceEntity\InputEntity\Lottery\InGetBaseOddsList;
use App\InterfaceEntity\InputEntity\System\InGitUpdate;
use App\InterfaceEntity\OutputEntity\Lottery\OutGetBaseGame;
use App\InterfaceEntity\OutputEntity\Lottery\OutSubGuiZhe;
use App\Model\Active\Activity_modelSp;
use App\Model\Lotteries\Lotteries_orders_trace_modelSp;
use App\Model\ModelCommon\ModelEnum;
use App\Model\ModelCommon\System\EnumUserRate;
use App\Model\ModelEntityFactory;
use App\Model\ModelFactory;
use App\Model\Roles\Privileges_modelSp;
use App\Model\Roles\Roles_privileges_entity;
use App\Model\Roles\Roles_privileges_modelSp;
use App\Model\SysMessage\Message_modelSp;
use App\Model\System\Temps_2_modelSp;
use App\Model\System\User_rate_modelSp;
use App\Model\User\User_tongji_modelSp;
use App\Model\User\User_modelSp;
use App\Model\User\Users_roles_modelSp;
use App\Model\WeBet\Webet_bet_odds_sum_modelSp;
use App\Model\WeBet\Wechat_bet_room_modelSp;
use App\Redis\Chat\Friend\RedisChatFriendUser;
use App\Service\External\StorageCore;
use App\ServiceCore\External\ValidateCode;
use App\ServiceCore\GamesPlay\GameLogToCenterEntityCore;
use App\ServiceCore\GamesPlay\GameOrderCheckCore;
use App\ServiceCore\GamesPlay\GetGameLogCore;
use App\ServiceCore\GamesPlay\KaiYuanQP\KaiYuanApi;
use App\ServiceCore\GamesPlay\KaiYuanQP\KaiYuanTool;
use App\ServiceCore\GamesPlay\VGQP\VgApi;
use App\ServiceCore\GateWay\Chat\ChatCore;
use App\ServiceCore\LotteryCheck\BetCheckFive;
use App\ServiceCore\LotteryProRule\ProRuleSix_LHC;
use App\ServiceCore\LotteryResultCollect\CollectAiYiWCore;
use App\ServiceCore\LotteryResultCollect\CollectCaiKaiWCore;
use App\ServiceCore\LotteryResultCollect\CollectCenterCore;
use App\ServiceCore\LotteryResultCollect\EnumCaiKaiW;
use App\ServiceCore\LotteryRule\LotteryRuleVerify;
use App\ServiceCore\LotteryRule\RuleChe;
use App\ServiceCore\LotteryRule\RuleDanDan;
use App\ServiceCore\LotteryRule\RuleFive;
use App\ServiceCore\LotteryRule\RuleSix;
use App\ServiceCore\LotteryRule\RuleSsc;
use App\ServiceCore\LotteryRule\RuleTool;
use App\ServiceCore\Payment\LinePaymentTool_GFT;
use App\ServiceCore\Payment\LinePaymentCore;
use App\ServiceCore\Payment\LinePaymentTool_SD;
use App\ServiceCore\LotteryProRule\LotteryProRule;
use App\ServiceCore\LotteryProRule\ProRuleChe_Pk10;
use App\ServiceCore\LotteryProRule\ProRuleTool;
use App\ServiceCore\RecUser\RecUserTJCore;
use App\ServiceCore\RedisCommon\ActiveRecMoney_col;
use App\ServiceCore\RedisCommon\RedisActive;
use App\ServiceCore\RedisCommon\RedisAgencyRate;
use App\ServiceCore\RedisCommon\RedisBaseOdds;
use App\ServiceCore\RedisCommon\RedisTool;
use App\ServiceCore\RedisCommon\RedisUserLevels;
use App\ServiceCore\RedisCommon\RedisWinRank;
use App\ServiceCore\RedisCommon\System\RedisUserRate;
use App\ServiceCore\RedisCommon\WeBet\RedisWebet;
use App\ServiceCore\System\GitCore;
use App\ServiceCore\System\LotOpenTimeSetCore;
use App\ServiceCore\System\OfficialLotOpenCore;
use App\ServiceCore\System\SystemLotOpenCore;
use App\ServiceCore\Test\CalcCodeCount;
use App\ServiceCore\Test\OpenLotteryChe;
use App\ServiceCore\Test\OpenLotteryDandan;
use App\ServiceCore\Test\OpenLotteryFive;
use App\ServiceCore\Test\OpenLotteryK3;
use App\ServiceCore\Test\OpenLotterySsc;
use App\ServiceCore\Test\OpenLotteryTest;
use App\ServiceCore\Test\Test1;
use App\ServiceCore\User\UserLevelCore;
use App\ServiceCore\User\UserLineCore;
use App\ServiceCore\User\UserRolesCore;
use App\ServiceCore\Video\AgAsiaGame\AgAsiaService;
use App\ServiceCore\Video\AgAsiaGame\tess;
use App\ServiceCore\Video\Vr\VrService;
use Illuminate\Support\Facades\Storage;
use App\Model\Games\Game_account_modelSp;
use App\ServiceCore\Video\Wg\WgService;
use App\ServiceCore\Sport\Avia\AviaService;

class AutoController extends Controller
{

    //自动创建数据库表字段对应类
    public function createAuto()
    {
        //ModelEntityFactory::create("User", "user");//用户表
        //ModelFactory::create("User", "user", "nick");

        return '成功';
    }
}

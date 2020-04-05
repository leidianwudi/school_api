<?php
/**
 * Created by PhpStorm.
 * User: FBI
 * Date: 2018/6/27
 * Time: 21:40
 */

namespace App\ServiceCore\External;

use App\Common\Calc;
use App\Common\CommonTool;
use App\Common\LogTool;
use App\Common\Str;
use App\Common\TimeS;
use App\Common\UtilRedis;
use App\InterfaceEntity\InputEntity\Lotteries\InAddLotteriesResult;
use App\InterfaceEntity\InputEntity\Lotteries\InGetLotteriesOrders;
use App\InterfaceEntity\InputEntity\Lotteries\InLotteriesCancel;
use App\InterfaceEntity\InputEntity\Lotteries\InLotteriesOpen;
use App\InterfaceEntity\InputEntity\Lottery\InGetLotteryBets;
use App\InterfaceEntity\OutputEntity\ErrorMsg;
use App\InterfaceEntity\OutputEntity\ResultData;
use App\Model\Lotteries\Lotteries_notes_orders_entity;
use App\Model\Lotteries\Lotteries_notes_orders_modelSp;
use App\Model\Lotteries\Lotteries_orders_entity;
use App\Model\Lotteries\Lotteries_orders_modelSp;
use App\Model\Lotteries\Lotteries_orders_trace_modelSp;
use App\Model\Lotteries\Lotteries_result_entity;
use App\Model\Lotteries\Lotteries_result_modelSp;
use App\Model\ModelCommon\EnumLotteriesOrders;
use App\Model\ModelCommon\EnumPoint;
use App\ServiceCore\Lottery\LotteryCore;
use App\ServiceCore\LotteryRule\LotteryRuleVerify;
use App\ServiceCore\LotteryRule\RuleTool;
use App\ServiceCore\Money\MoneyCore;
use App\ServiceCore\Money\MoneyEntityTran;
use App\ServiceCore\RedisCommon\RedisTool;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Log;

//读取验证码
class ValidateCodeCore
{
    //输出验证码
    public static function doCodeImg($ip)
    {
        $v = new ValidateCode();
        $v->doimg();            //输出图片
        $code = $v->getCode();  //验证码
        UtilRedis::setex($code, $code);   //写入缓存
        return "";
    }
}

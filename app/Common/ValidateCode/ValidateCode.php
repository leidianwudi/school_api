<?php
/**
 * Created by PhpStorm.
 * User: FBI
 * Date: 2018/6/27
 * Time: 19:23
 */

namespace App\ServiceCore\External;

use App\Common\Calc;
use App\Common\CommonTool;
use App\Common\LogTool;
use App\Common\Str;
use App\Common\TimeS;
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


//验证码类
class ValidateCode
{
    //private $charset = 'abcdefghkmnprstuvwxyzABCDEFGHKMNPRSTUVWXYZ23456789';//随机因子
    private $charset = '2345689';//随机因子
    private $code;//验证码
    private $codelen = 4;//验证码长度
    private $width = 130;//宽度
    private $height = 50;//高度
    private $img;//图形资源句柄
    private $font;//指定的字体
    private $fontsize = 25;//指定字体大小
    private $fontcolor;//指定字体颜色

    //构造方法初始化
    public function __construct() {
        $this->font = dirname(__FILE__).'/Font/Aeron.ttf';//注意字体路径要写对，否则显示不了图片
    }
    //生成随机码
    private function createCode() {
        $_len = strlen($this->charset)-1;
        for ($i=0;$i<$this->codelen;$i++) {
            $this->code .= $this->charset[mt_rand(0,$_len)];
        }
    }
    //生成背景
    private function createBg() {
        $this->img = imagecreatetruecolor($this->width, $this->height);
        $color = imagecolorallocate($this->img, mt_rand(220,255), mt_rand(220,255), mt_rand(220,255));
        imagefilledrectangle($this->img,0,$this->height,$this->width,0,$color);
    }
    //生成文字
    private function createFont() {
        $_x = $this->width / $this->codelen;
        for ($i=0;$i<$this->codelen;$i++) {
            $this->fontcolor = imagecolorallocate($this->img,mt_rand(0,120),mt_rand(0,120),mt_rand(0,120));
            imagettftext($this->img,$this->fontsize,mt_rand(-30,30),$_x*$i+mt_rand(6,7),$this->height / 1.25,$this->fontcolor,$this->font,$this->code[$i]);
        }
    }
    //生成线条、雪花
    private function createLine() {
        //线条
        for ($i=0;$i<6;$i++) {
            $color = imagecolorallocate($this->img,mt_rand(0,156),mt_rand(0,156),mt_rand(0,156));
            imageline($this->img,mt_rand(0,$this->width),mt_rand(0,$this->height),mt_rand(0,$this->width),mt_rand(0,$this->height),$color);
        }
        //雪花
        for ($i=0;$i<100;$i++) {
            $color = imagecolorallocate($this->img,mt_rand(220,255),mt_rand(220,255),mt_rand(220,255));
            imagestring($this->img,mt_rand(1,5),mt_rand(0,$this->width),mt_rand(0,$this->height),'*',$color);
        }
    }

    //输出
    private function outPut() {
        header('Content-type:image/png');

        header('Access-Control-Allow-Origin:*');
//        header('Access-Control-Allow-Headers', 'Origin, Content-Type, Cookie, Accept, multipart/form-data, application/json, token');
//        header('Access-Control-Allow-Methods', 'GET, POST, PATCH, PUT, OPTIONS');

        imagepng($this->img);
        imagedestroy($this->img);
    }
    //对外生成
    public function doimg() {
        $this->createBg();
        $this->createCode();
        $this->createLine();
        $this->createFont();
        $this->outPut();
        //LogTool::error("验证码码是 ".$this->code);
    }

    //获取验证码
    public function getCode() {
        return strtolower($this->code);
    }
}

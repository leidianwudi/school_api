<?php


namespace App\Common;

//常用工具类
use App\InterfaceEntity\BaseEntity;
use Symfony\Component\HttpFoundation\Request;

class Util
{
    /** 循环post数据中的字段，若ent中也有定义该字段则赋值
     * @param Request $rep
     * @param BaseEntity|null $ent
     */
    public static function getInputFromPost(Request $rep, BaseEntity &$ent = null)
    {
        $strJson = $rep->getContent();//取post内Json格式数据
        $a = Tran::json2Obj($strJson);//直接转换成类
        Tran::setObjFromObjAuto($ent, $a);
    }

    /** 判断是否是object
     * @param $val
     * @return bool
     */
    public static function isObj($val): bool
    {
        return is_object($val);
    }

    //判断字符串是否数字
    public static function isNum($val) : bool
    {
        return is_numeric($val);
    }

    //判断是否字符串
    public static function isStr($val) : bool
    {
        return (is_string($val));
    }

    //判断是否数组
    public static function isArr($val) : bool
    {
        return is_array($val);
    }

    //是否有某字段，支持array和object
    public static function isHaveProperty($obj, $pro): bool
    {
        //dd($obj);
        if (self::isArr($obj)) return Arr::isHaveKey($obj, $pro);
        return property_exists($obj, $pro);
    }

    /**判断$a是否为空， ""、"  "、null、[] 都返回空
     * @param $a:要判断的值，是引用
     * @param bool $delSpec:判断完是否为空后，若为字符串，是否把$a的前后空格删除
     * @return bool:true: 值为空   false:值不为空
     */
    public static function empty(&$a, $delSpec = true)
    {
        if (is_null($a)) return true;
        if (Util::isNum($a)) return false;//数字都不为空
        if (Util::isStr($a))
        {
            if ($delSpec)//改改变a值的情况
            {
                $a = trim($a);//去掉前后空格，并且重新对a赋值
                return empty($a);
            }
            else
            {
                return empty(trim($a));//直接去掉前后空格再判断，不修改a的值
            }
        }
        if (is_bool($a)) return false;//是bool就不为空
        return empty($a);
    }

    /** 判断$a是否为空， ""、"  "、null、[] 都返回空
     * @param $a:要判断的值
     * @return bool:true: 值为空   false:值不为空:
     */
    public static function isEmpty($a): bool
    {
        return Util::empty($a, false);
    }

    //生成24位不重复订单流水号
    public static function getOrderSn24()
    {
        //订购日期
        date_default_timezone_set('PRC');

        //订单号码主体（YYYYMMDDHHIISSNNNNNNNN）
        $order_id_main = date('YmdHis') . rand(10000000,99999999);

        //订单号码主体长度
        $order_id_len = strlen($order_id_main);

        $order_id_sum = 0;
        for($i=0; $i<$order_id_len; $i++){
            $order_id_sum += (int)(substr($order_id_main,$i,1));
        }

        //唯一订单号码（YYYYMMDDHHIISSNNNNNNNNCC）
        $order_id = $order_id_main . str_pad((100 - $order_id_sum % 100) % 100,2,'0',STR_PAD_LEFT);
        return $order_id;
    }

    /**
     *   生成20位绝不重复订单号
     */
    public static function getOrderSn20(){
        $rand = Random::randomNum(0, 9999);    //随机后缀
        $rand = Num::formatNum($rand, 4);   //保留4位
        return date('d').substr(implode(NULL, array_map('ord', str_split(substr(uniqid("", true), 7, 13), 1))), 0, 14) . $rand;
    }

    //创建随机密码
    public static function createPassword($length)
    {
        $password = '';
        //将你想要的字符添加到下面字符串中，默认是数字0-9和26个英文字母
        $chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $char_len = strlen($chars);
        for ($i = 0; $i < $length; $i++) {
            $loop = mt_rand(0, ($char_len - 1));
            //将这个字符串当作一个数组，随机取出一个字符，并循环拼接成你需要的位数
            $password .= $chars[$loop];
        }
        return $password;
    }

    //创建全数字的推荐吗，首位不为0
    public static function createInvite($length)
    {
        $invite = '';
        //将你想要的字符添加到下面字符串中，默认是数字0-9和26个英文字母
        $chars = "0123456789";
        $char_len = strlen($chars);
        for ($i = 0; $i < $length; $i++) {
            if ($i == 0)
                $loop = mt_rand(1, ($char_len - 1));//首位不能为0
            else
                $loop = mt_rand(0, ($char_len - 1));
            //将这个字符串当作一个数组，随机取出一个字符，并循环拼接成你需要的位数
            $invite .= $chars[$loop];
        }
        return $invite;
    }

    //获取所在服务器本机ip地址
    public static function getLocalIp()
    {
        return gethostbyname(gethostname());
    }

    //获取服务器http地址ip
    public static function getServiceIp()
    {
        return $_SERVER["HTTP_HOST"];
    }

    //主动判断是否HTTPS
    public static function isHTTPS()
    {
        if (defined('HTTPS') && HTTPS) return true;
        if (!isset($_SERVER)) return FALSE;
        if (!isset($_SERVER['HTTPS'])) return FALSE;
        if ($_SERVER['HTTPS'] === 1) {  //Apache
            return TRUE;
        } elseif ($_SERVER['HTTPS'] === 'on') { //IIS
            return TRUE;
        } elseif ($_SERVER['SERVER_PORT'] == 443) { //其他
            return TRUE;
        }
        return FALSE;

    }

    //获得服务器http请求的ip地址
    public static function getHttpIp()
    {
        return (self::isHTTPS() ? 'https://' : 'http://').self::getServiceIp()."/";
    }

    //获得服务器http请求的ip地址+工程文件根文件名
    public static function getHttpIpBase()
    {
        return self::getHttpIp().self::getRootFileName()."/";
    }

    //取工程根路径
    public static function getRootPath()
    {
        return base_path();
    }

    //取工程根文件名
    public static function getRootFileName()
    {
        $path = base_path();
        if (Str::contains(PHP_OS, "WIN"))//windows操作系统
        {
            $file = strrchr($path, "\\");
        }
        else
        {
            $file = strrchr($path, "/");
        }
        $file = Str::substr($file, 1, Str::length($file)-1);//截去第一个 符号
        //var_dump($file);
        return $file;
    }

    //获取ip地址的城市
    public static function getIpCity($ip = '')
    {
        return IpLocation::getInstance()->getlocation($ip);
    }

    //取客户端ip地址
    public static function getClientIp() {
        $ip = "unknown";
        /*
         * 访问时用localhost访问的，读出来的是“::1”是正常情况。
         * ：：1说明开启了ipv6支持,这是ipv6下的本地回环地址的表示。
         * 使用ip地址访问或者关闭ipv6支持都可以不显示这个。
         * */
        if (isset($_SERVER)) {
            if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
                $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
            } elseif (isset($_SERVER["HTTP_CLIENT_ip"])) {
                $ip = $_SERVER["HTTP_CLIENT_ip"];
            } else {
                $ip = $_SERVER["REMOTE_ADDR"];
            }
        } else {
            if (getenv('HTTP_X_FORWARDED_FOR')) {
                $ip = getenv('HTTP_X_FORWARDED_FOR');
            } elseif (getenv('HTTP_CLIENT_ip')) {
                $ip = getenv('HTTP_CLIENT_ip');
            } else {
                $ip = getenv('REMOTE_ADDR');
            }
        }
        if(trim($ip)=="::1"){
            $ip="127.0.0.1";
        }

        // 第一个就是正式用户ip
        return explode(",", $ip)[0];
    }

    //是否手机端请求
    public static function isMobile()
    {
        // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
        if (isset($_SERVER['HTTP_X_WAP_PROFILE'])) {
            return true;
        }
        // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
        if (isset($_SERVER['HTTP_VIA'])) {
            // 找不到为flase,否则为true
            return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
        }
        // 脑残法，判断手机发送的客户端标志,兼容性有待提高。其中'MicroMessenger'是电脑微信
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $clientkeywords = array('nokia','sony','ericsson','mot','samsung','htc','sgh','lg','sharp','sie-','philips','panasonic','alcatel','lenovo','iphone','ipod','blackberry','meizu','android','netfront','symbian','ucweb','windowsce','palm','operamini','operamobi','openwave','nexusone','cldc','midp','wap','mobile','MicroMessenger');
            // 从HTTP_USER_AGENT中查找手机浏览器的关键字
            if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
                return true;
            }
        }
        // 协议法，因为有可能不准确，放到最后判断
        if (isset ($_SERVER['HTTP_ACCEPT'])) {
            // 如果只支持wml并且不支持html那一定是移动设备
            // 如果支持wml和html但是wml在html之前则是移动设备
            if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
                return true;
            }
        }
        return false;
    }

    //生成token
    public static function createToken()
    {
        $str="QWERTYUIOPASDFGHJKLZXCVBNM1234567890qwertyuiopasdfghjklzxcvbnm";
        str_shuffle($str);
        return substr(str_shuffle($str),26,32);
    }
}

<?php
/**
 * Created by: PhpStorm.
 * User: yj
 * Date: 2019/12/2
 * Time: 20:45
 */

namespace App\InterfaceEntity\OutputEntity;


class ResultData
{
    public $code = ErrorMsg::fail;      //0：成功标志
    public $msg = "失败";               //提示
    public $token = null;               //token
    public $data = null;                //数据

    //设置具体错误信息
    public function setError($error) : ResultData
    {
        $this->code = $error;
        $this->msg = ErrorMsg::msgArr[$error];
        return $this;
    }

    //设置成功
    public function success() : ResultData
    {
        return self::setError(ErrorMsg::success);
    }

    //设置数据
    public function setData($data) : ResultData
    {
        $this->success();
        $this->data = $data;
        return $this;
    }
}

<?php
namespace App\API\V100\Model;
use Core\Base\Model;
use Core\Lib;

class Pay extends Model {

    public $Conf;
    public $merId;
    public function __construct($appid)
    {
        //manage 获取配置文件
        $file = APP_PATH.'Config/MercConfig/'.$appid."_Config.php";
        $content = file_get_contents($file);
        $ret = json_decode($content,true);
        $this->Conf = $ret;
        if($this->Conf['merchant_id']){
            $this->merId = $this->Conf['merchant_id'];
        }
    }

    //代收
    public function payDf($data){
        $merchantId = $this->merId;
        $version = $data['version']? $data['version'] :'V1.0';
        $userCode = $data['userCode'];
        $sysId = $data['sysId'];
        $notifyUrl = $data['notifyUrl'];
        $cardType = $data['cardType'];
        $orderType = $data['orderType'];
        $amount = $data['amount'];
        $poundage = $data['poundage'];
        $userOrderSn = $data['userOrderSn'];

        $sdk_sign = md5('['.$version.']|['.$userCode.']|['.$sysId.']|[H]|[2]|['.$userOrderSn.']|['.$merchantId.']'.$this->Conf['a_deposit_key']);
        $post = array(
            'version'   => $version,
            'merSn'     => $merchantId,       //大商户号
            'userCode'  => $userCode,         //子商编号
            'cardType'  => $cardType,         //银行卡类型
            'orderType'  => $orderType,       //订单类型
            'sysId'  => $sysId,               //订单类型
            'amount'  => $amount,             //金额
            'poundage'  => $poundage,         //手续费 + 还款笔数费
            'userOrderSn'  => $userOrderSn,   //手续费 + 还款笔数费
            'notifyUrl' => $notifyUrl,        //异步通知地址
            'Sign'      => $sdk_sign
        );
        $ret = Lib::httpPostUrlEncode(ZF_URL."?action=SdkBalanceOrder",$post);
        $sdk_ret = json_decode($ret,true);
        return $sdk_ret;
	}
    //代付查询
    public function payDfQuery($data){
        $merchantId = $this->merId;
        $version = $data['version']? $data['version'] :'V1.0';
        $sysOrderSn = $data['sysOrderSn'];
        $userOrderSn = $data['userOrderSn'];
        //md5,[version]|[sysOrderSn]|[userOrderSn]|[merSn]出款密钥
        $sdk_sign = md5('['.$version.']|['.$sysOrderSn.']|['.$userOrderSn.']|['.$merchantId.']'.$this->Conf['a_deposit_key']);
        $post = array(
            'version'   => $version,
            'merSn'     => $merchantId,       //大商户号
            'sysOrderSn'  => $sysOrderSn,         //手续费 + 还款笔数费
            'userOrderSn'  => $userOrderSn,   //手续费 + 还款笔数费
            'Sign'      => $sdk_sign
        );
        $ret = Lib::httpPostUrlEncode(ZF_URL."?action=SdkBalanceOrderQuery",$post);
        $sdk_ret = json_decode($ret,true);
        return $sdk_ret;
    }
    //代收
    public function payDs($data){
        $merchantId = $this->merId;
        $version = $data['version']? $data['version'] :'V1.0';
        $userCode = $data['userCode'];
        $sysId = $data['sysId'];
        $notifyUrl = $data['notifyUrl'];
        $cardType = $data['cardType'];
        $orderType = $data['orderType'];
        $amount = $data['amount'];
        $userOrderSn = $data['userOrderSn'];
        $attach = $data['attach'];

        $sdk_sign = md5('['.$version.']|['.$userCode.']|['.$sysId.']|['.$orderType.']|['.$cardType.']|['.$userOrderSn.']|['.$merchantId.']'.$this->Conf['deposit_key']);
        $post = array(
            'version'   => $version,
            'merSn'     => $merchantId,       //大商户号
            'userCode'  => $userCode,         //子商编号
            'cardType'  => $cardType,         //银行卡类型
            'orderType'  => $orderType,       //订单类型
            'sysId'  => $sysId,               //订单类型
            'amount'  => $amount,             //金额
            'userOrderSn'  => $userOrderSn,   //手续费 + 还款笔数费
            'notifyUrl' => $notifyUrl,        //异步通知地址
            'attach' => $attach,
            'Sign'      => $sdk_sign,
        );
        $ret = Lib::httpPostUrlEncode(ZF_URL."?action=SdkPayOrder",$post);
        $sdk_ret = json_decode($ret,true);
        return $sdk_ret;
    }
    //代收查询
    public function payDsQuery($data){
        $merchantId = $this->merId;
        $version = $data['version']? $data['version'] :'V1.0';
        $sysOrderSn = $data['sysOrderSn'];
        $userOrderSn = $data['userOrderSn'];
        //md5,[version]|[sysOrderSn]|[userOrderSn]|[merSn]入款密钥
        $sdk_sign = md5('['.$version.']|['.$sysOrderSn.']|['.$userOrderSn.']|['.$merchantId.']'.$this->Conf['deposit_key']);
        $post = array(
            'version'   => $version,
            'merSn'     => $merchantId,       //大商户号
            'sysOrderSn'  => $sysOrderSn,         //手续费 + 还款笔数费
            'userOrderSn'  => $userOrderSn,   //手续费 + 还款笔数费
            'Sign'      => $sdk_sign
        );
        $ret = Lib::httpPostUrlEncode(ZF_URL."?action=SdkPayQuery",$post);
        $sdk_ret = json_decode($ret,true);
        return $sdk_ret;
    }

    //余额转账
    public function payTrans($data){
        $merchantId = $this->merId;
        $version = $data['version']? $data['version'] :'V1.0';
        $userCode = $data['userCode'];
        $amount = $data['amount'];
        $remark = $data['remark'];
        //md5,[version]|[userCode]|[merSn]出款密钥
        $sdk_sign = md5('['.$version.']|['.$userCode.']|['.$merchantId.']'.$this->Conf['a_deposit_key']);
        $post = array(
            'version'   => $version,
            'merSn'     => $merchantId,       //大商户号
            'userCode'  => $userCode,     
            'amount'  => $amount,   
            'remark'  => $remark,   
            'Sign'      => $sdk_sign
        );
        $ret = Lib::httpPostUrlEncode(ZF_URL."?action=SdkBalanceTo",$post);
        $sdk_ret = json_decode($ret,true);
        return $sdk_ret;
    }

}
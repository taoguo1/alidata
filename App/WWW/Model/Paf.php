<?php
namespace App\WWW\Model;
use Core\Base\Model;
use Core\Lib;

class Paf extends Model {

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
    //设置费率
    public function paySetRate($data){
        $merchantId = $this->merId;
        $version = $data['version']? $data['version'] :'V1.0';
        $userCode = $data['userCode'];
        $czValue = $data['czValue'];
        $txValue = $data['txValue'];
        $xfValue = $data['xfValue'];
        $jqValue = $data['jqValue'];
        $hkValue = $data['hkValue'];

        $sdk_sign = md5('['.$version.']|['.$userCode.']|['.$merchantId.']'.$this->Conf['merchant_key']);
        $post = array(
            'version'   => $version,
            'merSn'     => $merchantId,       //大商户号
            'userCode'  => $userCode,         //子商编号
            'czValue'  => $czValue,           //充值手续费
            'txValue'  => $txValue,           //提现手续费
            'xfValue'  => $xfValue,           //消费手续费
            'jqValue'  => $jqValue,           //鉴权手续费
            'hkValue'  => $hkValue,           //还款手续费
            'Sign'      => $sdk_sign
        );
        $ret = Lib::httpPostUrlEncode(ZF_URL."?action=SdkUserRate",$post);
        $sdk_ret = json_decode($ret,true);
        return $sdk_ret;
    }
    //代付
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
        $poundage_branch = $data['poundage_branch'];
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
            'poundage'  => $poundage,         //小商户手续费
            'poundage_branch'  => $poundage_branch,  //大商户手续费
            'userOrderSn'  => $userOrderSn,   //手续费 + 还款笔数费
            'notifyUrl' => $notifyUrl,        //异步通知地址
            'Sign'      => $sdk_sign
        );
        //Lib::pLog('payDfIn.log',"paramIn:".json_encode($post)."\n",'Paf');
        $ret = Lib::httpPostUrlEncode(ZF_URL."?action=SdkBalanceOrder",$post);
        //Lib::pLog('payDfOut.log',"paramOut:".$ret."\n",'Paf');
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
        $poundage = $data['poundage'];
        $poundage_branch = $data['poundage_branch'];
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
            'poundage'  => $poundage,         //小商户手续费
            'poundage_branch'  => $poundage_branch,  //大商户手续费
            'userOrderSn'  => $userOrderSn,
            'notifyUrl' => $notifyUrl,        //异步通知地址
            'attach' => $attach,
            'Sign'      => $sdk_sign,
        );
        //Lib::pLog('payDsIn.log',"paramIn:".json_encode($post)."\n",'Paf');
        $ret = Lib::httpPostUrlEncode(ZF_URL."?action=SdkPayOrder",$post);
        //Lib::pLog('payDsOut.log',"paramOut:".$ret."\n",'Paf');
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

    //套现出款
    public function payTxOut($data){
        $merchantId = $this->merId;
        $version = 'V1.0';
        $userCode = $data['userCode'];
        $notifyUrl = $data['notifyUrl'];
        $bankCode = $data['bankCode'];
        $bankNo = $data['bankNo'];
        $userOrderSnIn = $data['userOrderSnIn'];
        $userOrderSn = $data['userOrderSn'];
        $attach = $data['attach'];
        //md5,[version]|[userCode]|[bankCode]|[bankNo]|[userOrderSn]|[userOrderSnIn]|[merSn]出款密钥
        $sdk_sign = md5('['.$version.']|['.$userCode.']|['.$bankCode.']|['.$bankNo.']|['.$userOrderSn.']|['.$userOrderSnIn.']|['.$merchantId.']'.$this->Conf['a_deposit_key']);
        $post = array(
            'action'   => 'SdkTxOut',
            'version'   => $version,
            'merSn'     => $merchantId,                     //大商户号
            'userCode'  => $userCode,                       //子商编号
            'bankCode'  => $bankCode,                       //银行卡类型
            'bankNo'  => $bankNo,                        //订单类型
            'userOrderSnIn'  => $userOrderSnIn,             //金额
            'userOrderSn'  => $userOrderSn,                 //手续费 + 还款笔数费
            'notifyUrl' => $notifyUrl,                      //异步通知地址
            'attach' => $attach,
            'Sign' => $sdk_sign,
        );
        $ret = Lib::httpPostUrlEncode(ZF_URL,$post);
        $sdk_ret = json_decode($ret,true);
        return $sdk_ret;
    }

    //套现出款
    public function payRateAll($data){
        $merchantId = $data['merchant_id'];
        $version = 'V1.0';
        //md5,[version]|[merSn]商户密钥
        $sdk_sign = md5('['.$version.']|['.$merchantId.']'.$this->Conf['merchant_key']);
        $post = array(
            'action'   => 'SdkUserRateAll',
            'version'   => $version,
            'merSn'     => $merchantId,                  //大商户号
            'czValue'  => $data['czValue'],              //充值手续费
            'txValue'  => $data['txValue'],              //提现手续费
            'xfValue'  => $data['xfValue'],              //消费手续费
            'jqValue'  => $data['jqValue'],              //鉴权手续费
            'hkValue'  => $data['hkValue'],              //还款手续费
            'sfValue' => $data['sfValue'],               //身份鉴权手续费
            'txInValue' => $data['txInValue'],           //套现入款手续费
            'txOutValue' => $data['txOutValue'],         //套现出款手续费
            'Sign' => $sdk_sign,
        );
        $ret = Lib::httpPostUrlEncode(ZF_URL,$post);
        $sdk_ret = json_decode($ret,true);
        return $sdk_ret;
    }

}
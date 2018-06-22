<?php
namespace App\CALL\Controller;

use Core\Base\Controller;
use Core\Lib;
use Core\DB\DBQ;
use Core\Extend\Redis;

class GatherNotice extends Controller
{

    public function in(){
        $userCode = Lib::request('userCode');
        $userOrderSn = Lib::request('userOrderSn');
        $sysOrderSn = Lib::request('sysOrderSn');
        $amount = Lib::request('amount');
        $attach = Lib::request('attach');

        $redis = Redis::instance('msg');
        $attachRedis = $redis->get('gather_data_'.$userOrderSn);
        $this->M()->myLog('gather_in_notice.txt',$attachRedis);
        $attachData = explode('|',$attachRedis);
        $appid = $attachData[0];
        $bankId = $attachData[1];
        $bankNo = $attachData[2];

        $dbHandle = $this->M()->getDb($appid);
        $billRow = $dbHandle->get('bill',['user_id','poundage'],['userOrderSn'=>$userOrderSn]);
        $dbHandle->update('bill',['status'=>1,'is_pay'=>1],['order_sn'=>$userOrderSn]);
        $bankInfo = DBQ::getRow('bank',['code_hlb','name'],['id'=>$bankId]);
        $this->M()->myLog('gather_in_notice.txt',$bankInfo['code_hlb'].":".$attach.":".$attachRedis.":".$sysOrderSn.":".$userOrderSn.json_encode($bankInfo)."bankid:".$bankId.":billRow:".json_encode($billRow)."\n");

        //构造套现出款数据
        $order_sn=  Lib::createOrderNo();
        $outData = [
            'userCode'  => $userCode,
            'bankCode'  => $bankInfo['code_hlb'],
            'bankNo'  => $bankNo,
            'userOrderSnIn'  => $userOrderSn,
            'userOrderSn'  => $order_sn,
            'notifyUrl' => OEM_CTRL_URL.'call/GatherNotice/out?appid='.$appid,
            'attach'=>$appid,
        ];

        //print_r($inData);
        $payHandle = new \App\WWW\Model\Paf($appid);
        $outResult = $payHandle->payTxOut($outData);
        $this->M()->myLog('gather_in_notice.txt',json_encode($outResult)."\n");

        if($outResult['error'] == 0){
            $billData = [
                'user_id' => $billRow['user_id'],
                'amount' => $amount-$billRow['poundage'],
                'bill_type' => 8,
                'card_type' => 2,
                'bank_name' => $bankInfo['name'],
                'card_no' => Lib::aesEncrypt($bankNo),
                'bank_id' => $bankId,
                'poundage' => $billRow['poundage'],
                'order_sn' => $order_sn,
                'userOrderSn'=> $outResult['userOrderSn'],
                'sysOrderSn'=> $outResult['sysOrderSn'],
                'channel' => 2,
                'status' => -1,
                'intatus' => 1,
                'is_pay' => -1,
                'create_time' => Lib::getMs(),
            ];
            $dbHandle->insert('bill',$billData);
            $this->M()->myLog('gather_in_notice.txt','bill2:'.json_encode($billData)."\n");
            //套现分润
            $r = $this->M()->txSharing($appid,['user_id'=>$billRow['user_id'],'amount'=>$amount,'userOrderSn'=>$order_sn]);
            $this->M()->myLog('gather_in_notice.txt','fr:'.json_encode($r)."\n");
        }
        die('success');
    }

    public function in_no(){
        $userCode = Lib::request('userCode');
        $userOrderSn = Lib::request('userOrderSn');
        $sysOrderSn = Lib::request('sysOrderSn');
        $attach = Lib::request('attach');

        $redis = Redis::instance('msg');
        $attachRedis = $redis->get('gather_data_'.$userOrderSn);
        $this->M()->myLog('gather_in_notice.txt',$attachRedis);
        $attachData = explode('|',$attachRedis);
        $appid = $attachData[0];
        $bankId = $attachData[1];
        $bankNo = $attachData[2];

        $dbHandle = $this->M()->getDb($appid);
        $billRow = $dbHandle->get('bill',['user_id','poundage'],['userOrderSn'=>$userOrderSn]);
        $dbHandle->update('bill',['status'=>1,'is_pay'=>1],['order_sn'=>$userOrderSn]);
        $bankInfo = DBQ::getRow('bank',['code_hlb','name'],['id'=>$bankId]);
        $this->M()->myLog('gather_in_notice.txt',$bankInfo['code_hlb'].":".$attach.":".$attachRedis.":".$sysOrderSn.":".$userOrderSn.json_encode($bankInfo)."bankid:".$bankId.":billRow:".json_encode($billRow)."\n");

        //构造套现出款数据
        $order_sn=  Lib::createOrderNo();
        $outData = [
            'userCode'  => $userCode,
            'bankCode'  => $bankInfo['code_hlb'],
            'bankNo'  => $bankNo,
            'userOrderSnIn'  => $userOrderSn,
            'userOrderSn'  => $order_sn,
            'notifyUrl' => OEM_CTRL_URL.'call/GatherNotice/out?appid='.$appid,
            'attach'=>$appid,
        ];

        //print_r($inData);
        $payHandle = new \App\WWW\Model\Paf($appid);
        $outResult = $payHandle->payTxOut($outData);
        $this->M()->myLog('gather_in_notice.txt',json_encode($outResult)."\n");

        if($outResult['error'] == 0){
            /*
            $billData = [
                'user_id' => $billRow['user_id'],
                'amount' => $amount-$billRow['poundage'],
                'bill_type' => 8,
                'card_type' => 2,
                'bank_name' => $bankInfo['name'],
                'card_no' => Lib::aesEncrypt($bankNo),
                'bank_id' => $bankId,
                'poundage' => $billRow['poundage'],
                'order_sn' => $order_sn,
                'userOrderSn'=> $outResult['userOrderSn'],
                'sysOrderSn'=> $outResult['sysOrderSn'],
                'channel' => 2,
                'status' => -1,
                'intatus' => 1,
                'is_pay' => -1,
                'create_time' => Lib::getMs(),
            ];
            $dbHandle->insert('bill',$billData);
            $this->M()->myLog('gather_in_notice.txt','bill2:'.json_encode($billData)."\n");
            //套现分润
            $r = $this->M()->txSharing($appid,['user_id'=>$billRow['user_id'],'amount'=>$amount,'userOrderSn'=>$order_sn]);
            $this->M()->myLog('gather_in_notice.txt','fr:'.json_encode($r)."\n");
            */
        }
        die('success');
    }

    public function out(){

        $userOrderSn = Lib::request('userOrderSn');
        $status = Lib::request('status');
        $appid = Lib::request('appid');
        $this->M()->myLog('gather_out_notice.txt',json_encode($appid.'|'.$userOrderSn.'|'.$status)."\n");
        //if($status == 'success') {
            $dbHandle = $this->M()->getDb($appid);
            $ret = $dbHandle->update('bill', ['status' => 1, 'is_pay' => 1], ['order_sn' => $userOrderSn]);
            if ($ret) {
                die('success');
            }
        //}

    }
	
	/**
	yjf
	*/
	
	public function yjfNotify(){
        $appId = Lib::request('appId');
        $userId = Lib::request('userId');
        $partnerOrderNo = Lib::request('orderId');		
		$amount = Lib::request('amount');
        $receiveAmount = Lib::request('receiveAmount');
		$creditCard = Lib::request('creditCard');
        $debitCard = Lib::request('debitCard');

        $data=[
            'appId'=>$appId,
            'userId'=>$userId,
            'orderId'=>$partnerOrderNo,
            'amount'=>$amount,
            'receiveAmount'=>$receiveAmount,
            'creditCard'=>$creditCard,
            'debitCard'=>$debitCard
        ];
        $this->M()->myLog(date('h').'-yjf_notice.txt','yjfbill:'.json_encode($data));
        $dbHandle=$this->M()->getDb($appId);
        //入
        try{
            $billDataIn = [
                'user_id' => $userId,
                'amount' => $amount,
                'bill_type' => 8,
                'card_type' => 1,
                'bank_name' => '',
                'card_no' => Lib::aesEncrypt($debitCard),
                'bank_id' => 0,
                'poundage' => 0,
                'order_sn' =>$partnerOrderNo,
                'userOrderSn'=> $partnerOrderNo,
                'sysOrderSn'=> '',
                'channel' => 2,
                'status' => 1,
                'intatus' => 1,
                'is_pay' => 1,
                'create_time' => Lib::getMs(),
            ];
            $dbHandle->insert('bill',$billDataIn);
            //出
            $billDataOut = [
                'user_id' => $userId,
                'amount' => $receiveAmount,
                'bill_type' => 8,
                'card_type' => 2,
                'bank_name' => '',
                'card_no' => Lib::aesEncrypt($creditCard),
                'bank_id' => 0,
                'poundage' => 0,
                'order_sn' => $partnerOrderNo,
                'userOrderSn'=>$partnerOrderNo,
                'sysOrderSn'=> '',
                'channel' => 2,
                'status' => 1,
                'intatus' => 1,
                'is_pay' => 1,
                'create_time' => Lib::getMs(),
            ];
            $dbHandle->insert('bill',$billDataOut);
        }catch(\Exception $e){
            echo $e->getMessage();
        }
    
		//入
        /*$dbHandle->update('bill',['status'=>1,'is_pay'=>1,'amount'=>$amount,'order_sn'=>$partnerOrderNo,'userOrderSn'=>$partnerOrderNo,'card_no'=>$cardNo],['id'=>$inId]);
		//出
		$dbHandle->update('bill',['status'=>1,'is_pay'=>1,'amount'=>$receiveAmount,'order_sn'=>$partnerOrderNo,'userOrderSn'=>$partnerOrderNo,'card_no'=>$cardNo],['id'=>$outId]);*/
        die('success');
    }

}


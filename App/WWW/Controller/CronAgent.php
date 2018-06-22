<?php
namespace App\WWW\Controller;
use Core\Base\Controller;
use Core\Lib;
use Core\Extend\Redis;

class CronAgent extends Controller
{

    public function trans($appid = ''){
        if(empty($appid)){ die('appid不能为空'); }
        $dbHandle = $this->M()->getDb($appid);
        $rcd = $dbHandle->get('temp','*',['status'=>0,'ORDER' => ['agent_id' => 'DESC']]);
        if(empty($rcd['userCode']) || empty($rcd['amount'])){ die('无数据'); }
        /*
        if(empty($rcd['userCode']) && !empty($rcd['amount'])){
            $dbHandle->update('temp', ['status' => 1], ['agent_id' => $rcd['agent_id']]);
            $this->M()->myLog('agent_err.txt', "appid:" . $appid . ",agent_id:" . $rcd['agent_id'] . ",result:".json_encode($result)." \n");
            die('无数据');
        }
        */
        //大商户给代理子商户转账
        /**/
        $post = array(
            'userCode'  => $rcd['userCode'],
            'amount'  => $rcd['amount'] * 100,
            'remark'  => '分润',
        );

        $payHandle = new \App\WWW\Model\Paf($appid);
        $result = $payHandle->payTrans($post);
        if($result['error'] == 0) {
            $up = $dbHandle->update('temp', ['status' => 1], ['agent_id' => $rcd['agent_id']]);
            if ($up) {
                $this->M()->myLog('agent.txt', "appid:" . $appid . ",agent_id:" . $rcd['agent_id'] . ",update:1 \n");
                die('转账成功！');
            } else {
                $this->M()->myLog('agent.txt', "appid:" . $appid . ",agent_id:" . $rcd['agent_id'] . ",update:0 \n");
                die('修改状态失败！');
            }
        }else{
            $this->M()->myLog('agent_err.txt', "appid:" . $appid . ",agent_id:" . $rcd['agent_id'] . ",result:".json_encode($result)." \n");
            die('转账失败！');
        }
    }

    public function mtrans($appid,$userCode,$amount){
        $payHandle = new \App\WWW\Model\Paf($appid);
        $post = array(
            'userCode'  => $userCode,
            'amount'  => $amount,   //分
            'remark'  => '分润',
        );
        $result = $payHandle->payTrans($post);
        if($result['error'] == 0) {
            die('转账成功！');
        }else{
            die('转账失败！');
        }
    }

    public function test(){
        echo 111111;
        var_dump($this->M()->transLog());
        echo 2222222;
    }

}


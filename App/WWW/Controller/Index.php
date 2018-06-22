<?php
namespace App\WWW\Controller;

use Core\Base\Controller;
use Core\Lib;
use Core\Extend\Redis;

class Index extends Controller
{
    public function pay1q(){
        $model = new \App\API\V100\Model\Pay('2883ec40801a518d');
        $post = array(
            'version'   => 'V1.0',
            'merSn'     => '11000000001',
            'sysOrderSn'  => 'PO5f8c34bfd7470602',
            'userOrderSn'  => 'H405053642935647',
        );

        $r = $model->payDfQuery($post);
        var_dump($r);
    }

    public function de(){
        $data = [
            'name' =>'郭延鹏',
            'bankno' =>'6221683210423094',
            'idnumber' =>'610602199408213617',
            'mobile' => '13700276115'
        ];
        $cardVerification = Lib::httpPostUrlEncode('https://exchange.dizaozhe.cn/exchange/RealnameAuth/cardFourItemsAndImage',$data);
        var_dump($cardVerification);
    }

    public function mk(){
        $path = 'Logs/Plan/'.date('Y',time()).'/'.date('m',time()).'/'.date('d',time());
        if(!file_exists($path)){
            \mkdir($path,0777,true);
            echo "新建目录";
        }else{
            echo "已存在";
        }
    }

    public function mkmodel(){
        $m = new \App\CALL\Model\Gdb();
        $m->myLog('test.txt','测试');
    }

    public function t(){
        $m = new \App\WWW\Model\Cron();
        $dbHandle = $m->getDb('1feb30526e31e188');
        $r = $dbHandle->get('plan_list',['id','status'],['plan_no'=>3,'plan_id'=>40,'plan_type'=>1]);
        var_dump($r);
        if(empty($r) || $r['status'] != 3){
            die('前序还款任务未完成或已失败！');
        }
    }

    public function t1(){
        $pushData = [
            'content' => "卡号(****************)还款人民币 ".rand(0,9999)." 元,计划成功执行",
            'deviceid' => 12,
            'platform' => 'all',
            'appid' => '1feb30526e31e188'
        ];
        $redisMsg = Redis::instance('plan');
        var_dump($redisMsg);
        echo $redisMsg->zAdd('jpush_repayment',time(), json_encode($pushData));
        echo "<p>执行完成,状态为成功！</p>";
    }

    public function t2(){
        $pushData = [
            'content' => "卡号(****************)消费人民币 ".rand(0,9999)." 元,计划成功执行",
            'deviceid' => 12,
            'platform' => 'all',
            'appid' => '1feb30526e31e188'
        ];
        $redisMsg = Redis::instance('plan');
        echo $redisMsg->zAdd('jpush_consume',time(), json_encode($pushData));
        echo "<p>执行完成,状态为成功！</p>";
    }

    public function t3(){
        $redisMsg = Redis::instance('plan');
        $data = $redisMsg->zRangeByScore('jpush_repayment','-inf',time(),['withscores'=>false,'limit'=>[0,1]]);
        print_r($data);

        echo "<hr>";

        $data = $redisMsg->zRangeByScore('jpush_consume','-inf',time(),['withscores'=>false,'limit'=>[0,1]]);
        print_r($data);
    }

    public function t4(){
        Lib::pLog('pay.txt','入参:');
    }


    public function trans($appid = ''){
        if(empty($appid)){ die('appid不能为空'); }
        $dbHandle = $this->M()->getDb('2aa14a475debc1bc');
        $payHandle = new \App\WWW\Model\Paf('2aa14a475debc1bc');
        var_dump($dbHandle);

        $rcd = $dbHandle->get('table1','*',['status'=>-1,'ORDER' => ['agent_id' => 'DESC']]);
        //大商户给代理子商户转账
        $post = array(
            'userCode'  => $rcd['userCode'],
            'amount'  => $rcd['amount'] * 100,
            'remark'  => '分润',
        );
        $result = $payHandle->payTrans($post);
        if($result['error'] == 0) {
            $up = $dbHandle->update('table1', ['status' => 1], ['agent_id' => $rcd['agent_id']]);
            if ($up) {
                $this->M()->myLog('agent.txt', "appid:" . $appid . ",agent_id:" . $rcd['agent_id'] . ",update:1 \n");
            } else {
                $this->M()->myLog('agent.txt', "appid:" . $appid . ",agent_id:" . $rcd['agent_id'] . ",update:0 \n");
            }
        }else{
            $this->M()->myLog('agent_err.txt', "appid:" . $appid . ",agent_id:" . $rcd['agent_id'] . ",result:".json_encode($result)." \n");
            die('转账失败！');
        }
    }

}


<?php
namespace App\WWW\Controller;
use Core\Base\Controller;
use Core\Extend\Redis;
use Core\Lib;

class CronPlanQueryTest extends Controller
{
    /*
     * 还款状态查询
     */
    public function repayment()
    {
        $redis = Redis::instance('plan');
        //查找到已经执行过的记录
        $data = $redis->zRangeByScore('rQuery','-inf','inf',['withscores'=>false,'limit'=>[0,1]]);
        //print_r($data);
        if($data){
            //批量执行，计算分润
            $valRds1 =  json_decode($data[0]);
            //请求代收查询接口
            $post = array(
                'sysOrderSn'  => $valRds1->sysOrderSn,
                'userOrderSn'  => $valRds1->userOrderSn,
            );
            $dbHandle = $this->M()->getDb($valRds1->appid);
            $payHandle = new \App\API\V100\Model\Pay($valRds1->appid);
            $result = $payHandle->payDfQuery($post);
            //echo "<br>";
            //print_r($result);
            //echo "手续费".$payHandle->Conf['repayment_poundage'];
            $poundage = sprintf('%0.2f',$valRds1->amount *  ($payHandle->Conf['repayment_poundage'] / 10000));
            if($result['error'] == 0) {
                //记账，计入bill表
                $billData1 = [
                    'user_id' => $valRds1->user_id,
                    'plan_id' => $valRds1->plan_id,
                    'amount' => $valRds1->amount,
                    'bill_type' => 1,
                    'card_type' => 1,
                    'bank_name' => $valRds1->bank_name,
                    'card_no' => $valRds1->card_no,
                    'bank_id' => $valRds1->bank_id,
                    'poundage' => $poundage,
                    'channel' => 2,
                    'order_sn' => $result['userOrderSn'],
                    'transaction_id' => Lib::getMs(),
                    'userOrderSn' => $result['userOrderSn'],
                    'sysOrderSn' => $result['sysOrderSn'],
                    'status' => 1,
                    'is_pay' => 1,
                    'intatus' => 1,
                    'create_time' => Lib::getMs(),
                ];
                //echo "<hr>";
                //var_dump($valRds1);
                //print_r($billData1);
                var_dump($billRet1 = $dbHandle->insert('bill',$billData1));
                //echo "<hr>";
                //echo $valRds1->id;
                //echo "<hr>";
                $dbHandle->update('plan_list',['userOrderSn'=>$result['userOrderSn'],'sysOrderSn'=>$result['sysOrderSn'],'status'=>3,'end_time'=>time()],['id'=>$valRds1->id]);
                //插入用户账户表（记账）
                $insertDataUser1 = [
                    'amount' => $poundage * -1,
                    'user_id' => $valRds1->user_id,
                    'desciption' => '还款手续费',
                    'order_sn' => $result['userOrderSn'],
                    'is_pay'      => 1, //-1未支付，1已支付
                    'status'      => 1, //-2锁定
                    'in_type' => 1,
                    'channel' => 2,
                    'create_time'=> Lib::getMs()
                ];
                print_r($insertDataUser1);
                $ret1 = $dbHandle->insert('user_account',$insertDataUser1);
                //计算代理分润
                //获取代理id
                $userrs = $dbHandle->get('user',['id','agent_id'],['id'=>$valRds1->user_id]);
                //echo "获取用户资料<br>";
                //$agentrs = $dbHandle->get('agent',['id','pid','rate'],['id'=>$userrs['agent_id']]);
                $agentrs = $dbHandle->get(
                    'agent (A)',
                    [
                        '[>]agent_ext (B)' => [
                            'A.id' => 'agent_id'
                        ]
                    ],
                    ['A.id','A.pid','A.rate','B.userCode'],
                    ['A.id'=>$userrs['agent_id']]
                );

                //echo "获取代理资料<br>";
                //获取所有上级代理
                $agentList = $this->getAgent($agentrs['pid'],$valRds1->appid);
                //合并数组
                array_unshift($agentList,$agentrs);
                //echo "<hr>-7";
                var_dump($agentList);
                foreach($agentList as $k2 => $val2){
                    $amount = $valRds1->amount * ($val2['rate']/10000);
                    //echo "<br>";
                    //插入代理账户表（记账）
                    $insertData = [
                        'amount' => $amount,
                        'agent_id' => $val2['id'],
                        'description' => '还款分润',
                        'order_sn' => $result['userOrderSn'],
                        'in_type' => 1,
                        'channel' => 2,
                        'create_time'=> Lib::getMs()
                    ];
                    $ret = $dbHandle->insert('agent_account',$insertData);
                    //更新代理账户余额
                    $upd_ret = $dbHandle->update('agent_ext',['total_commission[+]'=>$amount],['agent_id'=>$val2['id']]);
                    //大商户给代理子商户转账
                    /*
                    $post = array(
                        'userCode'  => $val2['userCode'],
                        'amount'  => $amount * 100,
                        'remark'  => '分润',
                    );
                    $result = $payHandle->payTrans($post);
                    */
                }
                //删除redis或临时表中的数据
                $upd2 = $redis->zRem('rQuery',$data[0]);
                echo "<p>执行完成,状态为成功！</p>";
            }elseif($result['status'] == 'PROCESSING'){
                echo "<p>状态未查到,继续轮询</p>";
            }elseif($result['status'] == 'FAILURE'){
                //构造账单数据
                $billData1 = [
                    'user_id' => $valRds1->user_id,
                    'plan_id' => $valRds1->plan_id,
                    'amount' => $valRds1->amount,
                    'bill_type' => 1,
                    'card_type' => 1,
                    'bank_name' => $valRds1->bank_name,
                    'card_no' => $valRds1->card_no,
                    'bank_id' => $valRds1->user_id,
                    'poundage' => $poundage,
                    'channel' => 2,
                    'order_sn' => $result['userOrderSn'],
                    'transaction_id' => Lib::getMs(),
                    'userOrderSn' => $result['userOrderSn'],
                    'sysOrderSn' => $result['sysOrderSn'],
                    'status' => -1,
                    'is_pay' => -1,
                    'intatus' => 1,
                    'create_time' => Lib::getMs(),
                ];
                //记账，计入bill表
                $billRet1 = $dbHandle->insert('bill',$billData1);
                //将计划详情状态改为失败
                $dbHandle->update('plan_list',['userOrderSn'=>$result['userOrderSn'],'sysOrderSn'=>$result['sysOrderSn'],'status'=>6,'end_time'=>time()],['id'=>$valRds1->id]);
                //删除redis或临时表中的数据
                $upd2 = $redis->zRem('rQuery',$data[0]);
                echo "<p>还款结束，状态为失败</p>";
            }
            echo "<p>执行结束</p>";
        }else{
            echo "<p>没有需要执行的任务</p>";
        }
    }

    /*
     * 消费状态查询
     * 先处理异步通知再轮询
     */
    public function consume()
    {
        $redis = Redis::instance('plan');
        //查找到已经执行过的记录
        $data = $redis->zRangeByScore('cQuery','-inf','inf',['withscores'=>false,'limit'=>[0,1]]);
        if($data){
            //批量执行，计算分润
            $valRds1 =  json_decode($data[0]);
            //请求代收查询接口
            $post = array(
                'sysOrderSn'  => $valRds1->sysOrderSn,
                'userOrderSn'  => $valRds1->userOrderSn,
            );
            $dbHandle = $this->M()->getDb($valRds1->appid);
            $payHandle = new \App\API\V100\Model\Pay($valRds1->appid);
            $result = $payHandle->payDsQuery($post);
            if($result['error'] == 0) {
                $billData2 = [
                    'user_id' => $valRds1->user_id,
                    'plan_id' => $valRds1->plan_id,
                    'amount' => $valRds1->amount,
                    'bill_type' => 2,
                    'card_type' => 1,
                    'bank_name' => $valRds1->bank_name,
                    'card_no' => $valRds1->card_no,
                    'bank_id' => $valRds1->bank_id,
                    'poundage' => 0,
                    'channel' => 2,
                    'order_sn' => $valRds1->order_sn,
                    'transaction_id' => Lib::getMs(),
                    'status' => 1,
                    'is_pay' => 1,
                    'intatus' => 1,
                    'create_time' => Lib::getMs(),
                ];
                $billRet2 = $dbHandle->insert('bill', $billData2);
                //更新还款计划详情表中的状态为还款中
                $upd1 = $dbHandle->update('plan_list',['status'=>3],['id'=>$valRds1->id]);
                //删除redis或临时表中的数据
                $upd2 = $redis->zRem('cQuery',$data[0]);
                echo "<p>执行完成,状态为成功</p>";
            }elseif($result['status'] == 'PROCESSING'){
                echo "<p>状态未查到,继续轮询</p>";
            }elseif($result['status'] == 'FAILURE'){
                $billData2 = [
                    'user_id' => $valRds1->user_id,
                    'plan_id' => $valRds1->plan_id,
                    'amount' => $valRds1->amount,
                    'bill_type' => 2,
                    'card_type' => 1,
                    'bank_name' => $valRds1->bank_name,
                    'card_no' => $valRds1->card_no,
                    'bank_id' => $valRds1->bank_id,
                    'poundage' => 0,
                    'channel' => 2,
                    'order_sn' => $valRds1->order_sn,
                    'transaction_id' => Lib::getMs(),
                    'status' => -1,
                    'is_pay' => -1,
                    'intatus' => 1,
                    'create_time' => Lib::getMs(),
                ];
                $billRet2 = $dbHandle->insert('bill', $billData2);
                //更新还款计划详情表中的状态为还款中
                $upd1 = $dbHandle->update('plan_list',['status'=>6],['id'=>$valRds1->id]);
                //删除redis或临时表中的数据
                $upd2 = $redis->zRem('cQuery',$data[0]);
                echo "<p>执行完成，状态为失败</p>";
            }
        }else{
            echo "<p>没有需要执行的任务</p>";
        }
    }
    //消费异步通知
    public function notifyConsume(){
        //exit('success');
        $userOrderSn = Lib::request('userOrderSn');
        $sysOrderSn = Lib::request('sysOrderSn');
        $attach_data = Lib::request('attach');
        $attach_data = explode('|',$attach_data);
        $dbHandle = $this->M()->getDb($attach_data[1]);
        //判断订单是否存在，存在说明已经处理过了
        $billRrow = $dbHandle->has('bill',['bill_type'=>2,'userOrderSn'=>$userOrderSn,'sysOrderSn'=>$sysOrderSn]);
        if($billRrow){
            die('success');
        }

        $row = $dbHandle->get('plan_list','*',['id'=>$attach_data[0]]);
        //print_r($row);
        if($userOrderSn) {
            $billData2 = [
                'user_id' => $row['user_id'],
                'plan_id' => $row['plan_id'],
                'amount' => $row['amount'],
                'bill_type' => 2,
                'card_type' => 1,
                'bank_name' => $row['bank_name'],
                'card_no' => $row['card_no'],
                'bank_id' => $row['bank_id'],
                'poundage' => 0,
                'channel' => 2,
                'order_sn' => $userOrderSn,
                'transaction_id' => Lib::getMs(),
                'status' => 1,
                'is_pay' => 1,
                'intatus' => 1,
                'create_time' => Lib::getMs(),
            ];
            $billRet2 = $dbHandle->insert('bill', $billData2);
            //更新还款计划详情表中的状态为还款中
            $upd1 = $dbHandle->update('plan_list',['status'=>3],['id'=>$row['id']]);
            //删除redis或临时表中的数据
            //$upd2 = $redis->zRem('cQuery',$data[0]);
            echo "success";
        }
        
    }

    public function getAgent($pid,$appid,&$data=[]){
        $dbHandle = $this->M()->getDb($appid);
        //$rs = $dbHandle->get('agent',['id','pid','rate'],['id'=>$pid]);
        //$rs = $dbHandle->get('agent',['id','pid','rate'],['id'=>$pid]);
        $rs = $dbHandle->get(
            'agent (A)',
            [
                '[>]agent_ext (B)' => [
                    'A.id' => 'agent_id'
                ]
            ],
            ['A.id','A.pid','A.rate','B.userCode'],
            ['A.id'=>$pid]
        );
        $data[] = $rs;
        if($rs['pid']) {
            $this->getAgent($rs['pid'],$appid, $data);
        }
        return $data;
    }

    public function test(){
        //echo 111;
        $dbHandle = $this->M()->getDb('1feb30526e31e188');
        //var_dump($dbHandle);
        $agentrs = $dbHandle->get(
            'agent (A)',
            [
                '[>]agent_ext (B)' => [
                    'A.id' => 'agent_id'
                ]
            ],
            ['A.id','A.pid','A.rate','B.userCode'],
            ['A.id'=>374]
        );
        //print_r($agentrs);
        //echo "获取代理资料<br>";
        //获取所有上级代理
        $agentList = $this->getAgent($agentrs['pid'],'1feb30526e31e188');
        //合并数组
        array_unshift($agentList,$agentrs);
        //echo "<hr>-7";
        //var_dump($agentList);
        $r = 0;
        foreach($agentList as $k2 => $val2){
            if($r == 0){
                echo $amount = 10000 * ($val2['rate']/10000);
            }else{
                echo $amount = 10000 * (($val2['rate'] - $r) / 10000);
            }
            $r = $val2['rate'];

            echo "<br>";
        }
    }

    public function testdb($appid){
        $dbHandle = $this->M()->getDb($appid);
        var_dump($dbHandle);
    }

    public function testdb1(){
        $dbHandle = $this->M()->select('bill','*');
        var_dump($dbHandle);
    }

    public function testbalance(){
        $dbHandle = $this->M()->getDb('1feb30526e31e188');
        //获取用户余额
        echo $money = $dbHandle->sum('user_account', 'amount', [
            'user_id' => 10
        ]);
    }

    public function testrds(){
        $redis = Redis::instance('plan');
        var_dump($redis);
        $keys = $redis->keys('*');
        var_dump($keys);
    }
    //测试平账
    public function testpz(){
        $billData= [
            'bank_name' => '中信银行',
            'card_no' => '7kuHKbApqMQSXCO/zB2eNwsm4bnypzQ/eREXjZUA6qY=',
            'bank_id' => 22,
        ];
        $pingRet = $this->M()->pingZh('1feb30526e31e188',9,4,$billData);
        var_dump($pingRet);
    }


    public function testq(){
        $post = array(
            'sysOrderSn'  => 'LI034c9e82587843e4',
            'userOrderSn'  => 'H419225791953809',
        );
        $payHandle = new \App\API\V100\Model\Pay('620e0cb4cc5049b3');
        $result = $payHandle->payDsQuery($post);
        var_dump($result);
    }


}




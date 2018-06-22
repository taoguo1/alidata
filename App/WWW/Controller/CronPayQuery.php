<?php
namespace App\WWW\Controller;
use Core\Base\Controller;
use Core\Extend\Redis;
use Core\Lib;


class CronPayQuery extends Controller
{
    public function __construct($controller, $action)
    {
        parent::__construct($controller, $action);
    }
    /*
     * 提现状态查询
     */
    public function withdraw()
    {
        $redis = Redis::instance('plan');
        //查找到已经提现的记录
        $data = $redis->zRangeByScore('withdraw','-inf','inf',['withscores'=>false,'limit'=>[0,1]]);

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
            $poundage = $payHandle->Conf['withdraw_poundage'];
            if($result['error'] == 0) {
                $billRet1 = $dbHandle->update('bill',['status'=>1,'is_pay'=>1,'sysOrderSn'=>$result['sysOrderSn']],['order_sn'=>$result['userOrderSn']]);
                //$dbHandle->update('user_account',['status'=>1,'is_pay'=>1,'sysOrderSn'=>$result['sysOrderSn']],['order_sn'=>$result['userOrderSn']]);
                $dbHandle->update('user_account',['status'=>1,'is_pay'=>1],['order_sn'=>$result['userOrderSn']]);
                //更新账户
                $sum = $dbHandle->sum('user_account','amount',['order_sn'=>$result['userOrderSn']]);
                $bill = $dbHandle->get('bill', '*', ['order_sn' => $result['userOrderSn']]);
                $money = $dbHandle->get('user_ext', '*', ['user_id' => $bill['user_id']]);
                $dbHandle->update('user_ext',['balance'=>$sum + $money['balance']],['user_id'=>$bill['user_id']]);
                //删除redis或临时表中的数据
                $upd2 = $redis->zRem('withdraw',$data[0]);
                echo "<p>执行完成</p>";
            }elseif($result['status'] == 'PROCESSING'){
                echo "<p>状态未查到,继续轮询</p>";
            }elseif($result['status'] == 'FAILURE'){
                //删除redis或临时表中的数据
                $upd2 = $redis->zRem('withdraw',$data[0]);
                $dbHandle->delete('bill',['order_sn'=>$result['userOrderSn']]);
                $dbHandle->delete('user_account',['order_sn'=>$result['userOrderSn']]);
                echo "<p>还款结束，状态为失败</p>";
            }
            echo "<p>执行完成</p>";
        }else{
            echo "<p>没有需要执行的任务</p>";
        }
    }

     /*
     * 代理提现状态查询
     */
    public function agentWithdraw()
    {
        $redis = Redis::instance('plan');
        //查找到已经提现的记录
        $data = $redis->zRangeByScore('aWithdraw','-inf','inf',['withscores'=>false,'limit'=>[0,1]]);
        if($data){
            //批量执行，计算分润
            $valRds1 =  json_decode($data[0]);
            //请求代收查询接口
            $post = array(
                'sysOrderSn'  => $valRds1->sysOrderSn,
                'userOrderSn'  => $valRds1->userOrderSn,
            );
            $dbHandle = $this->M()->getDb($valRds1->appid);
            $payHandle = \App\API\V100\Model\Pay($valRds1->appid);
            $result = $payHandle->payDfQuery($post);
            $poundage = $payHandle->Conf['withdraw_poundage'];
            if($result['error'] == 0) {
                $billRet1 = $dbHandle->update('bill',['status'=>1,'is_pay'=>1,'sysOrderSn'=>$result['sysOrderSn']],['order_sn'=>$result['userOrderSn']]);
                $dbHandle->update('agent_account',['is_pay'=>1],['order_sn'=>$result['userOrderSn']]);
                $sum = $dbHandle->sum('agent_account','*',['amount'],['order_sn'=>$result['userOrderSn']]);
                //更新代理账户
                $sum = $dbHandle->sum('agent_account','amount',['order_sn'=>$result['userOrderSn']]);
                $bill = $dbHandle->get('bill', '*', ['order_sn' => $result['userOrderSn']]);
                $money = $dbHandle->get('agent_ext', '*', ['agent_id' => $bill['agent_id']]);
                $dbHandle->update('user_ext',['total_commission'=>$sum + $money['total_commission']],['agent_id'=>$bill['agent_id']]);
                //需要同步到主服务器账单表中
                
                //删除redis或临时表中的数据
                $upd2 = $redis->zRem('aWithdraw',$data[0]);
                echo "<p>执行完成</p>";
            }elseif($result['status'] == 'PROCESSING'){
                echo "<p>状态未查到,继续轮询</p>";
            }elseif($result['status'] == 'FAILURE'){
                //删除redis或临时表中的数据
                $upd2 = $redis->zRem('aWithdraw',$data[0]);
                $dbHandle->delete('bill',['order_sn'=>$result['userOrderSn']]);
                $dbHandle->delete('user_account',['order_sn'=>$result['userOrderSn']]);
                echo "<p>还款结束，状态为失败</p>";
            }
            echo "<p>执行完成</p>";
        }else{
            echo "<p>没有需要执行的任务</p>";
        }
    }

    /*
     * 充值状态查询
     */
    public function deposit(){
        $redis = Redis::instance('plan');
        $data = $redis->zRangeByScore('deposit','-inf','inf',['withscores'=>false,'limit'=>[0,1]]);
        if($data){
            $valRds1 =  json_decode($data[0]);
            //请求代收查询接口
            $post = array(
                'sysOrderSn'  => $valRds1->sysOrderSn,
                'userOrderSn'  => $valRds1->userOrderSn,
            );
            $dbHandle = $this->M()->getDb($valRds1->appid);
            $payHandle = \App\API\V100\Model\Pay($valRds1->appid);
            $result = $payHandle->payDfQuery($post);
            $poundage = ($payHandle->Conf['deposit_poundage'] / 10000) * $valRds1->amount;
            if($result['error'] == 0) {
                //记账，计入bill表
                $billData1 = [
                    'user_id' => $valRds1->user_id,
                    'plan_id' => $valRds1->plan_id,
                    'amount' => $valRds1->amount,
                    'bill_type' => 4,
                    'card_type' => 2,
                    'bank_name' => $valRds1->bank_name,
                    'card_no' => $valRds1->card_no,
                    'bank_id' => $valRds1->user_id,
                    'poundage' => $poundage,
                    'channel' => 2,
                    'order_sn' => $valRds1->order_sn,
                    'transaction_id' => Lib::getMs(),
                    'userOrderSn' => $result['userOrderSn'],
                    'sysOrderSn' => $result['sysOrderSn'],
                    'status' => 1,
                    'is_pay' => 1,
                    'intatus' => 1,
                    'create_time' => Lib::getMs(),
                ];
                $billRet1 = $dbHandle->insert('bill',$billData1);
                //插入用户账户表（记账）
                $insertDataUser1 = [
                    'amount' => intval($poundage * (-1)),
                    'user_id' => $valRds1->user_id,
                    'desciption' => '还款手续费',
                    'order_sn' => $valRds1->order_sn,
                    'is_pay'      => 1, //-1未支付，1已支付
                    'status'      => 1, //-2锁定
                    'in_type' => 1,
                    'channel' => 2,
                    'create_time'=> Lib::getMs()
                ];
                $ret1 = $dbHandle->insert('user_account',$insertDataUser1);
                //删除redis或临时表中的数据
                $upd2 = $redis->zRem('rQuery',$data[0]);
                echo "<p>执行完成</p>";
            }elseif($result['status'] == 'PROCESSING'){
                echo "<p>状态未查到,继续轮询</p>";
            }elseif($result['status'] == 'FAILURE'){
                //删除redis或临时表中的数据
                $upd2 = $redis->zRem('rQuery',$data[0]);
                echo "<p>充值查询结束，状态为失败</p>";
            }
            echo "<p>执行完成</p>";
        }else{
            echo "<p>没有需要执行的任务</p>";
        }
        
    }

    


}




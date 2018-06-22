<?php
namespace App\WWW\Controller;
use Core\Base\Controller;
use Core\Extend\Redis;
use Core\Lib;

class CronPlanQuery extends Controller
{
    /*
     * 还款状态查询
     */
    public function repayment()
    {
        $redis = Redis::instance('plan');
        //查找到已经执行过的记录
        $data = $redis->zRangeByScore('rQuery','-inf','inf',['withscores'=>false,'limit'=>[0,1]]);
        if($data){
            //批量执行，计算分润
            $valRds1 =  json_decode($data[0]);
            $dat = $redis->hGet('rc_plan_data',$valRds1->appid.'_'.$valRds1->id);
            if(!$dat){
                $redis->zRem('rc:'.$valRds1->task_no,$data[0]);
                $redis->zRem('rQuery',$data[0]);
                die('redis关联数据不存在');
            }
            //请求代收查询接口
            $post = array(
                'sysOrderSn'  => $valRds1->sysOrderSn,
                'userOrderSn'  => $valRds1->userOrderSn,
            );
            $dbHandle = $this->M()->getDb($valRds1->appid);
            $payHandle = new \App\WWW\Model\Paf($valRds1->appid);
            $result = $payHandle->payDfQuery($post);
            $this->M()->myLog('rq.txt',json_encode($result)."\n");
            //手续费保留两位小数
            //$poundage = sprintf('%0.2f',$valRds1->amount *  ($payHandle->Conf['repayment_poundage'] / 10000));
            //手续费保留两位小数，四舍五入
            //$poundage = round($valRds1->amount *  ($payHandle->Conf['repayment_poundage'] / 10000),2);
            //笔数费
            $hkSingle = HK_SINGLE * $valRds1->re_times;
            //获取用户余额
            $money = $dbHandle->sum('user_account', 'amount', [
                'user_id' => $valRds1->user_id
            ]);
            //构造公共的账单数据
            $billData = [
                'user_id' => $valRds1->user_id,
                'plan_id' => $valRds1->plan_id,
                'amount' => $valRds1->amount,
                'bill_type' => 1,
                'card_type' => 1,
                'bank_name' => $valRds1->bank_name,
                'card_no' => $valRds1->card_no,
                'bank_id' => $valRds1->bank_id,
                'poundage' => $hkSingle,
                'channel' => 2,
                'order_sn' => $result['userOrderSn'],
                'transaction_id' => Lib::getMs(),
                'balance' => $money,
                'userOrderSn' => $result['userOrderSn'],
                'sysOrderSn' => $result['sysOrderSn'],
                'status' => 1,
                'is_pay' => 1,
                'intatus' => 1,
                'create_time' => Lib::getMs(),
            ];
            if($result['error'] == 0 && $result['status'] == 'SUCCESS') {
                $this->M()->myLog('rq.txt',json_encode($billData)."\n");
                $dbHandle->insert('bill',$billData);
                //调用还款记账
                $this->M()->hkSharing($valRds1->appid,$valRds1->id,$billData);
                //删除redis或临时表中的数据
                $redis->zRem('rQuery',$data[0]);
                $redis->hdel('rc_plan_data',$valRds1->appid.'_'.$valRds1->id);
                //同步账单信息到oem总数据库
                $billData['appid'] = $valRds1->appid;
                $this->M()->insert('bill',$billData);

                //将推送数据转到redis实现轮询推送
                /**/
                $pushData = [
                    'content' => "卡号(".Lib::aesDecrypt($valRds1->card_no).")还款人民币".$valRds1->amount."元,计划成功执行",
                    'deviceid' => $valRds1->user_id,
                    'platform' => 'all',
                    'appid' => $valRds1->appid
                ];
                $rsdRet = $redis->zAdd('jpush_repayment',time(), json_encode($pushData));
                $this->M()->myLog('rq.txt','rds:'.$rsdRet."\n");
                echo "<p>执行完成,状态为成功！</p>";
            }elseif($result['error'] == 0 && $result['status'] == 'PROCESSING'){
                //这里应该，将时间加3-5分钟
                /*
                 * zRem() 删掉原来的数据
                 * zAdd() 加时间后再插入
                 */
                echo "<p>状态未查到,继续轮询</p>";
            }elseif($result['error'] == 0 && $result['status'] == 'FAILURE'){
                //构造账单数据
                $billData['status'] = -1;
                $billData['is_pay'] = -1;
                $billData['intatus'] = -1;
                //记账，计入bill表
                $dbHandle->insert('bill',$billData);
                //将计划详情状态改为失败
                $dbHandle->update('plan_list',['userOrderSn'=>$result['userOrderSn'],'sysOrderSn'=>$result['sysOrderSn'],'status'=>6,'end_time'=>time()],['id'=>$valRds1->id]);
                //删除redis或临时表中的数据
                $redis->zRem('rQuery',$data[0]);
                $redis->hdel('rc_plan_data',$valRds1->appid.'_'.$valRds1->id);
                //删除redis中数据
                $data = $dbHandle->select('plan_list', '*', ['plan_id'=>$valRds1->plan_id,'user_id'=>$valRds1->user_id]);
                foreach($data as $k => $v){
                    $redis->hdel('rc_plan_data',$valRds1->appid.'_'.$v['id']);
                }
                //同步账单信息到oem总数据库
                $billData['appid'] = $valRds1->appid;
                $this->M()->insert('bill',$billData);
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
            $payHandle = new \App\WWW\Model\Paf($valRds1->appid);
            $result = $payHandle->payDsQuery($post);
            $this->M()->myLog('cq.txt',json_encode($result)."\n");
            //构造公共的账单数据
            $billData = [
                'user_id' => $valRds1->user_id,
                'plan_id' => $valRds1->plan_id,
                'amount' => $valRds1->amount,
                'bill_type' => 2,
                'card_type' => 1,
                'bank_name' => $valRds1->bank_name,
                'card_no' => $valRds1->card_no,
                'bank_id' => $valRds1->bank_id,
                'poundage' => $payHandle->Conf['money_out_poundage'],
                'channel' => 2,
                'order_sn' => $valRds1->order_sn,
                'userOrderSn' => empty($result['userOrderSn']) ? '' : $result['userOrderSn'],
                'sysOrderSn' => empty($result['sysOrderSn'])? '' : $result['sysOrderSn'],
                'transaction_id' => Lib::getMs(),
                'status' => 1,
                'is_pay' => 1,
                'intatus' => 1,
                'create_time' => Lib::getMs(),
            ];
            if($result['error'] == 0 && $result['status'] == 'SUCCESS') {
                $dbHandle->insert('bill', $billData);
                //更新还款计划详情表中的状态为还款中
                $dbHandle->update('plan_list',['status'=>3],['id'=>$valRds1->id]);
                //删除redis或临时表中的数据
                $redis->zRem('cQuery',$data[0]);

                /***************************************************************/
                //扣除消费笔数费
                $insertDataUser = [
                    'amount' => (float)($payHandle->Conf['money_out_poundage'])*(-1),
                    'user_id' => $valRds1->user_id,
                    'desciption' => '消费手续费',
                    'order_sn' => $billData['userOrderSn'],
                    'is_pay'      => 1, //-1未支付，1已支付
                    'status'      => 1, //-2锁定
                    'in_type' => 1,
                    'channel' => 2,
                    'create_time'=> Lib::getMs()
                ];
                $dbHandle->insert('user_account',$insertDataUser);
                /***************************************************************/

                //同步账单信息到oem总数据库
                $billData['appid'] = $valRds1->appid;
                $this->M()->insert('bill',$billData);
                echo "<p>执行完成,状态为成功</p>";
            }elseif($result['error'] == 0 && $result['status'] == 'PROCESSING'){
                echo "<p>状态未查到,继续轮询</p>";
            }elseif($result['error'] == 0 && $result['status'] == 'FAILURE'){
                $billData['status'] = -1;
                $billData['is_pay'] = -1;
                $billData['intatus'] = -1;
                $dbHandle->insert('bill', $billData);
                $redis->zRem('cQuery',$data[0]);
                //删除redis中数据
                $data = $dbHandle->select('plan_list', '*', ['plan_id'=>$valRds1->plan_id,'user_id'=>$valRds1->user_id]);
                foreach($data as $k => $v){
                    $redis->hdel('rc_plan_data',$valRds1->appid.'_'.$v['id']);
                }
                //同步账单信息到oem总数据库
                $billData['appid'] = $valRds1->appid;
                $this->M()->insert('bill',$billData);
                //余额平账
                $pingRet = $this->M()->pingZh($valRds1->appid,$valRds1->user_id,$valRds1->id,$billData);
                if($pingRet){
                    echo "<p>余额平账完成</p>";
                }else{
                    echo "<p>余额平账失败</p>";
                }
            }
        }else{
            echo "<p>没有需要执行的任务</p>";
        }
    }

    //消费异步通知
    public function notifyConsume(){
        $userOrderSn = Lib::request('userOrderSn');
        $sysOrderSn = Lib::request('sysOrderSn');
        $attach = Lib::request('attach');
        $attachData = explode('|',$attach);
        $this->M()->myLog('c_notice.txt',$attach.":".$sysOrderSn.":".$userOrderSn."\n");
        die('success');
        $dbHandle = $this->M()->getDb($attachData[1]);
        //判断订单是否存在，存在说明已经处理过了
        $billRrow = $dbHandle->has('bill',['bill_type'=>2,'userOrderSn'=>$userOrderSn,'sysOrderSn'=>$sysOrderSn]);
        if($billRrow){
            $this->M()->myLog('c_notice.txt',":同步已经抢先处理了 \n",FILE_APPEND);
            die('success');
        }
        $row = $dbHandle->get('plan_list','*',['id'=>$attachData[0]]);
        if($userOrderSn && $row) {
            $billData = [
                'user_id' => $row['user_id'],
                'plan_id' => $row['plan_id'],
                'amount' => $row['amount'],
                'bill_type' => 2,
                'card_type' => 1,
                'bank_name' => $row['bank_name'],
                'card_no' => $row['card_no'],
                'bank_id' => $row['bank_id'],
                'poundage' => 1,
                'channel' => 2,
                'order_sn' => $row['order_sn'],
                'userOrderSn' => $userOrderSn,
                'sysOrderSn' => $sysOrderSn,
                'transaction_id' => Lib::getMs(),
                'status' => 1,
                'is_pay' => 1,
                'intatus' => 1,
                'create_time' => Lib::getMs(),
            ];
            $dbHandle->insert('bill', $billData);
            //更新还款计划详情表中的状态为已完成
            $dbHandle->update('plan_list',['status'=>3],['id'=>$row['id']]);
            /***************************************************************/

            //同步账单信息到oem总数据库
            $billData['appid'] = $attachData[1];
            $this->M()->insert('bill',$billData);
            echo "success";
        }
    }

}




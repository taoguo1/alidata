<?php
namespace App\WWW\Controller;
use Core\Base\Controller;
use Core\Extend\Redis;
use Core\Lib;

class CronPlan extends Controller
{
    public function index(){}

    /*
     * 还款，$task_no 任务编号
     */
    public function repayment($task_no)
    {
        //获取redis实例
        $redis = Redis::instance('plan');
        //查找到满足可以立即执行的记录
        $data = $redis->zRangeByScore('rc:'.$task_no,'-inf',time(),['withscores'=>false,'limit'=>[0,1]]);
        //如果有数据进入执行还款
        if($data){
            //将数据解析为对象
            $valRds1 =  json_decode($data[0]);
            $valRds2 =  json_decode($data[0],true);
            if(!$valRds1){ die('redis数据解析失败'); }
            //if($valRds1->id != 7408){ die('not'); }
            $dat = $redis->hGet('rc_plan_data',$valRds1->appid.'_'.$valRds1->id);
            if(!$dat){
                $redis->zRem('rc:'.$task_no,$data[0]);
                die('redis关联数据不存在');
            }
            //从模型中获取当前记录中对应appid的数据库操作句柄
            $dbHandle = $this->M()->getDb($valRds1->appid);
            //从计划详情表中读取对应的记录
            $rcd = $dbHandle->get('plan_list','*',['id'=>$valRds1->id]);
            if(!$rcd){
                $redis->zRem('rc:'.$task_no,$data[0]);
                $redis->hdel('rc_plan_data',$valRds1->appid.'_'.$valRds1->id);
                $log = ['task_no'=>$task_no,'id'=>$valRds1->id,'plan_id'=>$valRds1->id,'appid'=>$valRds1->appid];
                $this->M()->myLog('hk_exception.txt',json_encode($log)."还款计划记录不存在！\n");
                die('还款计划记录不存在！');
            }elseif($rcd['status'] == 6 || $rcd['status'] == 3){
                $redis->zRem('rc:'.$task_no,$data[0]);
                $redis->hdel('rc_plan_data',$valRds1->appid.'_'.$valRds1->id);
                $log = ['task_no'=>$task_no,'id'=>$valRds1->id,'plan_id'=>$valRds1->id,'appid'=>$valRds1->appid];
                $this->M()->myLog('hk_exception.txt',json_encode($log)."不能重复处理同一条结束的还款计划记录！\n");
                die('不能重复处理同一条结束的还款计划记录！');
            }
            //将当前记录状态改为进行中
            $dbHandle->update('plan_list',['status'=>2],['id'=>$valRds1->id]);

            /*请求代付接口开始****************************************************************************************/
            //创建订单号
            $order_sn = Lib::createOrderNo();
            //实例化代收代付公共模型
            $payHandle = new \App\WWW\Model\Paf($valRds1->appid);
            //设置费率,请求代付，代收接口前需要设置费率，以和D支付平台费率保持一致
            $ratePost = [
                'userCode'  => $valRds1->userCode,
                'czValue'  => $payHandle->Conf['deposit_poundage'],             //充值手续费
                'txValue'  => $payHandle->Conf['withdraw_poundage'],            //提现手续费
                'xfValue'  => $payHandle->Conf['money_out_poundage'],           //消费手续费
                'jqValue'  => $payHandle->Conf['validatecard_poundage'],        //鉴权手续费
                'hkValue'  => $payHandle->Conf['repayment_poundage'],           //还款手续费
            ];
            $rateResult = $payHandle->paySetRate($ratePost);
            //计算当前记录还款所需的手续费

            //手续费保留两位小数
            //$poundage = sprintf('%0.2f',$valRds1->amount *  ($payHandle->Conf['repayment_poundage'] / 10000));
            //手续费保留两位小数，四舍五入
            //$poundage = round($valRds1->amount *  ($payHandle->Conf['repayment_poundage'] / 10000),2);
            //笔数费
            $hkSingle = HK_SINGLE * $valRds1->re_times;
            //构造请求所需的数据
            $post = array(
                'userCode'  => $valRds1->userCode,
                'cardType'  => 2,
                'orderType'  => 'H',
                'sysId'  => $valRds1->sysId,
                'amount'  => $valRds1->amount * 100,
                'poundage'  => ($hkSingle * 100),
                'poundage_branch' => ($hkSingle * 100),
                'userOrderSn'  => $order_sn,
            );
            //执行请求方法并接收到结果，后续根据结果来处理
            $result = $payHandle->payDf($post);
            $this->M()->myLog('r.txt','df->appid:'.$valRds1->appid.'|plan_id:'.$valRds1->plan_id.'|id:'.$valRds1->id.'|user_id:'.$valRds1->user_id.'||result:'.json_encode($result)."\n");
            /*请求代付接口结束****************************************************************************************/

            //删除redis或临时表中的数据
            $redis->zRem('rc:'.$task_no,$data[0]);
            //$redis->hdel('rc_plan_data',$valRds1->appid.'_'.$valRds1->id);
            if($result['error'] == 1 && empty($result['status'])){
                //子商户余额不足,交易失败，让当前计划暂停，通知管理员处理
                $data = $dbHandle->select('plan_list', '*', ['plan_id'=>$valRds1->plan_id,'user_id'=>$valRds1->user_id]);
                foreach($data as $k => $v){
                    $redis->hdel('rc_plan_data',$valRds1->appid.'_'.$v['id']);
                }
            }
            //基本不会直接接到状态为FAILURE
            //if($result['error'] == 1 && $result['status'] == 'FAILURE'){
            if($result['error'] == 1){
                //构造账单数据
                $billData = [
                    'user_id' => $valRds1->user_id,
                    'plan_id' => $valRds1->plan_id,
                    'amount' => $valRds1->amount,
                    'bill_type' => 1,
                    'card_type' => 1,
                    'bank_name' => $valRds1->bank_name,
                    'card_no' => $valRds1->card_no,
                    'bank_id' => $valRds1->user_id,
                    'poundage' => $hkSingle,
                    'channel' => 2,
                    'order_sn' => $order_sn,
                    'transaction_id' => Lib::getMs(),
                    'userOrderSn' => empty($result['userOrderSn']) ? '' : $result['userOrderSn'],
                    'sysOrderSn' => empty($result['sysOrderSn'])? '' : $result['sysOrderSn'],
                    'status' => -1,
                    'is_pay' => -1,
                    'intatus' => 1,
                    'create_time' => Lib::getMs(),
                ];
                //记账，计入bill表
                $dbHandle->insert('bill',$billData);
                //将计划详情状态改为失败
                $dbHandle->update('plan_list',['userOrderSn'=>$order_sn,'sysOrderSn'=>$result['sysOrderSn'],'status'=>6,'end_time'=>time()],['id'=>$valRds1->id]);
                //交易失败，让当前计划暂停，通知管理员处理
                $data = $dbHandle->select('plan_list', '*', ['plan_id'=>$valRds1->plan_id,'user_id'=>$valRds1->user_id]);
                foreach($data as $k => $v){
                    $redis->hdel('rc_plan_data',$valRds1->appid.'_'.$v['id']);
                }
                //同步账单信息到oem总数据库
                $billData['appid'] = $valRds1->appid;
                $this->M()->insert('bill',$billData);
                echo "<p>还款结束，状态为失败</p>";
            }elseif($result['error'] == 0 && $result['status'] == 'SUCCESS'){
                $record = $valRds2;
                $record['userOrderSn'] = $result['userOrderSn'];
                $record['sysOrderSn'] = $result['sysOrderSn'];
                $redis->zAdd('rQuery',time() + 300,json_encode($record));
                echo "<p>还款结束需要查询</p>";
            }elseif($result['error'] == 0 && $result['status'] == 'PROCESSING'){
                $record = $valRds2;
                $record['userOrderSn'] = $result['userOrderSn'];
                $record['sysOrderSn'] = $result['sysOrderSn'];
                $redis->zAdd('rQuery',time() + 300,json_encode($record));
                echo "<p>还款结束需要查询</p>";
            }
            echo "<p>执行完成</p>";
        }else{
            echo "<p>没有需要执行的任务</p>";
        }
    }
    /*
     * 消费 $task_no 任务编号
     */
    public function consume($task_no)
    {   
        //获取redis实例     
        $redis = Redis::instance('plan');
        //查找到满足立即执行的记录
        $data = $redis->zRangeByScore('rc:'.$task_no,'-inf',time(),['withscores'=>false,'limit'=>[0,1]]);

        //如果有数据进入执行消费
        if($data){
            //将数据解析为对象
            $valRds1 =  json_decode($data[0]);
            $valRds2 =  json_decode($data[0],true);
            if(!$valRds1){ die('redis数据解析失败'); }
            //if($valRds1->id != 7410){ die('not'); }
            $dat = $redis->hGet('rc_plan_data',$valRds1->appid.'_'.$valRds1->id);
            if(!$dat){
                $redis->zRem('rc:'.$task_no,$data[0]);
                die('redis关联数据不存在');
            }
            //从模型中获取当前记录中对应appid的数据库操作句柄
            $dbHandle = $this->M()->getDb($valRds1->appid);
            //从计划详情表中读取对应的记录
            $rcd = $dbHandle->get('plan_list','*',['id'=>$valRds1->id]);
            if(!$rcd){
                $redis->zRem('rc:'.$task_no,$data[0]);
                $redis->hdel('rc_plan_data',$valRds1->appid.'_'.$valRds1->id);
                $log = ['task_no'=>$task_no,'id'=>$valRds1->id,'plan_id'=>$valRds1->plan_id,'appid'=>$valRds1->appid];
                $this->M()->myLog('xf_exception.txt',json_encode($log)."消费计划记录不存在！\n");
                die('消费计划记录不存在！');
            }elseif($rcd['status'] == 6 || $rcd['status'] == 3){
                $redis->zRem('rc:'.$task_no,$data[0]);
                $redis->hdel('rc_plan_data',$valRds1->appid.'_'.$valRds1->id);
                $log = ['task_no'=>$task_no,'id'=>$valRds1->id,'plan_id'=>$valRds1->plan_id,'appid'=>$valRds1->appid];
                $this->M()->myLog('xf_exception.txt',json_encode($log)."不能重复处理同一条结束的消费计划记录！\n");
                die('不能重复处理同一条结束的消费计划记录！');
            }

            /*检查还款是否执行成功*/
            $r = $dbHandle->get('plan_list',['id','status'],['plan_no'=>$valRds1->plan_no,'plan_id'=>$valRds1->plan_id,'plan_type'=>1]);
            if(empty($r) || $r['status'] != 3){
                $redis->zRem('rc:'.$task_no,$data[0]);
                //status=2表示在还款中可以将消费时间延迟
                /*
                 * 正常情况下消费时间不需要延迟因为，本身有时间间隔
                 * 如果特殊情况下，比如计划任务临时停止数小时，积攒了很多数据，此时启动计划任务时，
                 * 因为还款和消费都达到了立即执行的条件这个时候需要消费延迟，等待还款成功
                if($r['status'] == 2){
                    $redis->zAdd('rc:'.$task_no,time()+600,$data[0]);
                }else{
                    $redis->hdel('rc_plan_data',$valRds1->appid.'_'.$valRds1->id);
                }
                */
                //删除管理数据
                $redis->hdel('rc_plan_data',$valRds1->appid.'_'.$valRds1->id);
                //记录日志
                $log = ['task_no'=>$task_no,'id'=>$valRds1->id,'plan_id'=>$valRds1->plan_id,'appid'=>$valRds1->appid];
                $this->M()->myLog('xf_exception.txt',json_encode($log)."前序还款任务未完成或已失败！\n");
                die('前序还款任务未完成或已失败！');
            }

            //将当前记录状态改为进行中
            $dbHandle->update('plan_list',['status'=>2],['id'=>$valRds1->id]);
   
            /*请求代收接口开始****************************************************************************************/
            //创建订单号
            $order_sn = Lib::createOrderNo();
            $payHandle = new \App\WWW\Model\Paf($valRds1->appid);
            //设置费率,请求代付，代收接口前需要设置费率，以和D支付平台费率保持一致

            $ratePost = [
                'userCode'  => $valRds1->userCode,
                'czValue'  => $payHandle->Conf['deposit_poundage'],             //充值手续费
                'txValue'  => $payHandle->Conf['withdraw_poundage'],            //提现手续费
                'xfValue'  => $payHandle->Conf['money_out_poundage'],           //消费手续费
                'jqValue'  => $payHandle->Conf['validatecard_poundage'],        //鉴权手续费
                'hkValue'  => $payHandle->Conf['repayment_poundage'],           //还款手续费
            ];
            $rateResult = $payHandle->paySetRate($ratePost);
            $attach_data = $valRds1->id."|".$valRds1->appid;
            //手续费，按原还款手续比例收取
            //$poundage = sprintf('%0.2f',$valRds1->amount * ($ratePost['hkValue'] / 10000));
            $poundage = floor(($valRds1->amount * ($ratePost['hkValue'] / 10000)) * 100) / 100;
            $branchRate = $payHandle->Conf['oem_repayment_poundage'];
            $poundage_branch = floor(($valRds1->amount * ($branchRate / 10000)) * 100) / 100;
            $post = array(
                'userCode'  => $valRds1->userCode,
                'cardType'  => 2,
                'orderType'  => 'X',
                'sysId'  => $valRds1->sysId,
                'amount'  => $valRds1->amount * 100,
				'poundage' => $poundage * 100,
                'poundage_branch' => $poundage_branch * 100,
                'userOrderSn'  => $order_sn,
                'notifyUrl' => OEM_CTRL_URL.'CronPlanQuery/notifyConsume',
                'attach' => $attach_data,
            );
            $result = $payHandle->payDs($post);
            /*请求代收接口结束****************************************************************************************/

            //写入日志
            $this->M()->myLog('c.txt','ds->appid:'.$valRds1->appid.'|plan_id:'.$valRds1->plan_id.'|id:'.$valRds1->id.'|user_id:'.$valRds1->user_id.'||result:'.json_encode($result)."\n");
            //直接返回交易失败，没有返回状态时
            //四. error = 1, status = ""
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
                'poundage' => $poundage,
                'channel' => 2,
                'order_sn' => $order_sn,
                'transaction_id' => Lib::getMs(),
                'userOrderSn' => empty($result['userOrderSn']) ? '' : $result['userOrderSn'],
                'sysOrderSn' => empty($result['sysOrderSn'])? '' : $result['sysOrderSn'],
                'status' => 1,
                'is_pay' => 1,
                'intatus' => 1,
                'create_time' => Lib::getMs(),
            ];
            if($result['error'] == 1) {
                $billData['status'] = -1;
                $billData['is_pay'] = -1;
                $billData['intatus'] = -1;
                $dbHandle->insert('bill', $billData);
                $redis->zRem('rc:'.$task_no,$data[0]);
                //删除redis中数据
                $data = $dbHandle->select('plan_list', '*', ['plan_id'=>$valRds1->plan_id,'user_id'=>$valRds1->user_id]);
                foreach($data as $k => $v){
                    $redis->hdel('rc_plan_data',$valRds1->appid.'_'.$v['id']);
                }
                $this->M()->myLog('jcpz.txt',"进入error=1,\n");
                //同步账单信息到oem总数据库
                $billData['appid'] = $valRds1->appid;
                $this->M()->insert('bill',$billData);
                //余额平账
                $pingRet = $this->M()->pingZh($valRds1->appid,$valRds1->user_id,$valRds1->plan_id,$billData);
                $this->M()->myLog('jcpz.txt',"进入error=1,pingRet:".$pingRet."\n");
                if($pingRet == 1){
                    echo "<p>余额平账完成</p>";
                }else{
                    echo "<p>余额平账失败</p>";
                }
                die('结束...');
            }
            //二. error = 0, status = "SUCCESS"
            if($result['error'] == 0 && $result['status'] == 'SUCCESS') {
                $dbHandle->insert('bill', $billData);
                //更新还款计划详情表中的状态为还款中
                $dbHandle->update('plan_list',['status'=>3,'end_time'=>time()],['id'=>$valRds1->id]);
                //删除redis或临时表中的数据
                $redis->zRem('rc:'.$task_no,$data[0]);
                $redis->hdel('rc_plan_data',$valRds1->appid.'_'.$valRds1->id);
                /***************************************************************/
                //扣除消费笔数费
                /*
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
                */
                /***************************************************************/
                //调用分润
                $this->M()->xfSharing($valRds1->appid,$valRds1->id,$billData);
                //同步账单信息到oem总数据库
                $billData['appid'] = $valRds1->appid;
                $this->M()->insert('bill',$billData);

                //将推送数据转到redis实现轮询推送
                /**/
                $pushData = [
                    'content' => "卡号(".Lib::aesDecrypt($valRds1->card_no).")消费人民币".$valRds1->amount."元,计划成功执行",
                    'deviceid' => $valRds1->user_id,
                    'platform' => 'all',
                    'appid' => $valRds1->appid
                ];
                $rdsRet =$redis->zAdd('jpush_consume',time(), json_encode($pushData));
                $this->M()->myLog('c.txt','jpush:'.$rdsRet ."\n");

                //将plan表状态修改为已完成（status=3）
                $maxRs = $dbHandle->get('plan_list',['id'],['plan_id'=>$valRds1->plan_id,"ORDER" => ["id" => "DESC"]]);
                $this->M()->myLog('planStatus.txt',"maxRs:".json_encode($maxRs) .",appid:".$valRds1->appid.",plan_id:".$valRds1->plan_id."\n");
                //后期用auto_excute_num直接判断
                if($maxRs['id'] == $valRds1->id){
                    //修改主表状态
                    $dbHandle->update('plan',['status'=>3,'finish_time'=>time()],['id'=>$valRds1->plan_id]);
                    $this->M()->myLog('planStatus.txt',"update:1 \n ------------------------------------------------- \n");
                }

            }elseif($result['error'] == 0 && $result['status'] == 'PROCESSING'){
                $record = $valRds2;
                $record['userOrderSn'] = $result['userOrderSn'];
                $record['sysOrderSn'] = $result['sysOrderSn'];
                //删除redis
                $redis->zRem('rc:'.$task_no,$data[0]);
                $redis->hdel('rc_plan_data',$valRds1->appid.'_'.$valRds1->id);
                $redis->zAdd('cQuery',time() + 90,json_encode($record));
                echo "<p>消费完成结束,需要查询</p>";
            }elseif($result['error'] == 1 && $result['status'] == 'FAILURE'){
                /*
                $billData['status'] = -1;
                $billData['is_pay'] = -1;
                $billData['intatus'] = -1;
                $dbHandle->insert('bill', $billData);
                $redis->zRem('rc:'.$task_no,$data[0]);
                //删除redis中数据
                $data = $dbHandle->select('plan_list', '*', ['plan_id'=>$valRds1->plan_id,'user_id'=>$valRds1->user_id]);
                foreach($data as $k => $v){
                    $redis = Redis::instance('plan');
                    $redis->hdel('rc_plan_data',$valRds1->appid.'_'.$v['id']);
                }
                //同步账单信息到oem总数据库
                $billData['appid'] = $valRds1->appid;
                $this->M()->insert('bill',$billData);
                //余额平账
                $pingRet = $this->M()->pingZh($valRds1->appid,$valRds1->user_id,$valRds1->plan_id,$billData);
                if($pingRet){
                    echo "<p>余额平账完成</p>";
                }else{
                    echo "<p>余额平账失败</p>";
                }
                */
            }
            echo "<p>执行完成</p>";
        }else{
            echo "<p>没有需要执行的任务</p>";
        }
    }


}


?>
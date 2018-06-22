<?php
namespace App\WWW\Controller;
use Core\Base\Controller;
use Core\Extend\Redis;
use Core\Lib;


class WithDrawQuery extends Controller
{
    public function __construct($controller, $action)
    {
        parent::__construct($controller, $action);
    }
    /*
     * 提现状态查询
     */
    public function index()
    {
        $redis = Redis::instance('plan');
        //查找到已经提现的记录
        $data = $redis->zRangeByScore('wQuery','-inf','inf',['withscores'=>false,'limit'=>[0,1]]);
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
            $result = $payHandle->payDf($post);
            $poundage = sprintf('%0.2f',$valRds1->amount *  ($payHandle->Conf['repayment_poundage'] / 10000));
            if($result['error'] == 0) {
                //记账，计入bill表
                $billData1 = [
                    'user_id' => $valRds1->user_id,
                    'plan_id' => $valRds1->plan_id,
                    'amount' => $valRds1->amount,
                    'bill_type' => 3,
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
                echo "<p>还款结束，状态为失败</p>";
            }
            echo "<p>执行完成</p>";
        }else{
            echo "<p>没有需要执行的任务</p>";
        }
    }

    


}




<?php

namespace App\WWW\Controller;

use Core\Base\Controller;
use Core\Extend\Redis;
use Core\Lib;

class CheckPlan extends Controller
{
    /*
     * 计划创建后24小时内检测合法性，合法性包括以下：
     * 1.银行消费限额，单笔 499,999，日 < 1500,< 10000
     * 2.消费次数2-5次，光大必须小于3次
     * 3.每天还款金额与消费金额必须吻合
     * 4.总还款金额，与总还款金额保持相等，且总还款金额等于计划金额
     * 4.还款天数范围为5-25天
     */
    public function index()
    {
        $redis = Redis::instance('plan');
        $data = $redis->zRangeByScore('CheckPlanTable', '-inf', 'inf', ['withscores' => false, 'limit' => [0, 1]]);
        if ($data) {
            $valRds = json_decode($data[0]);
            $dbHandle = $this->M()->getDb($valRds->appid);
            $list = $dbHandle->select('plan_list', ['id', 'amount', 'plan_type', 'plan_no'], ['plan_id' => $valRds->plan_id]);
            $newListPre = $newList = [];
            $allHkAmount = $allXfAmount = 0;
            $time = 0;
            foreach ($list as $value) {
                if ($value['plan_type'] == 1) {
                    $allHkAmount += $value['amount'];
                }
                if ($value['plan_type'] == 2) {
                    $allXfAmount += $value['amount'];
                }
                if ($time == $value['plan_no']) {
                    $newListPre[$value['plan_no']][] = $value;
                    $time++;
                }
            }

            foreach ($newListPre as $key => $val) {
                foreach ($list as $v) {
                    if ($v['plan_no'] == $key) {
                        if ($v['plan_type'] != 1)
                            $val[0]['sub'][] = $v;
                    }
                    $val[0]['consumeAmount'] = array_sum(array_map(function ($val) {
                        return $val['amount'];
                    }, $val[0]['sub']));
                }
                $newList[] = $val;
            }

            if ((int)$allHkAmount != (int)$allXfAmount) {
                //发邮件
                $this->M()->myLog('checkPlanErr.txt', "plan:" . json_encode($data) . "\n");
                $redis->zRem('CheckPlanTable', $data[0]);
                die('总金额异常');
            }
            foreach ($newList as $val) {
                if ((int)$val['consumeAmount'] != (int)$val['amount']) {
                    //发邮件
                    $this->M()->myLog('checkPlanErr.txt', "plan:" . json_encode($data) . "\n" . "day:" . json_encode($val));
                    $redis->zRem('CheckPlanTable', $data[0]);
                    die('每天金额异常');
                }
            }
            $redis->zRem('CheckPlanTable', $data[0]);
            echo "<p>执行结束,无异常</p>";
        } else {
            echo "<p>没有数据</p>";
        }
    }

    /**
     *
     */
    public function channel()
    {

    }


}




<?php
/**
 * Created by jixiang.
 * User: pc
 * Date: 2018/4/24
 * Time: 14:02
 */
namespace App\CALL\Model;
use Core\Lib;


class GatherNotice extends Gdb
{
    //套现分润公共方法
    public function txSharing($appid,$row){
        $dbHandle = $this->getDb($appid);
        $userRs = $dbHandle->get('user',['id','agent_id'],['id'=>$row['user_id']]);
        //echo "获取用户资料<br>";
        $agentRs = $dbHandle->get(
            'agent (A)',
            [
                '[>]agent_ext (B)' => [
                    'A.id' => 'agent_id'
                ]
            ],
            ['A.id','A.pid','A.rate','A.skrate','B.userCode'],
            ['A.id'=>$userRs['agent_id']]
        );
        //获取所有上级代理
        if($agentRs['pid'] >= 0){
            $agentList = $this->getAgent($agentRs['pid'],$appid);
            if($agentList){
                //合并数组
                array_unshift($agentList,$agentRs);
            }else{
                $agentList = $agentRs;
            }
        }else{
            $agentList = $agentRs;
        }

        //return $agentList;
        //实例化代收代付公共模型
        $payHandle = new Paf($appid);
        //记录上级分润比例
        $this->myLog('fr.txt',json_encode($agentList));
        $r = 0;
        foreach($agentList as $k => $val){
            if($r == 0){
                $amount = $row['amount'] * ($val['skrate']/10000);
            }else{
                $amount = $row['amount'] * (($val['skrate'] - $r) / 10000);
            }
            $r = $val['skrate'];
            $this->myLog('fr.txt','amount:'.$amount.':'.'r:'.$r);
            //插入代理账户表（记账）
            $insertData = [
                'amount' => $amount,
                'agent_id' => $val['id'],
                'description' => '套现分润',
                'order_sn' => $row['userOrderSn'],
                'in_type' => 1,
                'is_pay' => 1,
                'channel' => 2,
                'create_time'=> Lib::getMs()
            ];
            $this->myLog('fr.txt',json_encode($insertData));
            $dbHandle->insert('agent_account',$insertData);
            //大商户给代理子商户转账
            /**/
            $post = array(
                'userCode'  => $val['userCode'],
                'amount'  => $amount * 100,
                'remark'  => '套现分润',
            );
            $result = $payHandle->payTrans($post);
            $log = [
                'appid' => $appid,
                'agent_id' => $val['id'],
                'type' => 11,
                'amount' => $amount,
                'rate' => $val['rate'] - $r,
                'plan_id' => 0,
                'plan_list_id' => 0,
                'create_time' => time(),
            ];
            if($result['error'] == 0) {
                $log['status'] = 1;
                $this->db->insert('trans_tx_log',$log);
                $this->myLog('agent.txt', "appid:" . $appid . ",agent_id:" . $val['id'] . ",result:".json_encode($result)."\n"."row:".json_encode($row)." \n");
            }else{
                $log['status'] = -1;
                $this->db->insert('trans_tx_log',$log);
                $this->myLog('agent_err.txt', "appid:" . $appid . ",agent_id:" . $val['id'] . ",result:".json_encode($result)."\n"."row:".json_encode($row)." \n");
            }
        }
        return 1;
    }

}
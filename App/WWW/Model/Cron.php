<?php
namespace App\WWW\Model;
use Core\Base\Model;
use Core\DB\DB;
use PDO;
use Core\Lib;

class Cron extends Model
{
    public $dbOem;
    public $Conf;
    //获取配置
    public function getConf($appid){
        if(!$appid){
            exit("参数错误");
        }
        $postData = [
            'appid' => $appid,
            'version'=> OEM_CTRL_URL_VERSION
        ];
        $ret = Lib::httpPostUrlEncode(OEM_CTRL_URL.'api/getConfig', $postData);
        $ret = json_decode($ret,true);
        $this->Conf = $ret;
        if(!$ret['status']=='fail')
        {
            exit($ret['msg']);
        }else{
            if(!$ret['status']==-1){
                exit("该账户异常");
            }
        }
        return $this->Conf;
    }
    //获取DB对象
    public function getDb($appid){
        $ret = $this->getConf($appid);
        $this->dbOem = new DB([
            'databaseType' => 'mysql',
            'databaseName' => $ret['db_name'],
            'server' => $ret['db_ip'],
            'userName' => $ret['db_user'],
            'password' => $ret['db_password'],
            'charSet' => 'utf8',
            'debugMode' => false,
            'logging' => true,
            'port' => $ret['db_port'],
            'prefix' => $ret['db_prefix'],
            'option' => [
                PDO::ATTR_CASE => PDO::CASE_NATURAL
            ],
            'command' => [
                'SET SQL_MODE=ANSI_QUOTES'
            ]
        ]);
        return $this->dbOem;
    }
    //余额平账
    public function pingZh($appid,$user_id,$plan_id,$row){
        $dbHandle = $this->getDb($appid);
        //判断当前结束的计划详情数据里是否存在本期已还款但未消费状态，如存在则强制平账，将已还款期的未消费金额直接写入user_account
        $planListInfo = $dbHandle->select('plan_list','*',['plan_id'=>$plan_id,'user_id'=>$user_id]);
        //获取还款天数、期数
        $duration = $dbHandle->get('plan','*',['id'=>$plan_id,'user_id'=>$user_id]);
        $count=[];
        $payInfo=[];
        $payId=[];
        //用plan_no字段将数据归类写入数组
        foreach ($planListInfo as $key => $value) {
            for($i=0;$i<$duration['duration'];$i++){
                if($value['plan_no']==$i){
                    $count[$i][]=$value;
                }
            }
        }
        foreach($count as $k=>$v){
            if($count[$k][0]['plan_type']==1&&($count[$k][0]['status']==3||$count[$k][0]['status']==5)){
                for($j=0;$j<count($count[$k]);$j++){
                    if($count[$k][$j]['plan_type']==2 && $count[$k][$j]['status']!=3){
                        $payInfo[]=$count[$k][$j]['amount'];
                        $payId[]=$count[$k][$j]['id'];
                    }
                }
            }
        }
        if(!empty($planListInfo)){
            //更新plan数据表
            $ret = $dbHandle->update('plan',['status'=>3,'finish_type'=>2],['id'=>$plan_id]);
            //更新plan_list表状态
            foreach($planListInfo as $k=>$v){
                if($v['status']!=3&&$v['status']!=5){
                    $dbHandle->update('plan_list',['status'=>6],['id'=>$v['id']]);
                }
            }
            if(!empty($payId)){
                for($s=0;$s<count($payId);$s++){
                    $dbHandle->update('plan_list',['status'=>6],['id'=>$payId[$s]]);
                }
            }
            if(!empty($payInfo)){
                //将未消费数据插入user_account数据表
                $allBalanced = 0;
                for($m=0;$m<count($payInfo);$m++){
                    $order_no = Lib::createOrderNo();
                    $allBalanced += $payInfo[$m];
                    $user_account = array(
                        'user_id' => $user_id,
                        'amount' => (float)($payInfo[$m])*(-1),
                        'order_sn' => $order_no,
                        'desciption' => '未消费金额平账',
                        'in_type' => 1,
                        'channel' => 1,  //1易联2易宝
                        'is_pay' => 1,   //-1未支付，1已支付
                        'status' => 1,   //-2锁定
                        'create_time' => Lib::getMs()
                    );
                    $dbHandle->insert('user_account', $user_account);
                }
                //构造账单数据
                $billData = [
                    'user_id' => $user_id,
                    'plan_id' => $plan_id,
                    'amount' => (float)($allBalanced),
                    'bill_type' => 6,
                    'bank_name' => $row['bank_name'],
                    'card_no' => $row['card_no'],
                    'bank_id' => $row['bank_id'],
                    'card_type' => 1,
                    'poundage' => 0,
                    'channel' => 2,
                    'order_sn' => Lib::createOrderNo(),
                    'transaction_id' => Lib::getMs(),
                    'status' => 1,
                    'is_pay' => -1,
                    'intatus' => 1,
                    'create_time' => Lib::getMs(),
                ];
                $this->myLog('pz.txt',json_encode($billData));
                //平账，计入bill表
                $dbHandle->insert('bill',$billData);
            }
            return 1;
        }else{
            return 0;
        }
    }
    //还款手续费记账
    public function hkSharing($appid,$id,$row){
        $dbHandle = $this->getDb($appid);
        $dbHandle->update('plan_list',['userOrderSn'=>$row['userOrderSn'],'sysOrderSn'=>$row['sysOrderSn'],'status'=>3,'end_time'=>time()],['id'=>$id]);
        //插入用户账户表（记账）
        $insertDataUser1 = [
            'amount' => (float)($row['poundage'])*(-1),
            'user_id' => $row['user_id'],
            'desciption' => '还款手续费',
            'order_sn' => $row['userOrderSn'],
            'is_pay'      => 1, //-1未支付，1已支付
            'status'      => 1, //-2锁定
            'in_type' => 1,
            'channel' => 2,
            'create_time'=> Lib::getMs()
        ];
        $dbHandle->insert('user_account',$insertDataUser1);
        return 1;
    }

    //消费分润
    public function xfSharing($appid,$id,$row){
        $dbHandle = $this->getDb($appid);
        $dbHandle->update('plan_list',['userOrderSn'=>$row['userOrderSn'],'sysOrderSn'=>$row['sysOrderSn'],'status'=>3,'end_time'=>time()],['id'=>$id]);
        //插入用户账户表（记账）
        $insertDataUser1 = [
            'amount' => (float)($row['poundage'])*(-1),
            'user_id' => $row['user_id'],
            'desciption' => '消费手续费',
            'order_sn' => $row['userOrderSn'],
            'is_pay'      => 1, //-1未支付，1已支付
            'status'      => 1, //-2锁定
            'in_type' => 1,
            'channel' => 2,
            'create_time'=> Lib::getMs()
        ];

        $dbHandle->insert('user_account',$insertDataUser1);
        $userRs = $dbHandle->get('user',['id','agent_id'],['id'=>$row['user_id']]);
        //echo "获取用户资料<br>";
        $agentRs = $dbHandle->get(
            'agent (A)',
            [
                '[>]agent_ext (B)' => [
                    'A.id' => 'agent_id'
                ]
            ],
            ['A.id','A.pid','A.rate','B.userCode'],
            ['A.id'=>$userRs['agent_id']]
        );
        //echo "获取代理资料<br>";
        //获取所有上级代理
        $agentList = $this->getAgent($agentRs['pid'],$appid);
        //合并数组
        array_unshift($agentList,$agentRs);
        //如果查不到代理提现则忽略处理

        //实例化代收代付公共模型
        $payHandle = new Paf($appid);
        //记录上级分润比例
        $r = 0;
        foreach($agentList as $k => $val){
            if($r == 0){
                //$amount = sprintf('%0.2f',$row['amount'] * ($val['rate']/10000));
                $amount = floor(($row['amount'] * ($val['rate'] / 10000)) * 100) / 100;
            }else{
                //$amount = sprintf('%0.2f',$row['amount'] * (($val['rate'] - $r) / 10000));
                $amount = floor(($row['amount'] * (($val['rate'] - $r) / 10000)) * 100) / 100;
            }
            $r = $val['rate'];

            //插入代理账户表（记账）
            $insertData = [
                'amount' => $amount,
                'agent_id' => $val['id'],
                'description' => '还款分润',
                'order_sn' => $row['userOrderSn'],
                'in_type' => 1,
                'is_pay' => 1,
                'channel' => 2,
                'create_time'=> Lib::getMs()
            ];
            $dbHandle->insert('agent_account',$insertData);
            //大商户给代理子商户转账
            /**/
            if($val['userCode']){
                $post = array(
                    'userCode'  => $val['userCode'],
                    'amount'  => $amount * 100,
                    'remark'  => '还款分润',
                );
                $result = $payHandle->payTrans($post);
                $log = [
                    'appid' => $appid,
                    'agent_id' => $val['id'],
                    'type' => 1,
                    'amount' => $amount,
                    'rate' => $val['rate'] - $r,
                    'plan_id' => $row['plan_id'],
                    'plan_list_id' => $id,
                    'create_time' => time(),
                ];
                if($result['error'] == 0) {
                    $log['status'] = 1;
                    $this->db->insert('trans_log',$log);
                    $this->myLog('agent.txt', "appid:" . $appid . ",agent_id:" . $val['id'] . ",result:".json_encode($result)."\n"."row:".json_encode($row)." \n");
                }else{
                    $log['status'] = -1;
                    $this->db->insert('trans_log',$log);
                    $this->myLog('agent_err.txt', "appid:" . $appid . ",agent_id:" . $val['id'] . ",result:".json_encode($result)."\n"."row:".json_encode($row)." \n");
                }
            }
        }
        return 1;
    }
    //获取代理树
    public function getAgent($pid,$appid,&$data=[]){
        $dbHandle = $this->getDb($appid);
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

    //写日志
    public function myLog($name,$content){
        $path = 'Logs/Plan/'.date('Y',time()).'/'.date('m',time()).'/'.date('d',time());
        if(!file_exists($path)){
            if(\mkdir($path,0777,true)){
                file_put_contents($path.'/'.date('H',time()).'-'.$name,json_encode($content)."\n",FILE_APPEND);
            }
        }else{
            file_put_contents($path.'/'.date('H',time()).'-'.$name,json_encode($content)."\n",FILE_APPEND);
        }
    }

    public function transLog(){
        $log = [
            'appid' => '8657aebf01cb3199',
            'agent_id' => 1,
            'type' => 1,
            'amount' => 2,
            'plan_id' => 3,
            'plan_list_id' => 201,
            'create_time' => time(),
        ];
        $result = ['error'=>1];
        if($result['error'] == 0) {
            $log['status'] = 1;
            $this->db->insert('trans_log',$log);
            return $this->db->error();
            $this->myLog('agent.txt', "result:".json_encode($result)."\n");
        }else{
            $log['status'] = -1;
            $this->db->insert('trans_log',$log);
            $this->myLog('agent_err.txt', "result:".json_encode($result)."\n");
            return $this->db->error();
        }
    }


}
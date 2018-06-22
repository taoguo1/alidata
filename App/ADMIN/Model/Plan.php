<?php
namespace App\ADMIN\Model;
use Core\Lib;
use Core\Base\Model;
use Core\DB\DBQ;
use App\WWW\Model\Cron;
class Plan extends Model
{

    public function getList($pageArr = null, $condition = null)
    {
        // $pageArr, $table, $join, $columns = null, $where = null
        $data = DBQ::pages($pageArr, 'plan (P)', [
            '[>]user (U)' => [
                'P.user_id' => 'id'
            ]
        ], [
            'P.id',
            'U.real_name',
            'P.amount',
            'P.card_no',
            'P.start_time',
            'P.end_time',
            'P.duration',
            'P.poundage',
            'P.finish_time',
            'P.finish_type',
            'P.status',
            'P.create_time'
        ], $condition);

        return $data;
    }

    public function add($data)
    {
        return DBQ::add('plan', $data);
    }

    public function del($id = 0)
    {
        return DBQ::del('plan', [
            'id' => $id
        ]);
    }

    public function delAll($ids)
    {
        return DBQ::del('plan', [
            'id' => $ids
        ]);
    }

    public function edit($id = 0, $data)
    {
        return DBQ::upd('plan', $data, [
            'id' => $id
        ]);
    }
	
	
	 //强制完成计划
    public function editPlan($user_id,$plan_id,$status,$appid){
    	$dbConfig2 =new Cron();
		$info2=$dbConfig2->getDb($appid);
        //平账数据
        //判断当前结束的计划详情数据里是否存在本期已还款但未消费状态，如存在则强制平账，将已还款期的未消费金额直接写入user_account
        $planListInfo = $info2->select('plan_list','*',['plan_id'=>$plan_id,'user_id'=>$user_id]);
        //获取还款天数、期数
        $duration = $info2->get('plan','*',['id'=>$plan_id,'user_id'=>$user_id]);
		if($duration['finish_type']==2||$duration['status']==3){
			return 0;
		}
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
            $ret = $info2->update('plan',['status'=>$status,'finish_type'=>2],['id'=>$plan_id]);
            //更新plan_list表状态
            foreach($planListInfo as $k=>$v){
                if($v['status']!=3&&$v['status']!=5){
                    $info2->update('plan_list',['status'=>6],['id'=>$v['id']]);
                }
            }
            if(!empty($payId)){
                for($s=0;$s<count($payId);$s++){
                    $info2->update('plan_list',['status'=>6],['id'=>$payId[$s]]);
                }
            }
            if(!empty($payInfo)){
                //将未消费数据插入user_account数据表
                //金额合计
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
                        'is_pay' => 1, //-1未支付，1已支付
                        'status' => 1, //-2锁定
                        'create_time' => Lib::getMs()
                    );
                    $info2->insert('user_account', $user_account);
                }
                //构造账单数据
                $billData1 = [
                    'user_id' => $user_id,
                    'plan_id' => $plan_id,
                    'amount' => (float)($allBalanced),
                    'bill_type' => 6,
                    'card_type' => 1,
                    'poundage' => 0,
                    'channel' => 2,
                    'card_no'=>$duration['card_no'],
                    'bank_id'=>$planListInfo[0]['bank_id'],
                    'bank_name'=>$planListInfo[0]['bank_name'],
                    'order_sn' => Lib::createOrderNo(),
                    'transaction_id' => Lib::getMs(),
                    'status' => 1,
                    'is_pay' => -1,
                    'intatus' => 1,
                    'create_time' => Lib::getMs(),
                ];
                //记账，计入bill表
                $info2->insert('bill',$billData1);
            }
            $return =1;
        }else{
            $return =0;
        }
        return $return;
    }

    //根appid 查询出 appname
    public function getAppName($appid=null)
    {
         $result = DBQ::getRow("merc","app_name",["appid"=>$appid]);
         return $result;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2018/1/29
 * Time: 10:56
 */
namespace App\ADMIN\Controller;
use Core\DB\DBQ;
use Core\Lib;
use Core\Extend\Dwz;
use Core\Base\Controller;

class CreditHkAmount extends Controller
{
    public function index()
    {
        $type = Lib::request ( 'type' );
        $year = Lib::request ( 'year' );
        $month = Lib::request ( 'month' );

        $condition = '1=1 AND `bill_type` = 1 AND `is_pay` = 1';


//		$condition['AND']['A.bill_type'] = '2'; //2消费
////		$condition['AND']['is_pay'] = '1'; //-1未支付1已支付
//		$condition['AND']['A.channel'] = '2'; //1易联2易宝
        $type = ($type) ? $type : "day";
        ($year) ? $year= $year : $year= date("Y");
        if(!empty($month)){
            $daystart=strtotime($year."-".$month."-1"." 00:00:00")."000";
            $dayend=strtotime($year."-".$month."-".Lib::getMonthLastDay($year,$month)." 23:59:59")."999";
            $condition .= " AND (
                            `create_time` BETWEEN ".$daystart."
                            AND ".$dayend."
                        )";
//                $condition['AND']['A.create_time[<>]'] = [$daystart,$dayend];
        }else{
            $daystart=strtotime($year."-1-1"." 00:00:00")."000";
            $dayend=strtotime($year."-12-31"." 23:59:59")."999";
            $condition .= " AND (
                                `create_time` BETWEEN ".$daystart."
                                AND ".$dayend."
                            )";
        }

//        ($userid) ? $condition ['AND'] ['user_id'] = $userid : null;
//        $condition['ORDER'] = ['A.create_time'=>"ASC"];
        $data = $this->M()->getList ($type,$condition,$year,$month);
        $this->assign ( "data", $data );
        $this->assign ( "year", $year );
        $userlist = DBQ::getAll('user',['id','real_name']);
        $this->assign ( "userlist", $userlist );
        //测试数据
//        $this->appendbill();
        //测试数据end
        $this->view();
    }
//    //测试数据
//    public function appendbill()
//    {
//        $usermaxid=DBQ::getMax("user",'id');
//        $agentmaxid=DBQ::getMax("agent",'id');
//        $banklist=DBQ::getAll("bank",['id','name']);
//        $array_data=[];
//        for($i=0;$i<=5;$i++){
//            $userid=$usermaxid+rand(100,999);
//            $agentid=$agentmaxid+rand(100,999);
//            $rand=rand(10,20);
//            $a = mt_rand(10000000,99999999);
//            $b = mt_rand(10000000,99999999);
//            $time=Lib::getMs()+rand(100000,999999);
//            $databull=[
//                'user_id'=>$userid,
//                'agent_id'=>$agentid,
//                'bank_id'=>$banklist[$rand]['id'],
//                'bank_name'=>$banklist[$rand]['name'],
//                'poundage'=>rand(1,5),
//                'plan_id'=>0,
//                'amount'=>rand(100,5000),
//                'card_type'=>rand(1,2),
//                'bill_type'=>2,
//                'order_sn'=>"C".Lib::createOrderNo(),
//                'card_no'=>Lib::aesEncrypt($a.$b),
//                'task_no'=>0,
//                'transaction_id'=>0,
//                'channel'=>2,
//                'status'=>1,
//                'is_pay'=>1,
//                'intatus'=>1,
//                'create_time'=>$time
//            ];
//            array_push($array_data,$databull);
//
//        }
//        DBQ::add("bill_c",$array_data);
//        return true;
//    }
//    public function delbill()
//    {
//        DBQ::del('bill_c', [
//            'id[>]' => 1
//        ]);
//
//        return true;
//
//    }
//
//    public function append()
//    {
//        $usermaxid=DBQ::getMax("user",'id');
//        $agentmaxid=DBQ::getMax("agent",'id');
//        $banklist=DBQ::getAll("bank",['id','name']);
//
//        $year="2018";
//        for($j = 1;$j <= 3;$j++){
//            for ($i = 1; $i <= Lib::getMonthLastDay($year,$j); $i++) {
//
//                if($j==3 && $i>22){
//                    return false;
//                }else{
//                    $array_data=[];
//                    for($k=0;$k<=100;$k++){
//                        $userid=$usermaxid+rand(100,999);
//                        $agentid=$agentmaxid+rand(100,999);
//                        $rand=rand(10,20);
//                        $a = mt_rand(10000000,99999999);
//                        $b = mt_rand(10000000,99999999);
//                        $h=rand(0,23);
//                        $s=rand(0,59);
//                        $x=rand(1,999);
//                        $time=strtotime($year."-".$j."-".$i." ".$h.":".$s.":".$s).$x;
//                        $databull=[
//                            'user_id'=>$userid,
//                            'agent_id'=>$agentid,
//                            'bank_id'=>$banklist[$rand]['id'],
//                            'bank_name'=>$banklist[$rand]['name'],
//                            'poundage'=>rand(1,5),
//                            'plan_id'=>0,
//                            'amount'=>rand(10000,100000),
//                            'card_type'=>rand(1,2),
//                            'bill_type'=>2,
//                            'order_sn'=>"C".Lib::createOrderNo(),
//                            'card_no'=>Lib::aesEncrypt($a.$b),
//                            'task_no'=>0,
//                            'transaction_id'=>0,
//                            'channel'=>2,
//                            'status'=>1,
//                            'is_pay'=>1,
//                            'intatus'=>1,
//                            'create_time'=>$time
//                        ];
//                        array_push($array_data,$databull);
//
//                    }
//                    DBQ::add("bill_c",$array_data);
//                }
//            }
//        }
//    }
    //测试数据end

}

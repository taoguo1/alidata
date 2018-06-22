<?php
namespace App\ADMIN\Controller;
use Core\Lib;
use Core\Base\Controller;
use Core\Extend\Dwz;
use Core\DB\DBQ;
use App\WWW\Model\Cron;
//马来境外消费账单管理
class OverseasProfitByMl extends Controller {
    public function index(){
        $start_date = Lib::request('start_date');
        $end_date = Lib::request('end_date');
        $appid = Lib::request('oem_oem_appid');
        $out_order_id = Lib::request('out_order_id');//渠道订单号
        $order_id = Lib::request('order_id');//本地订单号

        //收益金额
        $pay_amt_start = Lib::request('pay_amt_start');
        $pay_amt_end = Lib::request('pay_amt_end');

        //总金额
        $amount_start = Lib::request('amount_start');
        $amount_end = Lib::request('amount_end');


        $profit = Lib::request('profit');//收益金额
        $amount = Lib::request('amount');//总金额

        $condition = " WHERE 1";

        //收益金额
        if($pay_amt_start&&!$pay_amt_end){
            $condition .= " and profit >= ".$pay_amt_start;
            $arr['profit[>=]'] =$pay_amt_start;
        }
        if(!$pay_amt_start&&$pay_amt_end){
            $condition .= " and profit <= ".$pay_amt_end;
            $arr['profit[<=]'] = $pay_amt_end;
        }
        if($pay_amt_start && $pay_amt_end) {
            $condition .= " and profit between " .$pay_amt_start . " and " .$pay_amt_end;
            $arr['profit[<>]'] = [$pay_amt_start,$pay_amt_end];
        }

        //总金额
        if($amount_start&&!$amount_end){
            $condition .= " and amount >= ".$amount_start;
            $arr['amount[>=]'] =$amount_start;
        }
        if(!$amount_start&&$amount_end){
            $condition .= " and amount <= ".$amount_end;
            $arr['amount[<=]'] = $amount_end;
        }
        if($amount_start && $amount_end) {
            $condition .= " and amount between " .$amount_start . " and " .$amount_end;
            $arr['amount[<>]'] = [$amount_start,$amount_end];
        }



        if($start_date&&!$end_date){
            $condition .= " and create_time >= " .(strtotime($start_date.' 00:00:00')) .'000';
            $arr['create_time[>=]'] = (strtotime($start_date.' 00:00:00')) .'000';
        }
        if(!$start_date&&$end_date){
            $condition .= " and create_time <= " .(strtotime($end_date.' 23:59:59')).'999';
            $arr['create_time[<=]'] = (strtotime($end_date.' 23:59:59')) .'999';
        }
        if($start_date && $end_date) {
            $condition .= " and create_time between " . (strtotime($start_date.' 00:00:00')) .'000' . " and " . (strtotime($end_date.' 23:59:59')).'999';
            $daystart=(strtotime($start_date.' 00:00:00')) .'000';
            $dayend=(strtotime($end_date.' 23:59:59')).'999';
            $arr['create_time[<>]'] = [$daystart,$dayend];
        }
        if($appid) {
            $condition .= " and appid ='$appid'";
            $arr['appid']=$appid;
        }
        if($order_id){
            $condition .= " and order_id like '%$order_id%'";
             $arr['order_id[~]']=$order_id;
        }
        if($out_order_id){
            $condition .= " and out_order_id like '%$out_order_id%'";
            $arr['out_order_id[~]']=$out_order_id;
        }
        /*if($profit){
            $condition .= " and profit = ".$profit;
            $arr['profit']=$profit;
        }
        if($amount){
            $condition .= " and amount = ".$amount;
        }*/
        $count2 = DBQ::getAll('myr_profit','*',
             $arr
        );
        $count=0;
        $countAmount=0;
        foreach($count2 as $k=>$v){
            //if($v['status']==1){
               $count+=$v['amount'];
               $countAmount+=$v['oem_profit'];
            //}
        }
        $condition .= " order by id desc";
        $pageArr = Lib::setPagePars();
        if ($pageArr['orderField']) {
            $columns['ORDER'] = [
                $pageArr['orderField'] => strtoupper($pageArr['orderDirection'])
            ];
        }
        $data = $this->M()->getList($pageArr, $condition);
        $model = new \App\ADMIN\Model\Index();
        $mersInfo=$model->getMercInfo();
        foreach($data['list'] as $k=>$v){ 
            foreach($mersInfo as $k2=>$v2){
                if($v['appid']==$v2['appid']){
                    $data['list'][$k]['app_name']=$v2['app_name'];
                }
            } 
        }
        //获取收益金额
        //$count = DBQ::sum('myr_profit','profit'
        //);
        //获取oem总金额
       // $countAmount = DBQ::sum('myr_profit','oem_profit'
       // );
        $this->assign("count",$count);
        $this->assign("countAmount",$countAmount);
        $this->assign("data", $data);
        $this->view();

    }

    public function oemContact() {
        $appid = Lib::request('appid');
        $app_name = Lib::request('app_name');
        $status = Lib::request('status');
        $start_date = Lib::request('start_date');
        $end_date = Lib::request('end_date');

        $condition = " WHERE 1";
        if ($appid) {
            $condition .= " and appid like '%" . $appid . "%'";
        }
        if ($app_name) {
            $condition .= " and app_name like '%" . $app_name . "%'";
        }
        if ($status) {
            $condition .= " and status = '" . $status . "'";
        }

        if ($start_date || $end_date) {
            $condition .= " and A.create_time between " . (strtotime($start_date)) * 1000 . " and " . (strtotime($end_date)) * 1000;
        }

        $pageArr = Lib::setPagePars();
        if ($pageArr['orderField']) {
            $columns['ORDER'] = [
                $pageArr['orderField'] => strtoupper($pageArr['orderDirection'])
            ];
        }

        $data = $this->M()->oemList($pageArr, $condition);

        $this->assign("data", $data);
        $this->view();
    }

}
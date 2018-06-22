<?php
namespace App\ADMIN\Controller;
use Core\Lib;
use Core\Base\Controller;
use Core\Extend\Dwz;
use Core\DB\DBQ;
//马来境外消费账单管理
class OverseasBillByMl extends Controller {
    public function index(){
        $start_date = Lib::request('start_date');
        $end_date = Lib::request('end_date');
        $appid = Lib::request('oem_oem_appid');
        $status = Lib::request('type');//订单状态
        $id_no = Lib::request('id_no');//身份证号
        $out_order_id = Lib::request('out_order_id');//渠道订单号
        $order_id = Lib::request('order_id');//本地订单号
        $phone = Lib::request('phone');//持卡人电话
        $card_holder = Lib::request('card_holder');//持卡人姓名
        //$pay_amt = Lib::request('pay_amt');//交易金额
        $pay_amt_start = Lib::request('pay_amt_start');
        $pay_amt_end = Lib::request('pay_amt_end');
        $card_no = Lib::request('card_no');//卡号
        $condition = " WHERE 1";
        if($pay_amt_start&&!$pay_amt_end){
            $condition .= " and pay_amt >= ".$pay_amt_start*100;
            $arr['pay_amt[>=]'] =$pay_amt_start*100;
        }
        if(!$pay_amt_start&&$pay_amt_end){
            $condition .= " and pay_amt <= ".$pay_amt_end *100;
            $arr['pay_amt[<=]'] = $pay_amt_end *100;
        }
        if($pay_amt_start && $pay_amt_end) {
            $condition .= " and pay_amt between " .$pay_amt_start*100 . " and " .$pay_amt_end*100;
            $arr['pay_amt[<>]'] = [$pay_amt_start*100,$pay_amt_end*100];
        }
        if($start_date&&!$end_date){
            $condition .= " and order_date >= ".(strtotime($start_date.' 00:00:00')) .'000';
            $arr['order_date[>=]'] = (strtotime($start_date.' 00:00:00')) .'000';
        }
        if(!$start_date&&$end_date){
            $condition .= " and order_date <= " . (strtotime($end_date.' 23:59:59')) .'999';
            $arr['order_date[<=]'] = (strtotime($end_date.' 23:59:59')) .'999';
        }
        if($start_date && $end_date) {
            $condition .= " and order_date between " . (strtotime($start_date.' 00:00:00')) .'000' . " and " . (strtotime($end_date.' 23:59:59')).'999';
            $daystart=(strtotime($start_date.' 00:00:00')) .'000';
            $dayend=(strtotime($end_date.' 23:59:59')).'999';
            $arr['order_date[<>]'] = [$daystart,$dayend];
        }
        if($card_holder){
            $condition .= " and card_holder = '$card_holder'";
            $arr['card_holder[~]']=$card_holder;
        }
        if($card_no){
            $condition .= " and card_no like '%$card_no%'";
            $arr['card_no[~]']=$card_no;
        }
        if($phone){
            $condition .= " and phone like '%$phone%'";
            $arr['phone[~]']=$phone;

        }
        if($order_id){
            $condition .= " and order_id like '%$order_id%'";
            $arr['order_id[~]']=$order_id;
        }
        if($out_order_id){
            $condition .= " and out_order_id like '%$out_order_id%'";
            $arr['out_order_id[~]']=$out_order_id;
        }
        if($id_no){
            $condition .= " and id_no like '%$id_no%'";
            $arr['id_no[~]']=$id_no;
        }
        if($status){
            $condition .= " and status = ".$status;
            $arr['status']=$status;
        }
        if($appid) {
            $condition .= " and appid ='$appid'";
            $arr['appid']=$appid;
        }

        $count2 = DBQ::getAll('myr_order','*',
             $arr
        );
        $count=0;
        $countAmount=0;
        foreach($count2 as $k=>$v){
            if($v['status']==1){
               $count++;
               $countAmount+=$v['foreign_amt'];
            }
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
                $data['list'][$k]['foreign_amt']=$v['foreign_amt']/100;
                $data['list'][$k]['pay_amt']=$v['pay_amt']/100;
                
            } 
        }
        $this->assign("count",$count);
        $this->assign("countAmount",$countAmount/100);
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
<?php
namespace App\ADMIN\Controller;
use Core\Lib;
use Core\Base\Controller;
use Core\Extend\Dwz;
use Core\DB\DBQ;


class OverseasBill extends Controller {

    public function index()
    {
        $appid = Lib::request('oem_oem_appid');
        $ip = Lib::request('IP');


        $start_date = Lib::request('start_date');
        $end_date = Lib::request('end_date');
        $condition = null;
        ($start_date) ? $condition ['AND'] ['time[>=]'] =strtotime($start_date. " 00:00:00")*1000: null;
        ($end_date) ? $condition ['AND'] ['time[<=]'] =strtotime($end_date. " 23:59:59")*1000 : null;
        $pageArr = Lib::setPagePars();
        if($appid) {
            $condition['AND']["appid"]=$appid;
        }
        if($ip) {
            $condition['AND']["ip[~]"]=$ip;
        }
        if ($pageArr['orderField']) {
            $columns['ORDER'] = [
                $pageArr['orderField'] => strtoupper($pageArr['orderDirection'])
            ];
        }

        //获取所有境外支付账单
        $data  = $this->M()->getList($pageArr,$condition);

        $condition['AND']['status'] = 1;

        //获取成功的笔数
        $count = DBQ::count('jwpay_no','id',
            $condition
        );
        //获取成功总金额
        $countAmount = DBQ::sum('jwpay_no','amount',
            $condition
        );

        $this->assign("data",$data);
        $this->assign("count",$count);
        $this->assign("countAmount",$countAmount);

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
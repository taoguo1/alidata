<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2018/1/19
 * Time: 14:27
 */
namespace App\ADMIN\Controller;

use Core\Base\Controller;
use Core\DB\DBQ;
use Core\Lib;
use Core\Extend\Dwz;

class KcpOrder extends Controller
{

    /**
     *
     * @name 查询卡测评
     */
 	public function index(){


		$real_name = Lib::request('real_name');
		$id_card = Lib::request('id_card');
		$card_no = Lib::request('card_no');
		$mobile = Lib::request('mobile');
        $order_sn = Lib::request('order_sn');
        $order_wxsn = Lib::request('order_wxsn');
        $start_date = Lib::request('start_date');
        $end_date = Lib::request('end_date');
        $status = Lib::request('status');
        $appid = Lib::request('appid');
		$condition = null;
		($real_name) ? $condition ['AND'] ['real_name'] =Lib::aesEncrypt($real_name) : null;
		($id_card) ? $condition ['AND'] ['id_card'] = Lib::aesEncrypt($id_card): null;
		($card_no) ? $condition ['AND'] ['card_no'] = Lib::aesEncrypt($card_no): null;
		($mobile) ? $condition ['AND'] ['B.mobile'] = $mobile : null;
        ($order_sn) ? $condition ['AND'] ['order_sn'] = $order_sn : null;
        ($order_wxsn) ? $condition ['AND'] ['order_wxsn'] = $order_wxsn : null;
        ($status) ? $condition ['AND'] ['status'] = $status : null;
        ($appid) ? $condition ['AND'] ['appid'] = $appid : null;
		
		($start_date) ? $condition ['AND'] ['create_time[>=]'] =strtotime($start_date. " 00:00:00")*1000: null;
		($end_date) ? $condition ['AND'] ['create_time[<=]'] =strtotime($end_date. " 23:59:59")*1000 : null;

		$condition ['ORDER'] = [ 
				'id' => 'ASC'
		];
		$pageArr = Lib::setPagePars();
		if ($pageArr['orderField']) {
			$columns['ORDER'] = [
					$pageArr['orderField'] => strtoupper($pageArr['orderDirection'])
			];
		}
	
		$data = $this->M()->getList($pageArr, $condition);

		$this->assign("data", $data);
		
		$this->view();
	}
    /**
     *
     * @name 删除
     * @param number $id
     */
    public function del($id = 0) {
        if ($this->M ()->del ( $id )) {
            Dwz::success ( Lib::getUrl ( $this->M ()->modelName ), $this->M ()->modelName );
        } else {
            Dwz::err ();
        }
    }
   
}
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

class BillStatistics extends Controller
{

    /**
     *
     * @name 查询商家
     */
 	public function index(){


		$app_name = Lib::request('app_name');
		$appid = Lib::request('appid');
		$real_name = Lib::request('real_name');
		$mobile = Lib::request('mobile');
		$start_date = Lib::request('start_date');
		$end_date = Lib::request('end_date');
		$condition = null;
		($appid) ? $condition ['AND'] ['A.appid'] = $appid : null;
		($app_name) ? $condition ['AND'] ['A.app_name'] = $app_name : null;
		($real_name) ? $condition ['AND'] ['B.real_name'] = $real_name : null;
		($mobile) ? $condition ['AND'] ['B.mobile'] = $mobile : null;		
		($start_date) ? $condition ['AND'] ['B.create_time[>=]'] =strtotime($start_date. " 00:00:00")*1000: null;
		($end_date) ? $condition ['AND'] ['B.create_time[<=]'] =strtotime($end_date. " 23:59:59")*1000 : null;

		$condition ['ORDER'] = [ 
				'B.id' => 'ASC' 
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
   
}
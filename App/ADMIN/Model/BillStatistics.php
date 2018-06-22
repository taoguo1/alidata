<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2018/1/19
 * Time: 15:03
 */
namespace App\ADMIN\Model;
use Core\Lib;
use Core\DB\DBQ;
use Core\Base\Model;
class BillStatistics extends Model
{
	
		public function getList($pageArr = null, $condition = null) {
			$data = DBQ::pages($pageArr, 'bill (A)', [
            '[>]merc (B)' => [
                'A.appid' => 'appid'
            ]
        ], [
           'A.id',
           'A.appid',                        
           'A.real_name',
           'A.amount',
           'A.poundage',
           'A.bill_type',
           'A.card_type', 
           'A.card_no',
           'A.mobile',
           'A.create_time',
           'B.appid', 
           'B.app_name', 
        ], $condition);
//     print_r($data)	;die;       
		return $data;
	}
	

}
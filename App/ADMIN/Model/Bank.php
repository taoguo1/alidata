<?php

namespace App\ADMIN\Model;
use Core\Lib;
use Core\Base\Model;
use Core\DB\DBQ;
use Core\Extend\Dwz;

class Bank extends Model {
	public function getList($pageArr = null, $condition = null) {
		$data = DBQ::pages ( $pageArr, 'bank', '*', $condition );

		return $data;
	}
	public static function getBank() {
		$data = DBQ::getAll('bank','*',['ORDER'=>["id" => "DESC"]]);
		return $data;
	}
	
	public function add($data) {
		return DBQ::add ( 'bank', $data );
	}
	public function edit($id, $data) {
		return DBQ::upd ( 'bank', $data, [ 
				'id' => $id 
		] );
	}
	public function del($data) {
		return DBQ::del ( 'bank', $data );
	}
	public function updateConfig()
    {
        $list = DBQ::getAll('bank', '*');
       foreach($list as $k=>$v){
       		$list[$k]['logo'] = OSS_ENDDOMAIN.'/'.$v['logo'];
       		$list[$k]['back_image'] = OSS_ENDDOMAIN.'/'.$v['back_image'];
       }
       
       $data=json_encode($list);
        $file = APP_PATH.'Config/Bank/BankConfig.php';
        file_put_contents($file, $data);
		if(file_exists($file))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}


<?php
namespace App\ADMIN\Model;
use Core\Lib;
use Core\Base\Model;
use Core\Extend\Dwz;

class SystemConfig extends Model {
	function getList() {
		$act = Lib::post ( 'act' );
		if ($act == 'edit') {
			$this->delete ( 'system_config', ['id[!]'=>0]);
			$this->insert ( 'system_config', [ 
					'id'=>1,
    			    'login_header_title' => Lib::post ( 'login_header_title' ),
    			    'login_footer_copyright' => Lib::post ( 'login_footer_copyright' ),
    			    'login_title' => Lib::post ( 'login_title' ),
    			    'system_name' => Lib::post ( 'system_name' ),
    			    'system_title' => Lib::post ( 'system_title' ),
    			    'system_copyright' => Lib::post ( 'system_copyright' ) ,
    			    'is_show_left_tree'=>Lib::post('is_show_left_tree')
			] );
			return Dwz::success ( Lib::getUrl ( 'SystemConfig' ) );
		}
		$list = $this->get ( 'system_config', '*' );
		return $list;
	}
	function edit() {
	}
}
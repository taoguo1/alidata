<?php
namespace App\ADMIN\Model;
use Core\Base\Model;
class Help extends Model {
	public function getList() {
		return $this->select ( 'help', '*', [ 
				'ORDER' => [ 
						'id' => 'ASC' 
				] 
		] );
	}
	public function getDetail($id) {
		return $this->get ( 'help', ['content'], [ 
				'id' => $id 
		] );
	}
}
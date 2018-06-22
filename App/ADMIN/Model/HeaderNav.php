<?php
namespace App\ADMIN\Model;
use Core\Base\Model;
class HeaderNav extends Model {
	public function getList($pageArr = null, $condition = null) {
		$data = $this->page ( $pageArr, 'header_nav', '*', $condition );
		return $data;
	}
	public function add($data) {
		return $this->insert ( 'header_nav', $data );
	}
	public function edit($id = 0, $data) {
		return $this->update ( 'header_nav', $data, [ 
				'id' => $id 
		] );
	}
	public function del($id = 0) {
		return $this->delete ( 'header_nav', [ 
				'id' => $id 
		] );
	}
}
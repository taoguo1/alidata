<?php
namespace App\ADMIN\Controller;
use Core\Base\Controller;
class SystemConfig extends Controller {
	/**
	 * @name 设置
	 */
	public function index() {
		
		$list = $this->M()->getList();
		$this->assign('list', $list);
		$this->view ();
	}
}
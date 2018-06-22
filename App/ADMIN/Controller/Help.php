<?php
namespace App\ADMIN\Controller;
use Core\Base\Controller;
/**
 * @name 开发帮助
 * @author Yu
 *
 */
class Help extends Controller {
	/**
	 * @name 查询
	 */
	public function index() {
		$data = $this->M ()->getList ();
		$content = $this->M ()->getDetail ( 1 ) ['content'];
		$this->assign ( 'content', $content );
		$this->assign ( 'data', $data );
		$this->view ();
	}
	/**
	 * @name 详情
	 * @param number $id
	 */
	public function detail($id = 0) {
		$content = $this->M ()->getDetail ( $id ) ['content'];
		$this->assign ( 'content', $content );
		$this->view ();
	}
}
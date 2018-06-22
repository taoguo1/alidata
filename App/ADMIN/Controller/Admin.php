<?php
namespace App\ADMIN\Controller;
use Core\Lib;
use Core\Base\Controller;
use Core\Extend\Dwz;
/**
 *
 * @name 管理员管理
 * @author Yu
 */
class Admin extends Controller {
  
	/**
	 *
	 * @name 查询
	 */
	public function index() {
	    // $this->model = new \App\ADMIN\Model\Admin();
		$account = Lib::post ( 'account' );
		$real_name = Lib::post ( 'real_name' );
		$condition = null;
		($account) ? $condition ['AND'] ['account[~]'] = $account : null;
		($real_name) ? $condition ['AND'] ['real_name[~]'] = $real_name : null;
		$condition ['ORDER'] = [ 
				'id' => 'ASC' 
		];
		
		$pageArr = Lib::setPagePars ();
		if ($pageArr ['orderField']) {
			$columns ['ORDER'] = [ 
					$pageArr ['orderField'] => strtoupper ( $pageArr ['orderDirection'] ) 
			];
		}
		$data = $this->M ()->getList ( $pageArr, $condition );
		$this->assign ( "data", $data );
		$this->view ();
	}
	/**
	 *
	 * @name 禁用
	 * @param number $id        	
	 */
	public function disable($id = 0) {
		if ($this->M ()->disable ( $id )) {
		    Dwz::success ( Lib::getUrl ( $this->M ()->modelName ), $this->M ()->modelName );
		} else {
			Dwz::err ();
		}
	}
	/**
	 *
	 * @name 启用
	 * @param number $id        	
	 */
	public function enable($id = 0) {
		if ($this->M ()->enable ( $id )) {
		    Dwz::success ( Lib::getUrl ( $this->M ()->modelName ), $this->M ()->modelName );
		} else {
			Dwz::err ();
		}
	}
	/**
	 *
	 * @name 添加
	 */
	public function add() {
	    $act = Lib::request ( "act" );
		if ($act == 'add') {
		    if (empty ( Lib::post ( 'role_id', '', 'array' ) )) {
				Dwz::err ( '请选择所属角色' );
			}
			$insertId = $this->M ()->add ();
			if ($insertId == - 1) {
				Dwz::err ( '登录帐号重复' );
			} else if ($insertId) {
				Dwz::successDialog ( $this->M ()->modelName, '', 'closeCurrent' );
			} else {
				Dwz::err ();
			}
		}
		
		$roleList = $this->M()->getRoleList ();
		$this->assign ( 'roleList', $roleList );
		$this->view ();
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
	/**
	 *
	 * @name 修改
	 * @param number $id        	
	 */
	public function edit($id = 0) {
	    if (Lib::post ( 'act' ) == 'edit') {
			if ($this->M ()->edit ( $id )) {
				Dwz::successDialog ( $this->M ()->modelName, '', 'closeCurrent' );
			} else {
				Dwz::err ();
			}
		}
		
		// 修改前查询
		$list = $this->M ()->edit ( $id, 'get' );
		$roleList = $this->M ()->getRoleList ();
		$this->assign ( 'list', $list );
		$this->assign ( 'roleList', $roleList );
		$this->view ();
	}
}
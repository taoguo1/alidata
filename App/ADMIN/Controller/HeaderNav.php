<?php
namespace App\ADMIN\Controller;
use Core\Lib;
use Core\Base\Controller;
use Core\Extend\Dwz;
/**
 *
 * @name 顶部导航配置
 * @author Yu
 */
class HeaderNav extends Controller {
	/**
	 *
	 * @name 查询
	 */
	public function index() {
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
	 * @name 添加
	 * @param $act        	
	 */
	public function add($act = null) {
		if ($act == 'add') {
			$data = [ 
			    'name' => Lib::post ( 'name' ),
			    'controller' => Lib::post ( 'controller' ),
			    'action' => Lib::post ( 'action' ),
			    'pars' => Lib::post ( 'pars' ),
			    'target' => Lib::post ( 'target' ),
			    'sort' => Lib::post ( 'sort' ) 
			];
			$insertId = $this->M ()->add ( $data );
			if ($insertId) {
				Dwz::successDialog ( $this->M ()->modelName, '', 'closeCurrent' );
			} else {
				Dwz::err ();
			}
		}
		if ($act == 'getClsMethods') {
		    Lib::outputJson ( (new \App\ADMIN\Model\Tree ())->getClsMethods () );
		}
		$controllerList = Lib::getControllerList ();
		$this->assign ( "controllerList", $controllerList );
		$this->view ();
	}
	/**
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
	 * @name 编辑
	 * @param number $id        	
	 * @param $act        	
	 */
	public function edit($id = 0, $act = null) {
		if ($act == 'edit' && ! empty ( $id )) {
			$data = [ 
					'name' => Lib::post ( 'name' ),
					'controller' => Lib::post ( 'controller' ),
					'action' => Lib::post ( 'action' ),
					'pars' => Lib::post ( 'pars' ),
					'target' => Lib::post ( 'target' ),
					'sort' => Lib::post ( 'sort' ) 
			];
			if ($this->M ()->edit ( $id ,$data)) {
				Dwz::successDialog ( $this->M ()->modelName, '', 'closeCurrent' );
			} else {
				Dwz::err ();
			}
		}
		if ($act == 'getClsMethods') {
		    Lib::outputJson ( (new \App\ADMIN\Model\Tree ())->getClsMethods () );
		}
		$controllerList = Lib::getControllerList ();
		
		$list = $this->M ()->db->get ( "header_nav", "*", [ 
				'id' => $id 
		] );
		$this->assign ( "controllerList", $controllerList );
		
		$this->assign ( "list", $list );
		$this->view ();
	}
}
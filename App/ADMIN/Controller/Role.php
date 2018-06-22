<?php
namespace App\ADMIN\Controller;
use Core\Lib;
use Core\Base\Controller;
use Core\Extend\Dwz;
use Core\DB\DBQ;
/**
 *
 * @name 角色管理
 * @author Yu
 */
class Role extends Controller {
	/**
	 *
	 * @name 查询
	 */
	public function index() {
		$condition ['ORDER'] = [ 
				'id' => 'ASC' 
		];
// 		session_destroy();
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
			$treeIds = [ ];
			$roleArray = isset($_POST ['role'])?$_POST ['role']:[];
			foreach ( $roleArray as $k => $v ) {
				$treeIds [] = $k;
			}
			$data = [ 
			    'name' => Lib::post ( 'name' ),
					'tree_ids' => implode ( ',', $treeIds ),
					'action_array' => serialize ( $roleArray ) 
			];
			$insertId = $this->M ()->add ( $data );
			if ($insertId) {
			    Dwz::successDialog ( $this->M ()->modelName, '', 'closeCurrent' );
			} else {
				Dwz::err ();
			}
		}
		$treeList = $this->M ()->getControllerFunction ( 0, '');
		$this->assign ( 'treeList', $treeList ,'');
		// 获取Controller以及Controller下的所有方法
		$this->view ();
	}
	/**
	 * @name 编辑
	 * @param number $id
	 * @param $act
	 */
	public function edit($id = 0, $act = null) {
		if ($act == 'edit' && ! empty ( $id )) {
			$treeIds = [ ];
			$roleArray = isset($_POST ['role'])?$_POST ['role']:[];
			foreach ( $roleArray as $k => $v ) {
				$treeIds [] = $k;
			}
			$data = [
			    'name' => Lib::post ( 'name' ),
					'tree_ids' => \implode ( ',', $treeIds ),
					'action_array' => serialize ( $roleArray )
			];
			if ($this->M ()->edit ( $id, $data )) {
				Dwz::successDialog ( $this->M ()->modelName, '', 'closeCurrent' );
			} else {
				Dwz::err ();
			}
		}
		
		$list = DBQ::getRow( "role", "*", [ 
				'id' => $id 
		] );


		$treeIdsArr = \explode(',', $list['tree_ids']);
		$actionArray = \unserialize($list['action_array']);
		$treeList = $this->M ()->getControllerFunction ( 0, '' ,$treeIdsArr,$actionArray);
		$this->assign ( 'treeList', $treeList );
		$this->assign ( "list", $list );
		$this->view ();
	}
}
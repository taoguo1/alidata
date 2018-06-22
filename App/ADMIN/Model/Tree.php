<?php
namespace App\ADMIN\Model;
use Core\Lib;
use Core\Base\Model;
use Core\Extend\Dwz;
class Tree extends Model {
	public $listStr = '';
	public $listOptionStr = '';
	public function getList($id, $cut) {
		$list = self::select ( 'tree', '*', [ 
				'pid' => $id,
				'ORDER' => [ 
						'sort' => 'ASC' 
				] 
		] );
		
		foreach ( $list as $k => $v ) {
			$icon = ! empty ( $v ['icon'] ) ? APP_SITE_PATH . $v ['icon'] : "";
			$status = ($v ['status'] == - 1) ? '<font style="color:red">禁用</font>' : '正常';
			$this->listStr .= '<tr target="id" rel="' . $v ['id'] . '">
			<td align="center" height="25">' . $v ['id'] . '</td>
			<td nowrap>' . $cut . $v ['name'] . '</td>
			<td align="center">' . $v ['alias'] . '</td>
			<td align="center">' . $v ['controller'] . '</td>
			<td align="center">' . $v ['action'] . '</td>
			<td align="center">' . $v ['pars'] . '</td>
			<td align="center">' . $v ['target'] . '</td>
			<td align="center"><img src="' . $icon . '" onerror="javascript:this.src=' . APP_ADMIN_STATIC . 'image/no_pic.png" height="18" /></td>
			<td align="center">' . $v ['sort'] . '</td>
			<td align="center">' . $status . '</td>

			</tr>';
			self::getList ( $v ['id'], $cut . '|--' );
		}
		return $this->listStr;
	}
	public function add() {
		return $this->insert ( 'tree', [ 
				'name' => Lib::post ( 'name' ),
		    'controller' => Lib::post ( 'controller' ),
		    'action' => Lib::post ( 'action' ),
		    'pars' => Lib::post ( 'pars' ),
		    'alias' => Lib::post ( 'alias' ),
		    'target' => Lib::post ( 'target' ),
		    'icon' => Lib::post ( 'icon' ),
		    'sort' => Lib::post ( 'sort' ),
		    'pid' => Lib::post ( 'pid' ) 
		] );
	}
	public function getOptionList($id, $cut, $selectId = 0) {
		$list = $this->select ( 'tree', '*', [ 
				'pid' => $id,
				'ORDER' => [ 
						'sort' => 'ASC' 
				] 
		] );
		
		foreach ( $list as $k => $v ) {
			if ($selectId == $v ['id']) {
				$this->listOptionStr .= '<option selected value="' . $v ['id'] . '">' . $cut . $v ['name'] . '</option>';
			} else {
				$this->listOptionStr .= '<option value="' . $v ['id'] . '">' . $cut . $v ['name'] . '</option>';
			}
			
			self::getOptionList ( $v ['id'], $cut . '|--', $selectId );
		}
		return $this->listOptionStr;
	}
	public function del($id = 0) {
		if ($this->has ( 'tree', [ 
				'pid' => $id 
		] )) {
			Dwz::err ( '该菜单下还有子菜单，请先删除子菜单' );
			return false;
		} else {
			return $this->delete ( 'tree', [ 
					'id' => $id 
			] );
		}
	}
	public function edit($id = 0) {
		return $this->update ( 'tree', [ 
				'name' => Lib::post ( 'name' ),
				'controller' => Lib::post ( 'controller' ),
				'action' => Lib::post ( 'action' ),
				'pars' => Lib::post ( 'pars' ),
				'alias' => Lib::post ( 'alias' ),
				'target' => Lib::post ( 'target' ),
				'icon' => Lib::post ( 'icon' ),
				'sort' => Lib::post ( 'sort' ),
				'pid' => Lib::post ( 'pid' ) 
		], [ 
				'id' => $id 
		] );
	}
	public function disable($id = 0) {
		return $this->update ( 'tree', [ 
				'status' => - 1 
		], [ 
				'id' => $id 
		] );
	}
	public function enable($id = 0) {
		return $this->update ( 'tree', [ 
				'status' => 0 
		], [ 
				'id' => $id 
		] );
	}
	public function getClsMethods() {
		$controller = Lib::request ( 'controller' );
		$controllerName = $controller;
		$controllers = APP_PATH . 'App/' . \strtoupper(RUN_PATH) . '/Controller/' . $controllerName . '.php';
		$arr = [ 
				"#" 
		];
		if (file_exists ( $controllers )) {
		    $arr = Lib::getClsMethods ( "\\App\\ADMIN\\Controller\\".$controllerName );
		}
		return $arr;
	}
}
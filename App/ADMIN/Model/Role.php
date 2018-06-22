<?php
namespace App\ADMIN\Model;
use Core\Lib;
use Core\Base\Model;
use Core\DB\DBQ;
class Role extends Model {
	public $listStr = '';
	public $roleTreeStr = '';
	public function getList($pageArr = null, $condition = null) {
		$data = DBQ::pages ( $pageArr, 'role', '*', $condition );

		foreach ( $data ['list'] as $k => $v ) {
			
			$this->roleTreeStr = '';
			$data ['list'] [$k] ['tree_name'] = $this->getRoleTree ( 0, '', $v ['tree_ids'], $v ['action_array'] );
		}

		return $data;
	}
	public function getRoleTree($id, $cut, $treeIds, $actionArray) {
		$treeData = DBQ::getAll( 'tree', '*', [ 
				'AND' => [ 
						'id' => explode ( ',', $treeIds ),
						'pid' => $id 
				] 
		] );
		$_actionArray = \unserialize ( $actionArray );
		foreach ( $treeData as $k => $v ) {
			
			// 查询该菜单树下的操作权限
			// $treeId = $v['id'];
			$curActionArray = $_actionArray [$v ['id']];
			$str = "";
			if (! empty ( $curActionArray )) {
				if (is_array ( $curActionArray )) {
					$_nameArr = [ ];
					foreach ( $curActionArray as $vm ) {
					    if (\class_exists("\\App\\ADMIN\\Controller\\".$v ['controller'])) {
    						$name = Lib::getFunctionName ( "\\App\\ADMIN\\Controller\\".$v ['controller'], $vm );
    						$_nameArr [] = $name . $vm;
					    }
					}
					$str = '<i style="color:#666">【' . implode ( ' / ', $_nameArr ) . '】</i>';
				}
			}
			if ($v ['pid'] == 0) {
				$this->roleTreeStr .= '<div style="line-height:18px;color:red;font-weight:bold">' . $cut . $v ['name'] . $str . '</div>';
			} else {
				$this->roleTreeStr .= '<div style="line-height:18px;">' . $cut . $v ['name'] . $str . ' </div>';
			}
			self::getRoleTree ( $v ['id'], $cut . '|--', $treeIds, $actionArray );
		}
		return $this->roleTreeStr;
	}
	// 查询菜单，对应Controller
	public function getControllerFunction($id, $cut, $treeIdsArr = [], $actionArray = []) {
	    $list = DBQ::getAll( 'tree', '*', [ 
				'pid' => $id,
				'ORDER' => [ 
						'sort' => 'ASC' 
				] 
		] );
		
		foreach ( $list as $k => $v ) {
			$checked = '';
			if (in_array ( $v ['id'], $treeIdsArr )) {
				$checked = 'checked';
			}
			
			$treeName = $v ['name'];
			
			if ($v ['pid'] == 0) {
				$this->listStr .= '<div style="width:280px;float:left;height:400px;border:1px solid #ccc;overflow-y:scroll;margin-right:5px;">';
				$treeName = '<span>' . $v ['name'] . '</span>';
				// $this->listStr .= '<div class="divider"></div>';
			}
			$this->listStr .= '<div class="unit">
			<label style="width:80%"> ' . $cut . '<input type="checkbox" ' . $checked . ' pid="' . $v ['pid'] . '" vid="' . $v ['id'] . '" class="check_role pid_' . $v ['pid'] . ' vid_' . $v ['id'] . '" id="check_role_' . $v ['id'] . '" name="role[' . $v ['id'] . ']"  value="' . $v ['id'] . '">' . $treeName . '</label>
			</div>';
			if (! empty ( $v ['controller'] ) && $v ['controller'] != '#') {
				// 获取function
			    $methods = Lib::getClsMethods ( "\\App\\ADMIN\\Controller\\".$v ['controller'] );
				if ($methods) {
					foreach ( $methods as $km => $vm ) {
						$checkedAction = '';
						if(!empty($actionArray)&&!empty($vm)&&!empty($actionArray[$v['id']]))
						{
						    if(is_array($actionArray[$v['id']])) {
                                if (in_array($vm, $actionArray[$v['id']])) {
                                    $checkedAction = 'checked';
                                }
                            }
						}
						$name = Lib::getFunctionName ( "\\App\\ADMIN\\Controller\\".$v ['controller'], $vm );
						$this->listStr .= '<div class="unit">
				<label style="width:80%;color:#999999">&nbsp;&nbsp;&nbsp;&nbsp;' . $cut . '<input type="checkbox" '.$checkedAction.' pid="' . $v ['pid'] . '" vid="' . $v ['id'] . '" vaction="' . $vm . '"  class="check_action pid_' . $v ['pid'] . ' vid_' . $v ['id'] . '" id="check_role_' . $v ['id'] . '_' . $vm . '"  name="role[' . $v ['id'] . '][]"  value="' . $vm . '">' . $name . '(' . $vm . ')</label>
				</div>';
					}
				}
			}
			
			self::getControllerFunction ( $v ['id'], $cut . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $treeIdsArr ,$actionArray);
			if ($v ['pid'] == 0) {
				$this->listStr .= '</div>';
			}
		}
		return $this->listStr;
	}
	public function add($data) {
	    return DBQ::add( 'role', $data );
	}
	public function edit($id = 0, $data) {
		return DBQ::upd( 'role', $data, [
				'id' => $id
		] );
	}
}
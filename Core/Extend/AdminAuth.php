<?php
namespace Core\Extend;
use Core\DB\DBQ;
class AdminAuth
{
    public function __construct($controllerName, $actionName) {

        $session = new Session();
        $roleId = $session->get('roleId');
        //$roleId = isset($_SESSION ['roleId'])?$_SESSION ['roleId']:'';
        if (RUN_PATH == APP_ADMIN && ! \in_array ( \ucfirst ( $controllerName ), \explode ( '|', APP_ADMIN_PUBLIC_CONTROLLER ) )) {
            $roleData = DBQ::getAll ( 'role', [
                'tree_ids',
                'action_array'
            ], [
                'id' => \explode ( ',', $roleId )
            ] );
            
            $treeIds = [ ];
            $actionArray = [ ];
            foreach ( $roleData as $k => $v ) {
                $treeIds [] = $v ['tree_ids'];
                $_actionArrays = \unserialize ( $v ['action_array'] );
                foreach ( $_actionArrays as $_k => $_v ) {
                    
                    if (\is_array ( $_v )) {
                        foreach ( $_v as $_vv ) {
                            $actionArray [$_k] [] = $_vv;
                        }
                    }
                }
            }
            foreach ( $actionArray as $k => $v ) {
                $actionArray [$k] = \array_unique ( $v );
            }
            $treeIds = \implode ( ',', $treeIds );
           
            $treeData = DBQ::getAll ( 'tree', [
                'id',
                'controller',
                'action'
            ], [
                'id' => \explode ( ',', $treeIds )
            ] );
            $controllerArray = [ ];
            $_actionArray = [ ];
            foreach ( $treeData as $v ) {
                $controllerArray [] = $v ['controller'];
                foreach ( $actionArray as $ka => $va ) {
                    if ($ka == $v ['id'] && \is_array ( $va )) {
                        $_actionArray [$v ['controller']] = $va;
                    }
                }
            }
			$count=count($controllerArray);
			$controllerArray[$count]='Plan';
			$controllerArray[$count+1]='PlanList';
            $controllerArray[$count+2]='OverseasBillByMl';
            $controllerArray[$count+3]='SystemMessage';
			$controllerArray[$count+4]='OverseasProfitByMl';
            $controllerArray[$count+5]='CreditAmount';
            $controllerArray[$count+6]='ConsumeCount';
            if (! \in_array ( $controllerName, $controllerArray )) {
                Dwz::auth ( '对不起，您没有权限,请联系管理员' );
            }
            if (isset ( $_actionArray [$controllerName] )) {
                if (! \in_array ( $actionName, $_actionArray [$controllerName] )) {
                    Dwz::auth ( '对不起，您没有权限,请联系管理员' );
                }
            }
        }
    }
}


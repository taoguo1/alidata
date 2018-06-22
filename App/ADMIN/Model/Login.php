<?php
namespace App\ADMIN\Model;
use Core\Lib;
use Core\Base\Model;
use Core\Extend\Dwz;
use Core\DB\DBQ;
use Core\Extend\Session;

class Login extends Model {
	public function login() {
		$account = Lib::post ( 'account' );
		$password = Lib::post ( 'password' );

		$list = DBQ::getRow( 'admin', [ 
				'id',
				'account',
				'password',
				'role_id',
				'status' 
		], [ 
				'account' => $account 
		] );

		if (empty ( $list )) {
		    if (Lib::post ( 'act' ) == 'timeOut') {
				Dwz::err ( '帐号或密码错误' );
			} else {
				Dwz::goBack ( '帐号或密码错误' );
			}
		} else {
			if ($list ['status'] == - 1) {
			    if (Lib::post ( 'act' ) == 'timeOut') {
					Dwz::err ( '该帐号已被禁用' );
				} else {
					Dwz::goBack ( '该帐号已被禁用' );
				}
			} else if (Lib::compilePassword ( $password ) != $list ['password']) {
			    if (Lib::post ( 'act' ) == 'timeOut') {
					Dwz::err ( '帐号或密码错误' );
				} else {
					Dwz::goBack ( '帐号或密码错误' );
				}
			} else {
				// 更改最后登录时间
				DBQ::upd ( 'admin', [ 
						'last_login_time' => Lib::getMs()
				], [ 
						'id' => $list ['id'] 
				] );
				// 登录成功
                \session_start();
				$_SESSION ['roleId'] = $list ['role_id'];
				$_SESSION ['accountId'] = $list ['id'];
				$_SESSION ['account'] = $list ['account'];

                /*
                $sess = new Session();
                $sess->set('roleId',$list ['role_id']);
                $sess->set('accountId',$list ['id']);
                $sess->set('account',$list ['account']);
*/

				if (Lib::post ( 'act' ) == 'timeOut') {
					Dwz::successClose ( '', '登录成功' );
				} else {
				    header ( 'location:' . Lib::getUrl ( 'Index' ) );
				}
			}
		}
	}
	public function logout() {
        /*
        unset($_SESSION ['account']);
        unset($_SESSION ['accountID']);
        $_SESSION ['account'] = NULL;
        $_SESSION ['accountID'] = NULL;
        */

		//session_destroy();

        $sess = new Session();
        $sess->remove('account');
        $sess->remove('accountID');
        $sess->remove('roleId');

		$loginUrl = Lib::getUrl('Login', "index", "act=admin");
		header ( 'location:' . $loginUrl );
	}
	public function getLoginInfo() {
		return DBQ::getRow( 'system_config', '*' );
	}
}
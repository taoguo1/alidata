<?php
namespace App\ADMIN\Controller;
use Core\Lib;
use Core\Base\Controller;
/**
 *
 * @name     管理员登录
 * @author Yu
 */
class Login extends Controller
{

    public function index($act = null)
    {
        if ($act != 'admin') {
            header("location:".APP_SITE_PATH);
        }
		
        $account = '';
        //$account = isset($_SESSION['account']) ? $_SESSION['account'] : '';
        /*
        $session = new Session();
        $account = $session->get('account');
        $account = isset($account) ? $account : '';
        */
        if (! empty($account)) {
            header('location:' . Lib::getUrl('index'));
        }
        
        $this->assign('data', $this->M()->getLoginInfo());
        //print_r($this->M()->getLoginInfo());
        $this->view();
    }

    public function loginBox()
    {
        $this->assign('data', $this->M()
            ->getLoginInfo());
        $this->view();
    }

    public function login()
    {
        $this->M()->login();
    }

    public function logout()
    {
        $this->M()->logout();
    }
}
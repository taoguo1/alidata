<?php
namespace App\ADMIN\Controller;
use Core\Lib;
use Core\Base\Controller;
use Core\Extend\Dwz;

class ChangePwd extends Controller
{

    public function changePwd($act = null)
    {
        if ($act == 'upd') {
            $oldPwd = Lib::post('oldPwd');
            $newPwd = Lib::post('newPwd');
            $newPwd1 = Lib::post('newPwd1');
            if (empty($oldPwd)) {
                Dwz::err('旧密码不能为空');
            }
            if (empty($newPwd)) {
                Dwz::err('请输入新的密码');
            }
            if ($newPwd != $newPwd1) {
                Dwz::err('两次输入密码不一致');
            }
            $oldPwd = Lib::compilePassword($oldPwd);
            $accountId = $_SESSION['accountId'];
            $account = $_SESSION['account'];
            $password = $this->M()->get('admin', 'password', [
                'id' => $accountId
            ]);
            if ($password != $oldPwd) {
                Dwz::err('旧密码输入错误');
            } else {
                $data = [
                    "password" => Lib::compilePassword($newPwd)
                ];
                $this->M()->edit($data, $accountId);
            }
            
        }
        $this->view();
    }
}
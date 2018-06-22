<?php
namespace App\ADMIN\Model;
use Core\Base\Model;
use Core\Extend\Dwz;
class ChangePwd extends Model
{

    public function edit($data, $accountId)
    {
        $ret = $this->update('admin', $data, [
            "id" => $accountId
        ]);
        if($ret)
        {
            Dwz::successClose('', "修改成功，请牢记您的新密码");
        }
        else {
            Dwz::err("密码没有更改");
        }
    }
}


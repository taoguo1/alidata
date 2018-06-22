<?php
/**
 * Created by jixiang.
 * User: pc
 * Date: 2018/5/5
 * Time: 14:33
 */

namespace App\WWW\Model;


use Core\Lib;

class User
{
    protected $dao = null;
    public function __construct ()
    {
        $this->dao = new DAO\User();
    }

    public function getUser($userId, $appId)
    {
        return $this->dao->getRow(['user_id' => $userId, 'app_id' => $appId]);
    }

    public function getUserByOpenid($openid)
    {
        return $this->dao->getRow(['open_id' => $openid]);
    }

    public function addUser($userId, $appId, $openid, $idFront, $idBack, $realName, $identityNo, $phone)
    {
        $data = [
            'open_id' => $openid,
            'user_id' => $userId,
            'app_id' => $appId,
            'id_front' => $idFront,
            'id_back' => $idBack,
            'real_name' => $realName,
            'id_no' => $identityNo,
            'phone' => $phone,
            'create_time' => Lib::getMs()
        ];
        return $this->dao->add($data);
    }

}
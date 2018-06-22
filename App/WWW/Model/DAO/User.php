<?php
/**
 * Created by jixiang.
 * User: pc
 * Date: 2018/5/6
 * Time: 9:16
 */

namespace App\WWW\Model\DAO;

use Core\Base\Model;
use Core\DB\DBQ;

class User extends Model
{
    public function add(array $data)
    {
        return DBQ::add('yjf_user', $data);
    }

    public function getRow(array $condition, array $fields = [])
    {
        if(empty($fields)) {
            $fields = '*';
        }
        return DBQ::getRow('yjf_user', $fields, $condition);
    }

    public function updUser(array $data, array $where)
    {
        return DBQ::upd('yjf_user', $data, $where);
    }
}
<?php
/**
 * Created by jixiang.
 * User: pc
 * Date: 2018/5/7
 * Time: 16:52
 */

namespace App\WWW\Model\DAO;


use Core\Base\Model;
use Core\DB\DBQ;

class WithDrawDetail extends Model
{
    public function addDetail($data)
    {
       return DBQ::add('yjf_withdraw_detail', $data);
    }

    public function updDetail(array $data, array $where)
    {
        return DBQ::upd('yjf_withdraw_detail', $data, $where);
    }

    public function getDetail($orderId)
    {
        return DBQ::getRow('yjf_withdraw_detail', '*', ['order_id' => $orderId]);
    }
}
<?php
/**
 * Created by jixiang.
 * User: pc
 * Date: 2018/5/6
 * Time: 17:35
 */

namespace App\WWW\Model\DAO;


use Core\Base\Model;
use Core\DB\DBQ;

class Order extends Model
{
    public function add(array $data)
    {
        return DBQ::add('yjf_order', $data);
    }

    public function updOrder(array $data, array $where)
    {
        return DBQ::upd('yjf_order', $data, $where);
    }

    public function getInfo($orderId)
    {
        return DBQ::getRow('yjf_order', '*', ['order_id' => $orderId]);
    }

    public function addCheckOrder($cardNo, $orderId)
    {
        $data = [
            'card_no' => $cardNo,
            'order_id' => $orderId
        ];
        return DBQ::add('yjf_check_order', $data);
    }

    public function getCheckOrder($order_id)
    {
        return DBQ::getRow('yjf_check_order', '*', ['order_id' => $order_id]);
    }
}
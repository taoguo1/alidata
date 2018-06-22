<?php
/**
 * Created by jixiang.
 * User: pc
 * Date: 2018/5/5
 * Time: 14:34
 */

namespace App\WWW\Model;


use App\WWW\Model\DAO\ChannelOrder;
use Core\Lib;
use Core\DB\DBQ;

class Order
{

    public function updateChannelCheckOrderStatus($status, $orderId, $cardNo)
    {
        $cardDao = new DAO\Card();
        $orderDao = new DAO\Order();
        $db = DBQ::connect();
        $db->db->pdo->beginTransaction();
        $modifiedTime = Lib::getMs();
        $orderDao->updOrder(['status' => $status, 'modified_time' => $modifiedTime], ['order_id' => $orderId]);
        $cardDao->updCard(['is_auth' => $status, 'modified_time' => $modifiedTime], ['card_no' => $cardNo]);
        return $db->db->pdo->commit();
    }

    public function updateOrderOpenAccountStatus($status, $orderId, $openid)
    {
        $userDao = new DAO\User();
        $orderDao = new DAO\Order();
        $db = DBQ::connect();
        $db->db->pdo->beginTransaction();
        $modifiedTime = Lib::getMs();
        $userDao->updUser(['is_auth_account' => $status, 'modified_time' => $modifiedTime], ['open_id' => $openid]);
        $orderDao->updOrder(['status' => $status, 'modified_time' => $modifiedTime], ['order_id' => $orderId]);
        return $db->db->pdo->commit();
    }

    public function updateWithDrawStatus($status, $orderId, $amount, $receiveAmount, $serviceFee, $profitAmount, $outOrderId = '')
    {
        $orderDao = new DAO\Order();
        $withDrawDao = new DAO\WithDrawDetail();
        $db = DBQ::connect();
        $db->db->pdo->beginTransaction();
        $modifiedTime = Lib::getMs();
        $orderDao->updOrder(['status' => $status, 'out_order_id' => $outOrderId, 'modified_time' => $modifiedTime],['order_id' => $orderId]);
        $withDrawDao->updDetail(
            ['amount' => $amount, 'receive_amount' => $receiveAmount, 'service_fee' => $serviceFee, 'profit_amount' => $profitAmount],
            ['order_id' => $orderId]
        );
        return $db->db->pdo->commit();
    }
    public function getOrder($orderId)
    {
        $dao =  new DAO\Order();
        return $dao->getInfo($orderId);
    }

    public function getWithDrawDetail($orderId)
    {
        $dao = new DAO\WithDrawDetail();
        return $dao->getDetail($orderId);
    }

    public function getChannelCheckCard($orderId)
    {
        $dao = new DAO\Order();
        return $dao->getCheckOrder($orderId);
    }

    public function addOrder(array $orderInfo, $type)
    {
        $orderInfo['create_time'] = Lib::getMs();
        switch($type) {
            case 1:
                $result = $this->addChannelCheckOrder($orderInfo);
                break;
            case 2:
                $result = $this->addOpenPayAccountOrder($orderInfo);
                break;
            case 3:
                $result = $this->addWithDrawOrder($orderInfo);
                break;
            default:
                $result = false;
        }
        return $result;
    }

    protected function addChannelCheckOrder(array $orderInfo)
    {
        $orderDao = new DAO\Order();
        $orderInfo['type'] = 1;
        $cardNo = $orderInfo['card_no'];
        unset($orderInfo['card_no']);
        $db = DBQ::connect();
        $db->db->pdo->beginTransaction();
        $orderDao->addCheckOrder($cardNo, $orderInfo['order_id']);
        $orderDao->add($orderInfo);
        return $db->db->pdo->commit();
    }

    protected function addOpenPayAccountOrder(array $orderInfo)
    {
        $orderDao = new DAO\Order();
        $orderInfo['type'] = 2;
        return $orderDao->add($orderInfo);
    }

    protected function addWithDrawOrder(array $orderInfo)
    {
        $orderDao = new DAO\Order();
        $detailDao = new DAO\WithDrawDetail();
        $order = [
            'open_id' => $orderInfo['open_id'],
            'type' => 3,
            'order_id' => $orderInfo['order_id'],
            'create_time' => $orderInfo['create_time']
        ];
        $detail = [
            'order_id' => $orderInfo['order_id'],
            'amount' => $orderInfo['amount'],
            'credit_card' => $orderInfo['credit_card'],
            'debit_card' => $orderInfo['debit_card']
        ];
        $db = DBQ::connect();
        $db->db->pdo->beginTransaction();
        $orderDao->add($order);
        $detailDao->addDetail($detail);
        return $db->db->pdo->commit();
    }
}
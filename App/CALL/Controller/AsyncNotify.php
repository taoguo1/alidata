<?php
/**
 * Created by jixiang.
 * User: pc
 * Date: 2018/5/6
 * Time: 11:46
 */

namespace App\CALL\Controller;


use App\WWW\Model\Order;
use App\WWW\Model\User;
use Core\Extend\FileLog;
use Core\Lib;
use Core\Sign;

class AsyncNotify
{
    const PARTNER_CODE = '18050217552114900000';
    const SECRET = 'e06f04fe2a2f1ffb62ff2da63899a87c';
    const NOTIFY = 'https://manage-dev.dizaozhe.cn/call/GatherNotice/yjfNotify';//todo 上线时修改成正式环境

    public function index ()
    {

        FileLog::setPath( 'yjf/asyncNotify' );
        $reqData = $_REQUEST;
        FileLog::setLog( '通知入参', $reqData, false );

        do {
            $error  = '';
            $status = 'success';
            if (empty( $reqData )) {
                $error = '响应数据为空';
                break;
            }
            $isSignCorret = Sign::checkNotifySign( $reqData[ 'sign' ], $reqData, self::SECRET );
            if (!$isSignCorret) {
                $error = '签名验证错误';
                break;
            }
            $orderId = $reqData[ 'partnerOrderNo' ];

            $orderModel = new Order();
            $order      = $orderModel->getOrder( $orderId );


            FileLog::setLog( '本地订单信息', $order, false, $orderId );
            if (empty( $order )) {
                $error = '订单不存在';
                break;
            }
            $result = false;
            switch ($order[ 'type' ]) {
                case 1:
                    $result = $this->channelCheckNotify( $reqData );
                    break;
                case 2:
                    $result = $this->openAccountNotify( $reqData );
                    break;
                case 3:
                    $result = $this->withdrawNotify( $reqData );

                    break;
                default:
                    break;
            }
            if (!$result) {
                $error = '订单更新失败';
                FileLog::setLog( '订单更新失败', '订单类型' . $order[ 'type' ], true );
                break;
            }
        } while (0);
        if (!empty( $error )) {
            FileLog::setLog( '错误信息', $error, true );
            $status = 'fail';
        }

        $data = [
            'status' => $status
        ];
        echo \json_encode($data);
        \fastcgi_finish_request();
        if ($reqData['status'] == 'SUCCESS') {
            $ret = $this->notify($reqData);
            FileLog::setLog( '通知下游响应结果', $ret, false );
        }
        FileLog::write();
    }

    protected function notify ($data)
    {

       $userModel = new User();
       $user = $userModel->getUserByOpenid($data['openId']);
       $orderModel = new Order();
       $orderDetail = $orderModel->getWithDrawDetail($data['partnerOrderNo']);

       $notifyData = [
           'appId' => $user['app_id'],
           'userId' => $user['user_id'],
           'amount' => $data['amount'],
           'receiveAmount' => $data['receiveAmount'],
           'orderId' => $data['partnerOrderNo'],
           'creditCard' => $orderDetail['credit_card'],
           'debitCard' => $orderDetail['debit_card']
       ];
        return Lib::httpPostUrlEncode(self::NOTIFY, $notifyData);
    }

    protected function channelCheckNotify($data)
    {
        $orderModel = new Order();
        $status = $data['status'] == 'SUCCESS' ? 1 : -1;
        $orderId = $data['partnerOrderNo'];
        $cardInfo = $orderModel->getChannelCheckCard($orderId);
        $cardNo = $cardInfo['card_no'];

        return $orderModel->updateChannelCheckOrderStatus($status, $orderId, $cardNo);
    }

    protected function openAccountNotify($data)
    {
        $orderModel = new Order();
        $status = $data['status'] == 'SUCCESS' ? 1 : -1;
        $orderId = $data['partnerOrderNo'];
        $openid = $data['openId'];
        return $orderModel->updateOrderOpenAccountStatus($status, $orderId, $openid);
    }

    protected function withdrawNotify($data)
    {

        $orderModel = new Order();
        $status =
            $data['status'] == 'SUCCESS' ? 1 : ($data['status'] == 'FAIL' ? -1 : 2) ;
        $outOrderId = !empty($data['orderNo']) ? $data['orderNo'] : '';
        $orderId = $data['partnerOrderNo'];
        $amount = $data['amount'];
        $receiveAmount = $data['receiveAmount'];
        $serviceFee = $data['chargeAmount'];
        $profitAmount = $data['profitAmount'];
        return $orderModel->updateWithDrawStatus(
            $status, $orderId, $amount, $receiveAmount, $serviceFee, $profitAmount, $outOrderId
        );
    }
}
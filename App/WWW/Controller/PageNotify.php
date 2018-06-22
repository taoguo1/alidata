<?php
/**
 * Created by jixiang.
 * User: pc
 * Date: 2018/5/7
 * Time: 18:29
 */

namespace App\WWW\Controller;


use Core\Base\Controller;
use Core\Extend\FileLog;
use Core\Lib;
use App\WWW\Model\ApiCall;
use App\WWW\Model\Order;


class PageNotify extends Controller
{
    const PARTNER_CODE = '18050217552114900000';
    const RETURN_URL = '/pageNotify/index';
    const NOTIFY_URL = '/call/asyncNotify/index';
    const SECRET = 'e06f04fe2a2f1ffb62ff2da63899a87c';

    protected $log = null;
    protected $apiUrl = '';
    protected $data = [];

    public function __construct ( $controller, $action )
    {
        parent::__construct( $controller, $action );
        $this->apiUrl = require_once(
            APP_PATH . 'App' . DIRECTORY_SEPARATOR . \strtoupper( RUN_PATH )
            . DIRECTORY_SEPARATOR . 'Model' . DIRECTORY_SEPARATOR . 'upstreamUrl.php' );
        $this->data = [
            'partnerCode' => self::PARTNER_CODE,
            'returnUrl'   => $_SERVER[ 'REQUEST_SCHEME' ] . '://' . $_SERVER[ 'SERVER_NAME' ] . self::RETURN_URL,
            'notifyUrl'   => $_SERVER[ 'REQUEST_SCHEME' ] . '://' . $_SERVER[ 'SERVER_NAME' ] . self::NOTIFY_URL,
        ];
        $this->data[ 'timestamp' ] = Lib::getMs();
    }

    public function index()
    {
        FileLog::setPath('yjf/pageNotify');
        $getData = $_GET;
        FileLog::setLog('PAGE回调通知入参', $getData);
        $apiJsUrl = $_SERVER[ 'REQUEST_SCHEME' ] . '://' . $_SERVER[ 'SERVER_NAME' ] . '/Static/js/api.js';

        do{
            $result = false;
            try{
                $orderId = $getData['partnerOrderNo'];
                $orderModel = new Order();
                $order = $orderModel->getOrder($orderId);
                $tplId = 0;
                switch($getData['method']) {
                    case 'channelInfoCheck':
                        $tplId = 1;
                        $result = $this->channelCheckCallback($orderId);
                        break;
                    case 'openPayAccount':
                        $tplId = 2;
                        $result = $this->openPayAccountCallback($order);
                        break;
                    case 'withdraw':
                        $tplId = 3;
                        $result = $this->queryOrderCallback($order);
                        break;
                    default:
                        break;
                }
            }catch(\Exception $e) {
                FileLog::setLog('更新订单抛出异常',$e->getMessage(),true);
                break;
            }
            if(!$result) {
                FileLog::setLog('更新订单', '更新失败',true, $orderId);
                break;
            }
        }while(0);
        FileLog::write();

        $tplName = 'index';
        if($tplId == 1) {
            $tplName = 'channelCheckCallback';//通道信息验证
        }
        if($tplId == 2) {
            $tplName = 'openPayAccountCallback';//注册支付账户
        }
        if($tplId == 3) {
            $tplName = 'queryOrderCallback';//提现下单
        }
        $this->assign('apiJsUrl', $apiJsUrl);
        $this->view( $tplName );
    }

    protected function channelCheckCallback($orderId)
    {
        $url = $this->apiUrl['baseUrl'].$this->apiUrl['api']['queryChannelInfoCheck'];
        $apiData = \array_merge(['partnerOrderNo' => $orderId], $this->data);

        try{
            FileLog::setLog('查询通道信息验证结果接口请求参数', $apiData,false, $orderId);
            $apiRet = ApiCall::query($url, self::SECRET, $apiData);

            FileLog::setLog('查询通道信息验证结果接口响应结果', $apiRet, false, $orderId);
            if(empty($apiRet)) {
                throw new \Exception('查询通道信息验证结果接口返回为空');
            }
            if($apiRet['success'] != 'true') {
                throw new \Exception('查询通道信息验证结果接口失败');
            }
            $apiRet = $apiRet['data'];
        }catch(\Exception $e){
            FileLog::setLog('查询可用通道错误',$e, true, $orderId);
            return false;
        };

        if($apiRet['status'] == 'PROCESS') {
            return true;
        }

        $orderModel = new Order();
        $status = $apiRet['status'] == 'SUCCESS' ? 1 : -1;
        $cardInfo = $orderModel->getChannelCheckCard($orderId);
        $cardNo = $cardInfo['card_no'];
        return $orderModel->updateChannelCheckOrderStatus($status, $orderId, $cardNo);
    }

    protected function openPayAccountCallback(array $order)
    {
        $url = $this->apiUrl['baseUrl'].$this->apiUrl['api']['queryPayAccount'];
        $openid = $order['open_id'];

        $apiData = \array_merge(['openId' => $openid], $this->data);

        try{
            FileLog::setLog('查询支付账户状态接口请求参数', $apiData,false, $order['order_id']);
            $apiRet = ApiCall::query($url, self::SECRET, $apiData);

            FileLog::setLog('查询支付账户状态接口响应结果', $apiRet, false, $order['order_id']);
            if(empty($apiRet)) {
                throw new \Exception('查询支付账户状态接口返回为空');
            }
            if($apiRet['success'] != 'true') {
                throw new \Exception('查询支付账户状态接口失败');
            }
            $apiRet = $apiRet['data'];
        } catch(\Exception $e) {
            FileLog::setLog('查询可用通道错误',$e, true, $order['order_id']);
            return false;
        }

        if($apiRet['status'] == 'PROCESS') {
            return true;
        }

        $orderModel = new Order();
        $status = $apiRet['status'] == 'SUCCESS' ? 1 : -1;
        $orderId = $order['order_id'];
        return $orderModel->updateOrderOpenAccountStatus($status, $orderId, $openid);
    }

    protected function queryOrderCallback(array $order)
    {
        $orderId = $order['order_id'];
        $url = $this->apiUrl['baseUrl'].$this->apiUrl['api']['queryOrder'];
        $apiData = \array_merge(['partnerOrderNo' => $orderId], $this->data);
        try{
            FileLog::setLog('查询提现订单接口请求参数', $apiData,false, $orderId);
            $apiRet = ApiCall::query($url, self::SECRET, $apiData);
            FileLog::setLog('查询提现订单接口响应结果', $apiRet, false, $orderId);
            if(empty($apiRet)) {
                throw new \Exception('查询提现订单接口返回为空');
            }
            if($apiRet['success'] != 'true') {
                throw new \Exception('查询提现订单接口失败');
            }
            $apiRet = $apiRet['data'];
        }catch(\Exception $e){
            FileLog::setLog('查询提现订单接口错误',$e, true, $orderId);
            return false;
        }

        if($apiRet['status'] == 'PROCESS') {
            return true;
        }

        $orderModel = new Order();
        $status =
            $apiRet['status'] == 'SUCCESS' ? 1 :
            $apiRet['status'] == 'FAIL' ? -1 : 2;

        $amount = $apiRet['amount'];
        $receiveAmount = $apiRet['receiveAmount'];
        $serviceFee = $apiRet['chargeAmount'];
        $profitAmount = $apiRet['profitAmount'];
        return $orderModel->updateWithDrawStatus(
            $status, $orderId, $amount, $receiveAmount, $serviceFee, $profitAmount
        );

    }
}
<?php
/**
 * Created by jixiang.
 * User: pc
 * Date: 2018/5/5
 * Time: 9:33
 */
namespace App\WWW\Controller;


use App\WWW\Model\User;
use App\WWW\Model\Order;
use App\WWW\Model\Card;
use App\WWW\Model\ApiCall;
use Core\Sign;
use Core\Lib;
use Core\Extend\FileLog;
use Core\Upload;

class PrePostData
{
    const SECRET = 'e06f04fe2a2f1ffb62ff2da63899a87c';
    const PARTNER_CODE = '18050217552114900000';
    const RETURN_URL = '/pageNotify/index';
    const NOTIFY_URL = '/call/asyncNotify/index';
    const CHANNEL_RATE = 0.35;//小额  大额为0.42
    const SERVICE_FEE = 0.5;

    protected $data = [];
    protected $upstreamUrl = [];

    public function __construct ()
    {
        $this->upstreamUrl = require_once(
            APP_PATH . 'App' . DIRECTORY_SEPARATOR . \strtoupper( RUN_PATH )
            . DIRECTORY_SEPARATOR . 'Model' . DIRECTORY_SEPARATOR . 'upstreamUrl.php' );
        $this->data = [
            'partnerCode' => self::PARTNER_CODE,
            'returnUrl'   => $_SERVER[ 'REQUEST_SCHEME' ] . '://' . $_SERVER[ 'SERVER_NAME' ] . self::RETURN_URL,
            'notifyUrl'   => $_SERVER[ 'REQUEST_SCHEME' ] . '://' . $_SERVER[ 'SERVER_NAME' ] . self::NOTIFY_URL,
        ];
        $this->data[ 'timestamp' ] = Lib::getMs();
    }

    /**
     * 提现
     */
    public function withDraw ()
    {

        FileLog::setPath( 'yjf/prepost/withdraw' );
        FileLog::setLog( '页面POST请求', $_POST );

        $creditCardNo = Lib::post( 'creditCardNo' );
        $isPromptly   = Lib::post( 'isPromptly' );
        $debitCardNo  = Lib::post( 'debitCardNo' );
        $amount       = Lib::post( 'amount' );
        $userId       = Lib::post( 'userId' );
        $appId        = Lib::post( 'appId' );


        do {
            $error     = '';
            $data      = [];
            //参数验证
            if(empty($creditCardNo)) {
                $error = '信用卡卡号不能为空';
                break;
            }
            if(empty($debitCardNo)) {
                $error = '借记卡卡号不能为空';
                break;
            }
            if(0 >= \intval($amount)) {
                $error = '提现金额必须大于0';
                break;
            }
            if(empty($userId)){
                $error = 'USER ID 不能为空';
                break;
            }
            if(empty($appId)) {
                $error = 'APP ID 不能为空';
                break;
            }


            $userModel = new User();
            $user      = $userModel->getUser( $userId, $appId );
            FileLog::setLog( '用户信息', $user, false, $userId.':'.$appId );
            if (empty( $user )) {
                $error = '用户不存在';
                break;
            }
            $cardModel  = new Card();
            $creditCard = $cardModel->getCard( $creditCardNo );
            FileLog::setLog( '用户信用卡信息', $creditCard, false, $creditCardNo );
            if (empty( $creditCard )) {
                $error = '卡信息不存在';
                break;
            }
            $orderModel = new Order();
            $orderId    = Lib::createOrderNo();
            $orderInfo  = [
                'order_id' => $orderId,
                'open_id'  => $user[ 'open_id' ],
                'amount'   => $amount,
                'debit_card' => $debitCardNo,
                'credit_card' => $creditCardNo
            ];
            try {
                FileLog::setLog( '创建订单数据', $orderInfo, false, $orderId );
                $orderModel->addOrder( $orderInfo, 3 );
            } catch (\Exception $e) {
                $error = '订单创建失败';
                $errLog = $e->getMessage();
                break;
            }

            //返回前台页面的数据
            $data[ 'partnerCode' ]    = $this->data[ 'partnerCode' ];
            $data[ 'timestamp' ]      = $this->data[ 'timestamp' ];
            $data[ 'returnUrl' ]      = $this->data[ 'returnUrl' ];
            $data[ 'notifyUrl' ]      = $this->data[ 'notifyUrl' ];
            $data[ 'partnerOrderNo' ] = $this->data[ 'partnerOrderNo' ] = $orderId;
            $data[ 'openId' ]         = $this->data[ 'openId' ] = $user[ 'open_id' ];
            $data[ 'channelId' ]      = $this->data[ 'channelId' ] = $creditCard[ 'channel_id' ];
            $data[ 'channelRate' ]    = $this->data[ 'channelRate' ] = self::CHANNEL_RATE;

            $this->data[ 'isPromptly' ]   = $isPromptly;
            $this->data[ 'amount' ]       = $amount;
            $this->data[ 'creditCardNo' ] = $creditCardNo;
            $this->data[ 'debitCardNo' ]  = $debitCardNo;
            $isPromptly == true ? $data[ 'serviceFee' ] = $this->data[ 'serviceFee' ] = self::SERVICE_FEE : null;
            $data[ 'sign' ]    = Sign::makeSign( $this->data, self::SECRET );
            $signLog           = $this->data;
            $signLog[ 'sign' ] = $data[ 'sign' ];
            FileLog::setLog( '生成签名数据', $signLog );
        } while (0);
        if (!empty( $error )) {
            $data = [ 'status' => 'fail', 'msg' => $error ];
            FileLog::setLog( '错误信息', $errLog, true );
        }
        FileLog::write();
        Lib::outputJson( $data );
    }

    public function addNewCard()
    {
        FileLog::setPath( 'yjf/prepost/addNewCard' );
        FileLog::setLog( '页面POST请求', $_POST );
        $creditCardNo = Lib::post( 'creditCardNo' );
        $realName     = Lib::post( 'realName' );
        $identityNo   = Lib::post( 'identityNo' );
        $phone        = Lib::post( 'phone' );
        $bankPhone    = Lib::post( 'bankPhone' );
        $userId       = Lib::post( 'userId' );
        $appId        = Lib::post( 'appId' );

        do{
            $data  = [];
            $error = '';

            //参数验证
            if(empty($creditCardNo)) {
                $error = '信用卡卡号不能为空';
                break;
            }
            if(empty($realName)) {
                $error = '用户真实姓名不能为空';
                break;
            }
            if (empty($identityNo)) {
                $error = '身份证号不能为空';
                break;
            }
            if(empty($phone)){
                $error = '联系电话不能为空';
                break;
            }
            if(!preg_match("/^1[34578]{1}\d{9}$/",$phone)) {
                $error = '联系电话格式错误';
                break;
            }
            if(empty($bankPhone)) {
                $error = '银行预留电话不能为空';
                break;
            }
            if(!preg_match("/^1[34578]{1}\d{9}$/",$phone)) {
                $error = '银行预留电话格式错误';
                break;
            }
            if(empty($userId)){
                $error = 'USER ID 不能为空';
                break;
            }
            if(empty($appId)) {
                $error = 'APP ID 不能为空';
                break;
            }

            //查询用户
            $userModel = new User();
            $user = $userModel->getUser($userId, $appId);
            if(empty($user)) {
                $error = '用户不存在';
                break;
            }
            $openid = $user['open_id'];

            //查询可用通道
            $userInfo = [
                'openId'       => $openid,
                'creditCardNo' => $creditCardNo
            ];
            $apiUrl   = $this->upstreamUrl[ 'baseUrl' ] . $this->upstreamUrl[ 'api' ][ 'queryChannelList' ];
            $apiData  = $this->data;
            $apiData  = \array_merge( $userInfo, $apiData );
            try {
                $apiRet = ApiCall::query( $apiUrl, self::SECRET, $apiData );
                if (empty( $apiRet )) {
                    $error = '查询可用通道失败';
                    break;
                }
                if ($apiRet[ 'success' ] != 'true') {
                    $error = '查询可用通道失败';
                    break;
                }
                $apiRet = $apiRet[ 'data' ];
            } catch (\Exception $e) {
                $errLog = $e->getMessage();
                break;
            }

            //创建信用卡信息
            $cardModel      = new Card();
            $creditCardInfo = [
                'open_id'    => $openid,
                'card_no'    => $creditCardNo,
                'bank_phone' => $bankPhone,
            ];
            foreach ($apiRet as $api) {
                $channelInfo[] = [
                    'open_id'             => $openid,
                    'card_no'             => $creditCardNo,
                    'channel_id'          => $api[ 'channelId' ],
                    'channel_name'        => $api[ 'channelName' ],
                    'single_amount_limit' => $api[ 'singleAmountLimit' ],
                    'day_amount_limit'    => $api[ 'dayAmountLimit' ],
                    'day_surplus_amount'  => $api[ 'daySurplusAmount' ]
                ];
            }
            FileLog::setLog( '创建信用卡_卡信息', $creditCardInfo );
            FileLog::setLog( '创建信用卡_通道信息', $channelInfo );
            try {
                $cardModel->addCard( $creditCardInfo, $channelInfo );
            } catch (\Exception $e) {
                $error = '创建信用卡失败';
                $errLog = $e->getMessage();
                break;
            }

            //创建通道验证订单
            $orderModel = new Order();
            $orderId    = Lib::createOrderNo();
            $orderInfo  = [
                'open_id'  => $openid,
                'order_id' => $orderId,
                'card_no' => $creditCardNo,
                'status'   => 2
            ];
            FileLog::setLog( '创建通道验证订单信息', $orderInfo );
            try {
                $orderModel->addOrder( $orderInfo, 1 );
            } catch (\Exception $e) {
                $error = '创建通道验证订单失败';
                $errLog = $e->getMessage();
                break;
            }
            //创建签名并返回前台页面的数据
            $data[ 'partnerCode' ]    = $this->data[ 'partnerCode' ];
            $data[ 'timestamp' ]      = $this->data[ 'timestamp' ];
            $data[ 'returnUrl' ]      = $this->data[ 'returnUrl' ];
            $data[ 'notifyUrl' ]      = $this->data[ 'notifyUrl' ];
            $data[ 'partnerOrderNo' ] = $this->data[ 'partnerOrderNo' ] = $orderId;
            $data[ 'openId' ]         = $this->data[ 'openId' ] = $openid;
            $data[ 'channelId' ]      = $this->data[ 'channelId' ] = \current( $channelInfo )[ 'channel_id' ];

            $this->data[ 'phone' ]        = $phone;
            $this->data[ 'creditCardNo' ] = $creditCardNo;
            $this->data[ 'realName' ]     = $realName;
            $this->data[ 'identityNo' ]   = $identityNo;
            $this->data[ 'bankPhone' ]    = $bankPhone;
            $data[ 'sign' ]               = Sign::makeSign( $this->data, self::SECRET );
            $signLog                      = $this->data;
            $signLog[ 'sign' ]            = $data[ 'sign' ];
            FileLog::setLog( '生成签名数据', $signLog );

        }while(0);
        if (!empty( $error )) {
            $data = [ 'status' => 'fail', 'msg' => $error ];
            FileLog::setLog( '错误信息', $errLog, true );
        }
        FileLog::write();
        Lib::outputJson( $data );
    }

    /**
     * 通道信息验证
     */
    public function channelInfoCheck ()
    {
        FileLog::setPath( 'yjf/prepost/channelInfoCheck' );
        FileLog::setLog( '页面POST请求', $_POST );
        $creditCardNo = Lib::post( 'creditCardNo' );
        $realName     = Lib::post( 'realName' );
        $identityNo   = Lib::post( 'identityNo' );
        $phone        = Lib::post( 'phone' );
        $bankPhone    = Lib::post( 'bankPhone' );
        $userId       = Lib::post( 'userId' );
        $appId        = Lib::post( 'appId' );
        $idFront      = Lib::post( 'idFront' );
        $idBack       = Lib::post( 'idBack' );


        do {
            $data  = [];
            $error = '';

            //参数验证
            if(empty($creditCardNo)) {
                $error = '信用卡卡号不能为空';
                break;
            }
            if(empty($realName)) {
                $error = '用户真实姓名不能为空';
                break;
            }
            if (empty($identityNo)) {
                $error = '身份证号不能为空';
                break;
            }
            if(empty($phone)){
                $error = '联系电话不能为空';
                break;
            }
            if(!preg_match("/^1[34578]{1}\d{9}$/",$phone)) {
                $error = '联系电话格式错误';
                break;
            }
            if(empty($bankPhone)) {
                $error = '银行预留电话不能为空';
                break;
            }
            if(!preg_match("/^1[34578]{1}\d{9}$/",$phone)) {
                $error = '银行预留电话格式错误';
                break;
            }
            if(empty($userId)){
                $error = 'USER ID 不能为空';
                break;
            }
            if(empty($appId)) {
                $error = 'APP ID 不能为空';
                break;
            }

            if (empty( $idFront )) {
                $error = '身份证正面照为空';
                break;
            }
            $idFront = $_SERVER[ 'REQUEST_SCHEME' ] . '://' . $_SERVER[ 'SERVER_NAME' ] . '/Uploads/yjf/' . $idFront;
            if (empty( $idBack )) {
                $error = '身份证背面照为空';
                break;
            }
            $idBack = $_SERVER[ 'REQUEST_SCHEME' ] . '://' . $_SERVER[ 'SERVER_NAME' ] . '/Uploads/yjf/' . $idBack;


            $userModel = new User();
            $user = $userModel->getUser($userId, $appId);
            //获取用户信息
            if(!empty($user)) {
                $openid = $user['open_id'];
            } else {
                //添加用户
                $openid = \uniqid();
                $userModel->addUser( $userId, $appId, $openid, $idFront, $idBack, $realName, $identityNo, $phone );
            }

            //查询可用通道
            $userInfo = [
                'openId'       => $openid,
                'creditCardNo' => $creditCardNo
            ];
            $apiUrl   = $this->upstreamUrl[ 'baseUrl' ] . $this->upstreamUrl[ 'api' ][ 'queryChannelList' ];
            $apiData  = $this->data;
            $apiData  = \array_merge( $userInfo, $apiData );
            try {
                $apiRet = ApiCall::query( $apiUrl, self::SECRET, $apiData );
                if (empty( $apiRet )) {
                    $error = '查询可用通道失败';
                    break;
                }
                if ($apiRet[ 'success' ] != 'true') {
                    $error = '查询可用通道失败';
                    break;
                }
                $apiRet = $apiRet[ 'data' ];
            } catch (\Exception $e) {
                $errLog = $e->getMessage();
                break;
            }

            //创建信用卡信息
            $cardModel      = new Card();
            $creditCardInfo = [
                'open_id'    => $openid,
                'card_no'    => $creditCardNo,
                'bank_phone' => $bankPhone,
            ];
            foreach ($apiRet as $api) {
                $channelInfo[] = [
                    'open_id'             => $openid,
                    'card_no'             => $creditCardNo,
                    'channel_id'          => $api[ 'channelId' ],
                    'channel_name'        => $api[ 'channelName' ],
                    'single_amount_limit' => $api[ 'singleAmountLimit' ],
                    'day_amount_limit'    => $api[ 'dayAmountLimit' ],
                    'day_surplus_amount'  => $api[ 'daySurplusAmount' ]
                ];
            }
            FileLog::setLog( '创建信用卡_卡信息', $creditCardInfo );
            FileLog::setLog( '创建信用卡_通道信息', $channelInfo );
            try {
                $cardModel->addCard( $creditCardInfo, $channelInfo );
            } catch (\Exception $e) {
                $error = '创建信用卡失败';
                $errLog = $e->getMessage();
                break;
            }

            //创建通道验证订单
            $orderModel = new Order();
            $orderId    = Lib::createOrderNo();
            $orderInfo  = [
                'open_id'  => $openid,
                'order_id' => $orderId,
                'card_no' => $creditCardNo,
                'status'   => 2
            ];
            FileLog::setLog( '创建通道验证订单信息', $orderInfo );
            try {
                $orderModel->addOrder( $orderInfo, 1 );
            } catch (\Exception $e) {
                $error = '创建通道验证订单失败';
                $errLog = $e->getMessage();
                break;
            }
            //创建签名并返回前台页面的数据
            $data[ 'partnerCode' ]    = $this->data[ 'partnerCode' ];
            $data[ 'timestamp' ]      = $this->data[ 'timestamp' ];
            $data[ 'returnUrl' ]      = $this->data[ 'returnUrl' ];
            $data[ 'notifyUrl' ]      = $this->data[ 'notifyUrl' ];
            $data[ 'partnerOrderNo' ] = $this->data[ 'partnerOrderNo' ] = $orderId;
            $data[ 'openId' ]         = $this->data[ 'openId' ] = $openid;
            $data[ 'channelId' ]      = $this->data[ 'channelId' ] = \current( $channelInfo )[ 'channel_id' ];

            $this->data[ 'phone' ]        = $phone;
            $this->data[ 'creditCardNo' ] = $creditCardNo;
            $this->data[ 'realName' ]     = $realName;
            $this->data[ 'identityNo' ]   = $identityNo;
            $this->data[ 'bankPhone' ]    = $bankPhone;
            $data[ 'sign' ]               = Sign::makeSign( $this->data, self::SECRET );
            $signLog                      = $this->data;
            $signLog[ 'sign' ]            = $data[ 'sign' ];
            FileLog::setLog( '生成签名数据', $signLog );
        } while (0);
        if (!empty( $error )) {
            $data = [ 'status' => 'fail', 'msg' => $error ];
            FileLog::setLog( '错误信息', $errLog, true );
        }
        FileLog::write();
        Lib::outputJson( $data );
    }

    /**
     * 注册支付账户
     */
    public function openPayAccount ()
    {

        FileLog::setPath( 'yjf/prepost/openPayAccount' );
        FileLog::setLog( '页面POST请求', $_POST );

        $userId = Lib::post( 'userId' );
        $appId  = Lib::post( 'appId' );
        do {
            $data      = [];
            $error     = '';
            if(empty($userId)){
                $error = 'USER ID 不能为空';
                break;
            }
            if(empty($appId)) {
                $error = 'APP ID 不能为空';
                break;
            }
            $userModel = new User();
            $user      = $userModel->getUser( $userId, $appId );
            FileLog::setLog( '用户信息', $user, false, $userId . ':' . $appId );
            if (empty( $user )) {
                $error = '用户不存在';
                break;
            }

            $orderModel = new Order();
            $orderId    = Lib::createOrderNo();
            $orderInfo  = [
                'open_id'  => $user[ 'open_id' ],
                'order_id' => $orderId,
                'status'   => 2
            ];
            FileLog::setLog( '订单创建信息', $orderInfo, false, $orderId );
            try {
                $orderModel->addOrder( $orderInfo, 2 );
            } catch (\Exception $e) {
                $error = '订单添加失败';
                $errLog = $e->getMessage();
                break;
            }
            $data[ 'partnerCode' ] = $this->data[ 'partnerCode' ];
            $data[ 'timestamp' ]   = $this->data[ 'timestamp' ];
            $data[ 'returnUrl' ]   = $this->data[ 'returnUrl' ];
            $data[ 'notifyUrl' ]   = $this->data[ 'notifyUrl' ];

            $data[ 'identityFrontUrl' ] = $this->data[ 'identityFrontUrl' ] = $user[ 'id_front' ];
            $data[ 'identityBackUrl' ]  = $this->data[ 'identityBackUrl' ] = $user[ 'id_back' ];
            $data[ 'openId' ]           = $this->data[ 'openId' ] = $user[ 'open_id' ];
            $data[ 'partnerOrderNo' ]   = $this->data[ 'partnerOrderNo' ] = $orderId;
            $data[ 'sign' ]             = Sign::makeSign( $this->data, self::SECRET );
            $signLog                    = $this->data;
            $signLog[ 'sign' ]          = $data[ 'sign' ];
            FileLog::setLog( '生成签名数据', $signLog );
        } while (0);
        if (!empty( $error )) {
            $data = [ 'status' => 'fail', 'msg' => $error ];
            FileLog::setLog( '错误信息', $errLog, true );
        }
        FileLog::write();
        Lib::outputJson( $data );
    }

    public function uploadImage ()
    {
        $imgUploader = new Upload();
        $imgUploader->set('path', 'Uploads/yjf/');
        $error       = '';
        $data        = [];
        do {
            if (empty( $_FILES )) {
                $error = '上传文件为空';
                break;
            }
            if (empty( $_FILES[ 'idFront' ] )) {
                $error = '未上传身份证正面照片';
                break;
            }
            if (empty( $_FILES[ 'idBack' ] )) {
                $error = '未上传身份证背面照片';
                break;
            }
            $ret = $imgUploader->upload( 'idFront' );
            if (!$ret) {
                $error = $imgUploader->getErrorMsg();
                break;
            }
            $idFront = $imgUploader->getFileName();

            $ret = $imgUploader->upload( 'idBack' );
            if (!$ret) {
                $error = $imgUploader->getErrorMsg();
                break;
            }
            $idBack = $imgUploader->getFileName();
            $data   = [
                'idFront' => $idFront,
                'idBack'  => $idBack
            ];
        } while (0);
        $data = [ 'status' => 'success', 'msg' => '', 'data' => $data ];
        if ($error) {
            $data = [ 'status' => 'fail', 'msg' => $error ];
        }
        Lib::outputJson( $data );
    }
}
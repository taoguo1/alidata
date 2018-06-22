<?php

namespace App\WWW\Controller;


use Core\Base\Controller;
use Core\Lib;
use App\WWW\Model\Card;
use App\WWW\Model\User;

class YjPay extends Controller
{
    protected $upstreamUrl = [];

    public function __construct ( $controller, $action )
    {
        parent::__construct( $controller, $action );
        $this->upstreamUrl = require_once(
            APP_PATH . 'App' . DIRECTORY_SEPARATOR . \strtoupper( RUN_PATH )
            . DIRECTORY_SEPARATOR . 'Model' . DIRECTORY_SEPARATOR . 'upstreamUrl.php'
        );
    }

    public function index ()
    {

        $userId = Lib::request( 'userId' );
        $appId = Lib::request('appId');

        if(empty($userId)) {
            Lib::outputJson(['status' => 'fail', 'msg' => 'USER ID 不能为空']);
        }
        if(empty($appId)) {
            Lib::outputJson(['status' => 'fail', 'msg' => 'APP ID 不能为空']);
        }

        do {
            $userModel = new User();
            $user      = $userModel->getUser( $userId, $appId );

            if (empty( $user )) {
                $this->channelInfoCheck( $userId, $appId );
                break;
            }

            $cardModel   = new Card();
            $creditCards = $cardModel->getVerifiedCards( $user[ 'open_id' ] );

            if (empty( $creditCards )) {
                $this->channelInfoCheck( $userId, $appId );
                break;
            }

            if (1 != $user[ 'is_auth_account' ]) {
                $this->openPayAccount( $userId, $appId );
                break;
            }

            $this->withDraw( $user, $creditCards );
        } while (0);
    }

    public function addNewCard()
    {
        $userId = Lib::request( 'userId' );
        $appId = Lib::request('appId');

        if(empty($userId)) {
            Lib::outputJson(['status' => 'fail', 'msg' => 'USER ID 不能为空']);
        }
        if(empty($appId)) {
            Lib::outputJson(['status' => 'fail', 'msg' => 'APP ID 不能为空']);
        }
        $formUrl = $this->upstreamUrl['baseUrl'].$this->upstreamUrl['page']['channelInfoCheck'];
        $preFormUrl = '/prePostData/addNewCard';
        $jqueryUrl = $_SERVER[ 'REQUEST_SCHEME' ] . '://' . $_SERVER[ 'SERVER_NAME' ] . '/Static/Admin/js/jquery-2.1.4.js';
        $getSignUrl = $_SERVER[ 'REQUEST_SCHEME' ] . '://' . $_SERVER[ 'SERVER_NAME' ]. '/Static/js/yjf/getSign.js';
        $cssUrl = $_SERVER[ 'REQUEST_SCHEME' ] . '://' . $_SERVER[ 'SERVER_NAME' ] . '/Static/css/yjf/core.css';
        $this->assign( 'formUrl', $formUrl );
        $this->assign( 'preFormUrl', $preFormUrl );
        $this->assign( 'userId', $userId );
        $this->assign('appId', $appId);
        $this->assign('jqueryUrl', $jqueryUrl);
        $this->assign('getSignUrl', $getSignUrl);
        $this->assign('cssUrl', $cssUrl);
        $this->view( __FUNCTION__ );
    }

    //通道信息验证页面
    public function channelInfoCheck ( $userId, $appId )
    {
        $formUrl = $this->upstreamUrl['baseUrl'].$this->upstreamUrl['page']['channelInfoCheck'];
        $preFormUrl = '/prePostData/channelInfoCheck';
        $uploadImgUrl = '/prePostData/uploadImage';
        $jqueryUrl = $_SERVER[ 'REQUEST_SCHEME' ] . '://' . $_SERVER[ 'SERVER_NAME' ] . '/Static/Admin/js/jquery-2.1.4.js';
        $getSignUrl = $_SERVER[ 'REQUEST_SCHEME' ] . '://' . $_SERVER[ 'SERVER_NAME' ]. '/Static/js/yjf/getSign.js';
        $uploadJsUrl = $_SERVER[ 'REQUEST_SCHEME' ] . '://' . $_SERVER[ 'SERVER_NAME' ] .  '/Static/js/yjf/uploadImg.js';
        $cssUrl = $_SERVER[ 'REQUEST_SCHEME' ] . '://' . $_SERVER[ 'SERVER_NAME' ] . '/Static/css/yjf/core.css';
        $this->assign( 'formUrl', $formUrl );
        $this->assign( 'preFormUrl', $preFormUrl );
        $this->assign( 'userId', $userId );
        $this->assign('appId', $appId);
        $this->assign('uploadImgUrl', $uploadImgUrl);
        $this->assign('jqueryUrl', $jqueryUrl);
        $this->assign('getSignUrl', $getSignUrl);
        $this->assign('uploadJsUrl', $uploadJsUrl);
        $this->assign('cssUrl', $cssUrl);
        $this->view( __FUNCTION__ );
    }

    //提现页面
    public function withDraw ( $user, $creditCards )
    {
        $formUrl = $this->upstreamUrl['baseUrl'].$this->upstreamUrl['page']['withDraw'];
        $preFormUrl = '/prePostData/withDraw';
        $jqueryUrl = $_SERVER[ 'REQUEST_SCHEME' ] . '://' . $_SERVER[ 'SERVER_NAME' ] . '/Static/Admin/js/jquery-2.1.4.js';
        $getSignUrl = $_SERVER[ 'REQUEST_SCHEME' ] . '://' . $_SERVER[ 'SERVER_NAME' ]. '/Static/js/yjf/getSign.js';
        $cssUrl = $_SERVER[ 'REQUEST_SCHEME' ] . '://' . $_SERVER[ 'SERVER_NAME' ] . '/Static/css/yjf/core.css';
        $this->assign( 'preFormUrl', $preFormUrl );
        $this->assign( 'formUrl', $formUrl );
        $this->assign( 'user', $user );
        $this->assign( 'creditCards', $creditCards );
        $this->assign('jqueryUrl', $jqueryUrl);
        $this->assign('getSignUrl', $getSignUrl);
        $this->assign('cssUrl', $cssUrl);
        $this->view( __FUNCTION__ );
    }

    //开通支付账户页面
    public function openPayAccount ( $userId, $appId )
    {
        $formUrl = $this->upstreamUrl['baseUrl'].$this->upstreamUrl['page']['openPayAccount'];
        $preFormUrl = '/prePostData/openPayAccount';
        $jqueryUrl = $_SERVER[ 'REQUEST_SCHEME' ] . '://' . $_SERVER[ 'SERVER_NAME' ] . '/Static/Admin/js/jquery-2.1.4.js';
        $getSignUrl = $_SERVER[ 'REQUEST_SCHEME' ] . '://' . $_SERVER[ 'SERVER_NAME' ]. '/Static/js/yjf/getSign.js';
        $this->assign( 'preFormUrl', $preFormUrl );
        $this->assign( 'formUrl', $formUrl );
        $this->assign( 'userId', $userId );
        $this->assign('appId', $appId);
        $this->assign('jqueryUrl', $jqueryUrl);
        $this->assign('getSignUrl', $getSignUrl);
        $this->view( __FUNCTION__ );
    }
}
<?php
/**
 * Created by jixiang.
 * User: pc
 * Date: 2018/4/24
 * Time: 14:02
 */

namespace App\WWW\Controller;


use Core\Base\Controller;
use Core\Lib;
use Core\Extend\FileLog as Log;

class Overseas extends Controller
{
    protected $organNo = 'wp201804170001';
    protected $secret = '2MBSUUFH3B3SSWV39MFNGBAB3MUHUO2H';
    protected $apiUrl = 'http://worldpay.appkfz.cn:8128/mpcctp/payment/api/WP0002.do';
    protected $currencyCode = 'MYR';
    protected $version = 'V1.0.0';
    const MIN_AMT = 100;//RMB
    const MAX_AMT = 500;//RMB

    public function index()
    {
        Log::setPath('myr/api', '');
        $data = [];
        $appid = Lib::request('appid');
        $data['appid'] = $appid;
		$data['amt']=rand(65,80);

        if (empty($appid)) {
            $error = $data['error'] = '无效的APPID，请检查APPID是否存在';
            Log::setLog('API接口错误', $error, true);
            $this->assign('data',$data);
            $this->view();
            exit;
        }

        if($this->M()->isPost()) {
            $appid = Lib::post('appid');
            $name = Lib::post('name');
            $card = Lib::post('card');
            $idNo = Lib::post('idno');
            $phone = Lib::post('phone');
            $foreignAmt = Lib::post('amt');
            $data = [
                'appid' => $appid,
                'name' => $name,
                'card' => $card,
                'idno' => $idNo,
                'phone' => $phone,
                'amt' => $foreignAmt
            ];
            $log['postData'] = $data;
            $error = '';

            //计算 08:00 点到 21:00 的 时间
            $time = \time();
            $date = \date("Y-m-d",$time);
            $startTime =  \strtotime($date." 08:00:00");
            $endTime  =  \strtotime($date." 21:00:00");

            do {
                if ($time < $startTime || $time > $endTime) {
                    $error = '境外消费时间为每日08:00:00 - 21:00:00';
                    break;
                }
                if (empty($name)) {
                    $error = '持卡人姓名不能为空';
                    break;
                }
                if (empty($card)) {
                    $error = '卡号不能为空';
                    break;
                }
                if (empty($idNo)) {
                    $error = '身份证号不能为空';
                    break;
                }
                $idNoLen = \strlen($idNo);
                if($idNoLen != 15 && $idNoLen != 18) {
                    $error = '身份证号错误';
                    break;
                }
                if (empty($phone)) {
                    $error = '手机号不能为空';
                    break;
                }
                if(!preg_match( '/^1[34578]\d{9}$/', $phone )) {
                    $error = '手机号格式错误';
                    break;
                }
                if (empty($foreignAmt)) {
                    $error = '消费金额不能为空';
                    break;
                }
                if(!\filter_var($foreignAmt, FILTER_VALIDATE_FLOAT,'decimal')) {
                    $error = '支付金额格式错误';
                    break;
                }
                if($this->M()->isLess20Minutes($card)) {
                    $error = '两次支付间隔时间必须大于20分钟';
                    break;
                }
                if($this->M()->isMaxCount($card)) {
                    $error = '当日支付次数不能超过3次';
                    break;
                }
                $foreignAmtFmt = $foreignAmt * 100;
                if($this->M()->isSameAmount($card, $foreignAmtFmt)) {
                    $error = '金额不能和上一次支付的金额相等';
					$data['amt']=rand(65,80);
					$this->assign('data',$data);
                    break;
                }
                $rmbAmtFmt = $this->M()->getRmbFmt($foreignAmtFmt);

                if($rmbAmtFmt < (self::MIN_AMT  *  100)) {
                    $error = '所输入的林吉特金额换算成人民币金额不能小于'.self::MIN_AMT.'元';
                    break;
                }

                if($rmbAmtFmt > (self::MAX_AMT  * 100)) {
                    $error = '所输入的林吉特金额换算成人民币金额不能大于'.self::MAX_AMT.'元';
                    break;
                }

                $mediaType = '02';
                $localOrderId = $this->M()->serialGen('OV', $appid);
                $insertData = [
                    'appid' => $appid,
                    'order_id' => $localOrderId,
                    'pay_amt' => $rmbAmtFmt,
                    'foreign_amt' => $foreignAmtFmt,
                    'media_type' => $mediaType,
                    'card_no' => $card,
                    'card_holder' => $name,
                    'id_no' => $idNo,
                    'phone' => $phone,
                    'status' => -1,
                    'order_date' => Lib::getMs(),
                ];
                $ret = $this->M()->addOrder($insertData);
                if(empty($ret)) {
                    $error = '订单生成失败，请稍后再试!';
                    break;
                }
                $limitData = [
                    'count[+]' => 1,
                    'amt' => $foreignAmtFmt,
                    'time' => \time()
                ];
                $this->M()->updateLimit($limitData, $card);

                $apiData = [
                    'version' => $this->version,
                    'organNo' => $this->organNo,
                    'payAmt' => \strval($rmbAmtFmt),
                    'foreignCurrencyAmt' => \strval($foreignAmtFmt),
                    'mediaType' => $mediaType,
                    'cardNo' => $card,
                    'currencyCode' => $this->currencyCode,
                    'cardHolder' => $name,
                    'certificateNo' => $idNo,
                    'phone' => $phone
                ];
                $log['apiRequest'] = $apiData;
                $apiData['signature'] = $this->M()->getSign($apiData, $this->secret);
                $ret = $this->M()->apiCall($this->apiUrl, $apiData);
                $log['apiResponse'] = $ret;
                Log::setLog('API接口响应数据', $log, false, $localOrderId);
                if ('0000' != $ret['respCode']) {
                    $error = $ret['respDesc'];
                    break;
                }
                $updData = [
                    'out_order_id' => $ret['orderNo']
                ];
                $this->M()->updOrder($updData, $localOrderId);
                $url = $ret['noCardPayUrl'];
                $this->M()->redirect($url);
            } while(0);

            if ($error) {
                $data['error'] = $error;
                Log::setLog('API接口错误', $error, true, $localOrderId);
            }
        }
        $this->assign('data',$data);
        $this->view();
    }

    public function __destruct ()
    {
        Log::write();
    }
}
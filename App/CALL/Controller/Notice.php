<?php
namespace App\CALL\Controller;

use Core\Base\Controller;
use Core\Lib;
use Core\DB\DBQ;
use Core\Extend\FileLog as Log;

class Notice extends Controller
{
    protected $organNo = 'wp201804170001';
    protected $secret = '2MBSUUFH3B3SSWV39MFNGBAB3MUHUO2H';
    protected $apiUrl = 'http://worldpay.appkfz.cn:8128/mpcctp/payment/api/WP0002.do';
    protected $currencyCode = 'MYR';
    protected $version = 'V1.0.0';

    public function index(){
        Log::setPath('myr/notice','');
        $json = file_get_contents("php://input");
        $rejson = json_decode($json);
        if ($rejson == null) {
            Log::setLog('通知接口错误', '返回JSON格式有误，无法解析', true);
            exit;
        }
        $outTradeNo = $rejson->outTradeNo;
        Log::setLog('通知接口入参', $json, false, $outTradeNo);
        $error = '';
        if($this->M()->checkSign($json, $this->secret)){
            $data = [
                'done_date' => \strtotime($rejson->doneDate),
                'status' => 1,
            ];
            $row = DBQ::getRow('myr_order','*',['out_order_id' => $outTradeNo]);
            $merc = DBQ::getRow('merc',['jwpay_earnings'],['appid' => $row['appid']]);
            if($rejson->money >= $row['foreign_amt']){
                if($rejson->done == true){
                    DBQ::upd('myr_order',$data,['out_order_id' => $outTradeNo]);
                    $profitData = [
                        'appid' => $row['appid'],
                        'order_id' => $row['order_id'],
                        'out_order_id' => $outTradeNo,
                        'profit' => ($row['foreign_amt'] - ( $row['foreign_amt'] * $merc['jwpay_earnings'] / 10000)) / 100,
                        'oem_profit' => ($row['foreign_amt'] * $merc['jwpay_earnings'] / 10000) / 100,
                        'amount'=> $row['foreign_amt'] / 100,
                        'create_time' => Lib::getMs()
                    ];
                    if (!empty($merc['jwpay_earnings'])) {
                        $profitData['rate'] = $merc['jwpay_earnings'];
                    } else {
                        $profitData['rate'] = 0;
                    }
                    DBQ::add('myr_profit',$profitData);

                    $db = $this->M()->getDb($row['appid']);
                    $oemProfit = [
                        'order_id' => $row['order_id'],
                        'profit' => ($row['foreign_amt'] * $merc['jwpay_earnings'] / 10000) / 100,
                        'amount' => $row['foreign_amt'] / 100,
                        'rate' => $merc
                        ['jwpay_earnings'],
                        'create_time' => Lib::getMs()
                    ];
                    if (!empty($merc['jwpay_earnings'])) {
                        $oemProfit['rate'] = $merc['jwpay_earnings'];
                    } else {
                        $oemProfit['rate'] = 0;
                    }
                    $db->insert('myr_profit',$oemProfit);
                } else {
                    $error = '接口通知支付状态为失败';
                }
            }else{
                $error = '接口通知返回金额与本地金额不一致';
            }
        }else{
            $error = '接口通知验签错误';
        }
        if($error) {
            Log::setLog('通知接口错误', $error,true, $outTradeNo);
        }
        
    }

    public function __destruct ()
    {
        Log::write();
    }

}
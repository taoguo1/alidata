<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2018/1/19
 * Time: 15:03
 */
namespace App\ADMIN\Model;
use Core\Lib;
use Core\DB\DBQ;
use Core\Base\Model;
class Merc extends Model
{
    public function getList($pageArr = null, $condition = null)
    {
        $sql = "SELECT A.* FROM dzz_merc A " . $condition;
        $data = DBQ::origPage($pageArr, $sql);
        return $data;
    }

 

    public function edit($data, $id)
    {
        return DBQ::upd("merc", $data, [
            'id' => $id
        ]);
    }
    public function updateConfig($id = 0)
    {
        $list = DBQ::getRow('merc', '*', [
            'id' => $id
        ]);
        $appid = $list['appid'];
        $appsecret = $list['appsecret'];
        $data = Lib::json($list);
        $file = APP_PATH.'Config/MercConfig/'.$appid."_Config.php";
        file_put_contents($file, $data);
        if(file_exists($file))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    public function updaterate($id = 0)
    {
        $data = DBQ::getRow('merc', '*', [
            'id' => $id
        ]);
        $appid = $data['appid'];
        $payHandle = new \App\WWW\Model\Paf($appid);
        //设置费率,请求代付，代收接口前需要设置费率，以和D支付平台费率保持一致
        $ratePost = [
            'merchant_id' => $data['merchant_id'],
            'czValue'  => $data['deposit_poundage'],
            'czValue'  => $data['deposit_poundage'],             //充值手续费
            'txValue'  => $data['withdraw_poundage'],            //提现手续费
            'xfValue'  => $data['money_out_poundage'],           //消费手续费
            'jqValue'  => $data['validatecard_poundage'],        //鉴权手续费
            'hkValue'  => $data['repayment_poundage'],           //还款手续费
            'sfValue' => $data['sfvalue'],                       //身份鉴权手续费
            'txInValue' => $data['txlnvalue'],                   //套现入款手续费
            'txOutValue' => $data['txoutvalue'],                 //套现出款手续费
        ];
        foreach($ratePost as $k => $v){ if(empty($v)){ return false; } }
        $rateResult = $payHandle->payRateAll($ratePost);
        if($rateResult['error'] == 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}
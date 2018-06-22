<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2018/1/19
 * Time: 14:27
 */
namespace App\ADMIN\Controller;

use Core\Base\Controller;
use Core\DB\DBQ;
use Core\Lib;
use Core\Extend\Dwz;
use App\WWW\Model\Cron;

class Merc extends Controller
{

    /**
     *
     * @name 查询商家
     */
    public function index()
    {
        $appid = Lib::request('appid');
        $app_name = Lib::request('app_name');
        $status = Lib::request('status');
        $start_date = Lib::request('start_date');
        $end_date = Lib::request('end_date');

        $condition = " WHERE 1";
        if ($appid) {
            $condition .= " and appid like '%" . $appid . "%'";
        }
        if ($app_name) {
            $condition .= " and app_name like '%" . $app_name . "%'";
        }
        if ($status) {
            $condition .= " and status = '" . $status . "'";
        }
        
        if ($start_date || $end_date) {
            $condition .= " and A.create_time between " . (strtotime($start_date)) * 1000 . " and " . (strtotime($end_date)) * 1000;
        }
        
        $pageArr = Lib::setPagePars();
        if ($pageArr['orderField']) {
            $columns['ORDER'] = [
                $pageArr['orderField'] => strtoupper($pageArr['orderDirection'])
            ];
        }
        
        $data = $this->M()->getList($pageArr, $condition);
         $this->assign("appid", $appid);
        $this->assign("data", $data);
        $this->view();
    }

    /**
     *
     * @name 添加商家
     */
    public function add($act = null)
    {
        if ($act == 'add') {
            if ($this->M()->has('merc', [
                'app_name' => Lib::post('app_name')
            ])) {
                Dwz::err('该应用已存在');
            }
            if ($this->M()->has('merc', [
                'db_name' => Lib::post('db_name')
            ])) {
                Dwz::err('数据库名称不能重复');
            }
            
            $key = Lib::post('app_name') . Lib::getMs();            
            $appid = substr(md5($key . rand(1000, 9999)), 8, 16);
            
            $appsecret = md5(md5($key . rand(10000, 99999)));

//            $keys_oss = ['accessKeyId','accessKeySecret','endpoint','bucket'];
//            $value_oss = \explode("&",trim(Lib::post('oss_config')));
//            $oss_config = json_encode(array_combine($keys_oss,$value_oss));
//
//            $keys_jpush = ['appKey','masterSecret','apnsProduction'];
//            $value_jpush = \explode("&",trim(Lib::post('jpush_config')));
//            $jpush_config = json_encode(array_combine($keys_jpush,$value_jpush));
//
//            $keys_sms = ['SignName','accessKeyId','accessKeySecret'];
//            $value_sms = \explode("&",trim(Lib::post('sms_config')));
//            $sms_config = json_encode(array_combine($keys_sms,$value_sms));
            $data = [

                'app_name' => Lib::post('app_name'),
                'appid' => $appid,
                'appsecret' => $appsecret,
                'db_ip' => Lib::post('db_ip'),
                'db_name' => Lib::post('db_name'),
                'db_user' => Lib::post('db_user'),
                'db_password' => Lib::post('db_password'),
                'db_port' => Lib::post('db_port'),
                'db_prefix' => Lib::post('db_prefix'),
                'oss_enddomain' => Lib::post('oss_enddomain'),
                'ex_service' => Lib::post('ex_service'),
                'sign_code' => Lib::post('sign_code'),
                'repayment_poundage' => Lib::post('repayment_poundage'),
                'deposit_poundage' => Lib::post('deposit_poundage'),
                'withdraw_poundage' => Lib::post('withdraw_poundage'),
                'validatecard_poundage' => Lib::post('validatecard_poundage'),
                'tx_in' => Lib::post('tx_in'),
                'tx_out' => Lib::post('tx_out'),
                'tx_agent_rate' => Lib::post('tx_agent_rate'),
                'repayment_rate' => Lib::post('repayment_rate'),
                'max_withdraw_day' => Lib::post('max_withdraw_day'),
                'bonus_day_max' => Lib::post('bonus_day_max'),
                'deposit_co' => Lib::post('deposit_co'),
                'max_r_amount' => Lib::post('max_r_amount'),
                'min_r_amount' => Lib::post('min_r_amount'),
                'max_r_sin_amount' => Lib::post('max_r_sin_amount'),
                'setnotifyurl' => Lib::post('setnotifyurl'),
                'channel_in_url' => Lib::post('channel_in_url'),
                'channel_out_url' => Lib::post('channel_out_url'),
                'channel_in_query_url' => Lib::post('channel_in_query_url'),
                'channel_out_query_url' => Lib::post('channel_out_query_url'),
                'redis_config' => Lib::post('redis_config') ,
                'oss_config' => Lib::post('oss_config'),
                'jpush_config' => Lib::post('jpush_config'),
                'sms_config' => Lib::post('sms_config'),
                'credit_card_url' => Lib::post('credit_card_url'),
                'insurance_url' => Lib::post('insurance_url'),
                'oem_repayment_poundage'=>Lib::post('oem_repayment_poundage'),
                'money_out_poundage'=>Lib::post('money_out_poundage'),
                'loan_url' => Lib::post('loan_url'),
                'status' => Lib::post('status'),
                'kcp_earnings' => Lib::post('kcp_earnings'),
                'jwpay_earnings' => Lib::post('jwpay_earnings'),
                'is_show' => Lib::post('is_show'),
                'merchant_id' => Lib::post('merchant_id'),
                'merchant_key' => Lib::post('merchant_key'),
                'payment_url' => Lib::post('payment_url'),
                'ios_down_url' => Lib::post('ios_down_url'),
                'android_down_url' => Lib::post('android_down_url'),
                'version_number' => Lib::post('version_number'),
                'new_version_number' => Lib::post('new_version_number'),
                'xf_service_charge' => Lib::post('xf_service_charge'),
                'abroad_url' => Lib::post('abroad_url'),
                'a_deposit_key' => Lib::post('a_deposit_key'),
                'deposit_key' => Lib::post('deposit_key'),
                'sfvalue' => Lib::post('sfvalue'),
                'txlnvalue' => Lib::post('txlnvalue'),
                'txoutvalue' => Lib::post('txoutvalue'),
                'create_time' => Lib::getMs()
            ];
         
            $insert = $this->M()->db->insert('merc', $data);
            
            if ($insert) {
                
                $id = DBQ::getOne('merc', 'id', [
                    'appid' => $appid
                ]);
                $ret = $this->M()->updateConfig($id);
                if (! $ret) {
                    Dwz::err('配置文件更新失败，请手动更新');
                }
                Dwz::successDialog($this->M()->modelName, '', 'closeCurrent');
            } else {
                Dwz::err();
            }
        }
        $this->view();
    }

    /**
     *
     * @name 编辑商家
     */
    public function edit($id = 0, $act = null)
    {

        if ($act == 'edit') {
            $data = [
                'app_name' => Lib::post('app_name'),
                'db_ip' => Lib::post('db_ip'),
                'db_name' => Lib::post('db_name'),
                'db_user' => Lib::post('db_user'),
                'db_password' => Lib::post('db_password'),
                'db_port' => Lib::post('db_port'),
                'db_prefix' => Lib::post('db_prefix'),
                'oss_enddomain' => Lib::post('oss_enddomain'),
                'ex_service' => Lib::post('ex_service'),
                'sign_code' => Lib::post('sign_code'),
                'repayment_poundage' => Lib::post('repayment_poundage'),
                'deposit_poundage' => Lib::post('deposit_poundage'),
                'withdraw_poundage' => Lib::post('withdraw_poundage'),
                'validatecard_poundage' => Lib::post('validatecard_poundage'),
                'tx_in' => Lib::post('tx_in'),
                'tx_out' => Lib::post('tx_out'),
                'tx_agent_rate' => Lib::post('tx_agent_rate'),
                'repayment_rate' => Lib::post('repayment_rate'),
                'max_withdraw_day' => Lib::post('max_withdraw_day'),
                'bonus_day_max' => Lib::post('bonus_day_max'),
                'deposit_co' => Lib::post('deposit_co'),
                'max_r_amount' => Lib::post('max_r_amount'),
                'min_r_amount' => Lib::post('min_r_amount'),
                'max_r_sin_amount' => Lib::post('max_r_sin_amount'),
                'setnotifyurl' => Lib::post('setnotifyurl'),
                'channel_in_url' => Lib::post('channel_in_url'),
                'channel_out_url' => Lib::post('channel_out_url'),
                'money_out_poundage'=>Lib::post('money_out_poundage'),
                'oem_repayment_poundage'=>Lib::post('oem_repayment_poundage'),
                'channel_in_query_url' => Lib::post('channel_in_query_url'),
                'channel_out_query_url' => Lib::post('channel_out_query_url'),
                'redis_config' => Lib::post('redis_config'),
                'oss_config' => Lib::post('oss_config'),
                'jpush_config' => Lib::post('jpush_config'),
                'sms_config' => Lib::post('sms_config'),
                'credit_card_url' => Lib::post('credit_card_url'),
                'insurance_url' => Lib::post('insurance_url'),
                'loan_url' => Lib::post('loan_url'),
                'merchant_id' => Lib::post('merchant_id'),
                'merchant_key' => Lib::post('merchant_key'),
                'payment_url' => Lib::post('payment_url'),
                'ios_down_url' => Lib::post('ios_down_url'),
                'android_down_url' => Lib::post('android_down_url'),
                'version_number' => Lib::post('version_number'),
                'new_version_number' => Lib::post('new_version_number'),
                'xf_service_charge' => Lib::post('xf_service_charge'),
                'abroad_url' => Lib::post('abroad_url'),
                'a_deposit_key' => Lib::post('a_deposit_key'),
                'deposit_key' => Lib::post('deposit_key'),
                'status' => Lib::post('status'),
                'kcp_earnings' => Lib::post('kcp_earnings'),
                'jwpay_earnings' => Lib::post('jwpay_earnings'),
                'is_show' => Lib::post('is_show'),
                'sfvalue' => Lib::post('sfvalue'),
                'txlnvalue' => Lib::post('txlnvalue'),
                'txoutvalue' => Lib::post('txoutvalue'),
            ];
            $upd = $this->M()->edit($data, $id);
            if ($upd) {
                $ret = $this->M()->updateConfig($id);
                /*
                $appid = Lib::post('appid');
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
	            $rateResult = $payHandle->payRateAll($ratePost);
                */
	            //var_dump($rateResult);exit;
                if (! $ret) {
                    Dwz::err('配置文件更新失败，请手动更新');
                }
                Dwz::successDialog($this->M()->modelName, '', 'closeCurrent');

            } else {
                Dwz::err();
            }
        }
        $list = DBQ::getRow('merc', '*', [
            'id' => $id
        ]);
        $this->assign('list', $list);
        $this->view();
    }

    /**
     *
     * @name 禁用商家
     */
    public function disable($id = 0)
    {
        $ret = DBQ::upd('merc', [
            'status' => '-1'
        ], [
            'id' => $id
        ]);
        if ($ret) {
            $ret = $this->M()->updateConfig($id);
            if (! $ret) {
                Dwz::err('配置文件更新失败，请手动更新');
            }
            Dwz::success(Lib::getUrl($this->M()->modelName), $this->M()->modelName);
        } else {
            Dwz::err();
        }
    }

    /**
     *
     * @name 启用商家
     */
    public function enable($id = 0)
    {
        $ret = DBQ::upd('merc', [
            'status' => '1'
        ], [
            'id' => $id
        ]);
        if ($ret) {
            $ret = $this->M()->updateConfig($id);
            if (! $ret) {
                Dwz::err('配置文件更新失败，请手动更新');
            }
            Dwz::success(Lib::getUrl($this->M()->modelName), $this->M()->modelName);
        } else {
            Dwz::err();
        }
    }

    /**
     *
     * @name 更新配置文件
     */
    public function updateConfig($id = 0)
    {
        $ret = $this->M()->updateConfig($id);
        if ($ret) {
            Dwz::success(Lib::getUrl($this->M()->modelName), $this->M()->modelName);
        } else {
            Dwz::err();
        }
    }
        /**
     *
     * @name 更新费率文件
     */
    public function updaterate($id = 0)
    {
        $ret = $this->M()->updaterate($id);
        if ($ret) {
            Dwz::success(Lib::getUrl($this->M()->modelName), $this->M()->modelName);
        } else {
            Dwz::err();
        }
    }


    //向oem商添加数据表
    public function addTable(){
        $dbConfig =new Cron();
        $appid=Lib::request ( 'appid' );
        $info=$dbConfig->getDb($appid);
        $this->assign('appid',$appid);
        $this->view();
    }

    //修改oem商数据表
    public function updateTable($act=null,$id=0){

        /*$dbConfig =new Cron();
        $appid=Lib::request ( 'appid' );
        $info=$dbConfig->getDb($appid);
        $this->assign('appid',$appid);
        $this->view();*/
        if ($act == 'updateTable') {
            $dbConfig =new Cron();
            $appid=Lib::request ( 'appid' );
            $info=$dbConfig->getDb($appid);
            if ($info) {
                
                Dwz::successDialog($this->M()->modelName, '', 'closeCurrent');

            } else {
                Dwz::err();
            }
        }

        $this->assign('appid',$appid);
        $this->view();
    }
}
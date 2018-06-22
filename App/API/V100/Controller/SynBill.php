<?php
namespace App\API\V100\Controller;
use Core\Base\Controller;
use Core\Lib;

class SynBill extends Controller
{
       public $m;
    public function __construct($controller, $action)
    {
        parent::__construct($controller, $action);
        $model = "\App\API\V100\Model\SynBill";
        $this->m = new $model;
    }

    public function index()
    {
        //设置IP白名单
        $ret = Lib::isSafeIp('',SAFE_IPS);
        if($ret==false)
        {
            $data = [
                'status' => 'fail',
                'msg' => 'IP不合法'
            ];
            Lib::outputJson($data);
        }
        else 
        {
            $data = [
                'appid' => Lib::post('appid'),
                'user_id' => Lib::post('user_id'),
                'agent_id' => Lib::post('agent_id'),
                'amount' => Lib::post('amount'),
                'poundage' => Lib::post('poundage'),
                'bill_type' => Lib::post('bill_type'),
                'card_type' => Lib::post('card_type'),
                'transaction_id' => Lib::post('transaction_id'),
                'status' => Lib::post('status'),
                'is_pay' => Lib::post('is_pay'),
                'order_sn' => Lib::post('order_sn'),
                'create_time' => Lib::getMs(),
            ];
            echo $ret = $this->m->add($data);
            $outdata = [
                'status' => 'success',
                'msg' => '同步成功!',
                'ret' => $ret
            ];
            Lib::outputJson($outdata);
        }
    }
}


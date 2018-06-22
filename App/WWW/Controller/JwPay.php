<?php
namespace App\WWW\Controller;
use Core\Base\Controller;
use Core\Lib;
use Core\DB\DBQ;

class JwPay extends Controller
{
	    // 配置内容
    protected $merchant_id = "4751";

    protected $Md5key="uinner8832HYKJNMJMBoftgj";
	
	public function add() {
		$data=[];
		$data['appid']  = Lib::post('appid');
		
		$data['amount'] = Lib::post('amount');
		
		$data['title'] = Lib::post('title');
		
		$data['out_trade_no'] =Lib::post('out_trade_no');
		
		$data['time'] =Lib::getMs();
		
		$data['status'] =0;
        //计算 9.30 点到 18.00 的 时间
        $time = date("Y-m-d",time());
        $startTime =  strtotime($time." 09:30")."000";
        $endTime  =  strtotime($time." 18:00")."999";

        $data['ip'] = Lib::post('ip');

        $row  = DBQ::getAll('jwpay_no',"*",[
            'ip' => $data['ip'],
            "time[<>]" => [$startTime, $endTime],
            "ORDER" => ['id'=>"DESC"],
            'LIMIT' => 2,
        ]);
		$status1 = 0;
		$status2 = 0;
		if(count($row)==1)
		{
			$status1 = $row[0]['status'];
		}
		if(count($row)==2)
		{
			$status2 = $row[1]['status'];
		}
	
		$all = $status1+$status2;
		
        if($all==2) {            
			echo json_encode(array("status"=>-1,'msg'=>'同个IP每天不能连续提交两次'));
			exit;
        }

        $ret=DBQ::add('jwpay_no',$data);
		
		if($ret){
			echo json_encode(array("status"=>1,'msg'=>'添加成功'));
		}else{
			echo json_encode(array("status"=>0,'msg'=>'添加失败'));
		}
		
		
	}

//异步通知
    public function notify_url(){
		
		$merchant_id=$_REQUEST['merchant_id'];//商户号
		$out_trade_no =$_REQUEST['out_trade_no'];//流水号
		$amount=$_REQUEST['amount'];//实际成功金额
		$order_sn=$_REQUEST['order_sn'];//雨金订单号
		$succtime=$_REQUEST['succtime'];//支付完成时间
		$sign=$_REQUEST['sign'];//md5签名
		$Md5key = $this->Md5key;//商户交易秘钥
		$MARK = "|";
		//MD5签名格式 商户+商户订单号+网站订单号+签名
		 
		$WaitSign=md5($merchant_id.$MARK.$order_sn.$MARK.$out_trade_no.$MARK.$Md5key);
		$condition['AND']['out_trade_no']=$out_trade_no;
		if ($sign == $WaitSign) {
			//内部处理
			$rest = DBQ::upd('jwpay_no',['status'=>1],$condition);
			if($rest){
				echo ("ok");
			}else{
				echo ("Fail");
			}	
			
		} else {

			$rest = DBQ::upd('jwpay_no',['status'=>-1],$condition);
			echo("Md5CheckFail");//MD5校验失败

		} 
    }
	
//通知地址
    public function return_url(){
		$merchant_id=$_REQUEST['merchant_id'];//商户号
		$out_trade_no =$_REQUEST['out_trade_no'];//流水号
		$amount=$_REQUEST['amount'];//实际成功金额
		$order_sn=$_REQUEST['order_sn'];//雨金订单号
		$succtime=$_REQUEST['succtime'];//支付完成时间
		$sign=$_REQUEST['sign'];//md5签名
		$Md5key = $this->Md5key;//商户交易秘钥
		$MARK = "|";
		//MD5签名格式 商户+商户订单号+网站订单号+签名
		 
		$WaitSign=md5($merchant_id.$MARK.$order_sn.$MARK.$out_trade_no.$MARK.$Md5key);
		if ($sign == $WaitSign) {
			
			$this->view();

		} else {
			echo '支付失败';
		   
		} 
    }

    //显示100条账单
    public function all() {
	    $data = DBQ::getAll("jwpay_no",'*',[
	       'ORDER'=>['id'=>'DESC'],
           'LIMIT'=>10
        ]);

	    $this->assign("data",$data);
    }

   


}




<?php
namespace App\WWW\Model;
use Core\Base\Model;
use Core\DB\DBQ;
class JwPay extends Model
{
  public function getOne($amount,$condition)
    {
       $data = DBQ::getOne('jwpay_no', '*', $condition );
	   $rest=false;
	   if($data['amount']==$amount && $data['status']== 0){
		   $dataup=DBQ::upd('jwpay_no',['status'=>1],['id'=>$data['id']]);
		   if($dataup){
			  $rest=true; 
		   }else{
			  $rest=false; 
		   }
	   }else{
		   $rest=false;
	   }
		return $rest;

    }
    public function addon($data)
    {
        $data = DBQ::add('jwpay_no', $data);
        return $data ;
    }
    
}
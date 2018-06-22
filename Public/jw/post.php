<?php \date_default_timezone_set('PRC');?><?php session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>充值接口-提交信息处理</title>
<?php
$merchant_id=$_REQUEST['merchant_id'];//商户号
$out_trade_no=$_REQUEST['out_trade_no'];//流水号
 
$amount=$_REQUEST['amount'];//订单金额
$title=$_REQUEST['title'];//产品名称 20字符以下
 
 

$notify_url=$_REQUEST['notify_url'];//通知商户页面端地址

$return_url=$_REQUEST['return_url'];//服务器底层通知地址
 
$Md5key="uinner8832HYKJNMJMBoftgj";//商户md5密钥

//MD5签名格式
$sign=md5($merchant_id."|".$out_trade_no."|".$return_url."|".$notify_url."|".$Md5key);
 
$payUrl="http://m.uinnpay.com/gateway_chinapayhk.html";//不通的接口 地址都不一样，具体根据文档对应修改
 
 
?>
</head>

<body onload="document.form1.submit()">
<form id="form1" name="form1" method="get" action="<?php echo $payUrl; ?>">
        <input type='hidden' name='merchant_id' value="<?php echo $merchant_id; ?>" />
	 
        
         <input type='hidden' name='out_trade_no' value="<?php echo $out_trade_no; ?>" />
 
        <input type='hidden' name='title' value="<?php echo $title; ?>" />
        <input type='hidden' name='amount' value="<?php echo $amount; ?>" />
         
        <input type='hidden' name='notify_url' value="<?php echo $notify_url; ?>" />
        <input type='hidden' name='return_url' value="<?php echo $return_url; ?>" />
        <input type='hidden' name='sign' value="<?php echo $sign; ?>" />
		 
</form>
</body>
</html>

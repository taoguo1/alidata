<?php 
\date_default_timezone_set('PRC');
$goodsName = 'SALE-';
for($i=1;$i<=5;$i++){
    $goodsName .= chr(rand(65,90));
}
$goodsName.=rand(1000,9999);
for($i=1;$i<=5;$i++){
    $goodsName .= chr(rand(65,90));
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>数据输入页</title>
<?php
    $out_trade_no =  date("Ymdhis");
?>
</head>

<body>
<form method="post" name="form1" id="form1"  action="post.php">
<table> 

<tr>
<td>商户号:</td>
<td><input  name="merchant_id" value="4751" /></td>
</tr>
<tr>
<td>网站流水号:</td>
<td><input type="text" name="out_trade_no" value="<?php echo $out_trade_no;?>" /></td>
</tr>

<tr>
<td>订单金额:</td>
<td><input name="amount"  value="1.00" /><span>建议1分钱支付</span> </td>
</tr>
<tr>
<td>商品名称:</td>
<td><input type="text" name="title" value="<?php echo $goodsName;?>"/></td>
</tr>
 
 
<tr>
<td>异步通知地址:</td>
<td><input type="text" name="notify_url"  size="80" value="https://manage.dizaozhe.cn/JwPay/notifyHkUrl" />
<font color="red"><b>此地址注意更换成你们可用的通知地址</b></font>
</td><!--页面跳转连接的商户页面地址-->
</tr>
<tr>
<td>通知地址:</td>
<td><input type="text" name="return_url" size="80" value="https://manage.dizaozhe.cn/JwPay/returnHkUrl" />
<font color="red"><b>此地址注意更换成你们可用的通知地址</b></font>
</td><!--通知服务器底层地址-->
</tr>
 
<tr>
<td colspan="2" align="center"><input type="submit" id="btnpost" value="提交"  /></td>
</tr>

</table>
</form>
</body>
</html>

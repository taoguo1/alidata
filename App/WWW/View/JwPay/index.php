
<!DOCTYPE html>
<html>

	<head>
		<meta charset="UTF-8">
		<title></title>
		<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
		<style>
			* {
				padding: 0;
				margin: 0;
			}
			
			.titl {
				width: 100%;
				height: 166px;
				margin-bottom: 0px;
				margin-bottom: 2px;
				overflow: hidden;
			}
			
			.titl img {
				width: 100%;
			}
			
			.ui-btn-lg {
				font-size: 17px;
				height: 40px;
				line-height: 40px;
				margin: 20px auto;
				display: block;
				width: 80%;
				border-radius: 3px;
			}
			
			.ui-btn-primary {
				border: 0;
				background-color: #0776e5;
				color: #fff;
				background-clip: padding-box;
			}
			
			.ui-btn-primary:active {
				background: #0776e5;
				color: #fff;
				opacity: 0.6;
			}
		</style>
	</head>

	<body style="background-color: #f7f7f7;">
	<form method="post" name="form1" id="form1"  action="<?=\Core\Lib::getUrl('JwPay','post')?>">
		<div class="titl">
			<img src="/Static/image/jwxf.png" alt="" />
		</div>
		<div style="width: 100%;height: auto;background-color: #fff;padding-bottom: 10px;">
		
			<div style="width: 100%;display: flex;align-items: center;justify-content: center;padding: 30px 0;">
				<span style="font-size: 40px;margin-right: 10px;">$</span>
				<input type="number" name="amount" id="ipt" value='<?php echo rand(10,15);?>' readonly  placeholder="请输入支付金额" style="color: #333;border: none;font-size: 20px;width:155px" />
			</div>
			<p style="width: 80%;margin: 0 auto;color:rgb(113,113,113)">
				注释：<br/> 消费金额：10-15美元 <br/>消费时间：9:30-18:00 <br/>是否到账：不到账 <br/>此商户为境外香港银联美元商户，凡带有银联标识的信用卡，都可以刷卡消费，帮助用户优化信用卡账单使用，起到辅助提额的效果，建议用户单卡单月消费不超过3次，您消费的金额不到账，请刷卡时，仔细阅读，慎重操作。
			</p>
		</div>
		<button class="ui-btn-lg ui-btn-primary" id="btn">立即支付</button>
		</div>
		</form>
		<script>
			var val = document.getElementById("ipt");
			var btn = document.getElementById("btn");
			btn.onclick = function() {
				if(val.value == "") {
					alert("输入不能为空!");
					return;
				}
				if (val.value < '10' || val.value > '15') {
					alert("消费金额必须是10~15美元");
				}
			}
		</script>
	</body>

</html>

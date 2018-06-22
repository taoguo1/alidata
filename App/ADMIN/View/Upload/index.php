<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=7" />
<title>upload</title>
<style type="text/css">
.btn {
	color: #fff;
	background-color: #337ab7;
	border-color: #2e6da4;
	display: inline-block;
	padding: 6px 12px;
	margin-bottom: 0;
	font-size: 14px;
	font-weight: 400;
	line-height: 1.42857143;
	text-align: center;
	white-space: nowrap;
	text-decoration: none;
	vertical-align: middle;
	-ms-touch-action: manipulation;
	touch-action: manipulation;
	cursor: pointer;
	-webkit-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
	user-select: none;
	background-image: none;
	border: 1px solid transparent;
	border-radius: 4px;
	width: 200px;
	background: #f74d37
}
</style>
<script>

	function checkInputFile()
	{
		document.getElementById('uploading').style.display='block';
		document.getElementById('upload').style.display='none';
		var file_name = document.getElementById('file_name').value;
		if(file_name=='')
		{
			document.getElementById('tips').style.display='block';
			return false;
		}
		document.getElementById('tips').style.display='none';
		return true;
	}
	function setImgPath(id)
	{
		window.parent.document.getElementById("<?=$id?>").value=id;
		window.parent.$("#<?=$id?>_img").attr("src","<?=APP_SITE_PATH?>"+id);
		window.parent.doOk();
	}
</script>
</head>
    <body style="background: #ededed">
    
    	<form action="<?php echo \Core\Lib::getUrl('upload','index','id='.$id.'&path='.$path)?>" method="post" enctype="multipart/form-data" onSubmit="return checkInputFile()">
    		<input type="hidden" name="act" value="upload" />
    		<div style="margin: 0 auto;width:100%; text-align:center">
    		<div style="display: none" id="uploading">
    			<div><img width="150" src="<?=APP_ADMIN_STATIC?>/image/uploading.gif" style="padding: 5px;"></div>
    			
    			<div style="color:#f74d37; font-size:14px;font-weight:bold; line-height: 40px;">正在上传,请勿关闭</div>
    			</div>
    		<div id="upload" style="position: relative;">
    			<input type="file" id="file_name" name="file_name" style="border:1px solid #cccccc;width:192px;padding:3px; margin: 5px; background:#ffffff">
    			<div id="tips" style="font-size:14px; display:none;color:red; font-weight: bold; position: absolute; top: 5px; right: 10px;">请选择附件</div>
    			<input type="submit" name="upload" class='btn' value="开始上传">
    		</div>
    		</div>
    	</form>
    </body>
</html>

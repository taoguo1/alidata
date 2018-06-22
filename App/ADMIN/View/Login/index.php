<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=7" />
<title><?php echo $data['login_title'];?>【登录】</title>
<meta name="author" content="lizhongwen.com">
<script src="<?=APP_ADMIN_STATIC?>login/js/jquery-1.8.2.min.js"></script>
<script src="<?=APP_ADMIN_STATIC?>login/js/supersized.3.2.7.min.js"></script>
<script src="<?=APP_ADMIN_STATIC?>login/js/scripts.js"></script>
<link rel="stylesheet" href="<?=APP_ADMIN_STATIC?>login/css/style.css">
</head>
<body>
<div class="login-name"><?php echo $data['login_header_title'];?></div>
	<div class="page-container">		
		<form action="<?php echo Core\Lib::getUrl('login','login');?>" method="post">
			<input type="text" name="account" class="username" placeholder="请输入您的登录帐号！">
			<input type="password" name="password" class="password" placeholder="请输入您的登录密码！">
			<button type="submit" class="submit_button">登录</button>
			<div class="error">
				<span>+</span>
			</div>
		</form>
		<div class="connect">
			<p><?php echo $data['login_footer_copyright'];?></p>
		</div>
	</div>
<script type="text/javascript">
    jQuery(function($){
        $.supersized({
            slide_interval     : 4000,
            transition         : 1,
            transition_speed   : 1000, 
            performance        : 1,
            min_width          : 0,
            min_height         : 0,
            vertical_center    : 1,
            horizontal_center  : 1,
            fit_always         : 0,
            fit_portrait       : 1,
            fit_landscape      : 0,
            slide_links        : 'blank',
            slides             : [{image : '<?=APP_ADMIN_STATIC?>login/img/1.jpg'},{image : '<?=APP_ADMIN_STATIC?>login/img/2.jpg'},{image : '<?=APP_ADMIN_STATIC?>login/img/3.jpg'}]
        });
    });
</script>
</body>
</html>
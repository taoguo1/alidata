<?php
\header("Content-Type:text/html; charset=utf-8");
\error_reporting(1);
\date_default_timezone_set('PRC');

$valid_token = 'managedev123456';
$client_token = $_SERVER['HTTP_X_GITLAB_TOKEN'];
//file_put_contents('hook.txt',$client_token);

if($valid_token == $client_token){

	$project_path = "/www/web/manage-dev_dizaozhe_cn/public_html/ManageDev/";
	$cmd = "cd $project_path && git pull";

	$ret = shell_exec('cd '.$project_path. '&& sudo /usr/bin/git pull origin master >> '.$project_path.'/Public/WebHook/hook.log');
	//echo "<pre>$ret</pre>";
	echo "success";

}else{
	echo "Access Token Error";
}


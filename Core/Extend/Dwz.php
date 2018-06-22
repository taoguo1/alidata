<?php
namespace Core\Extend;
class Dwz {
	public static function successDialog($navTabId, $url = "", $closeCurrent = "") {
		echo "{";
		echo "\"statusCode\":\"200\",";
		echo "\"message\":\"操作成功\",";
		echo "\"navTabId\":\"" . $navTabId . "\",";
		echo "\"rel\":\"\",";
		echo "\"callbackType\":\"" . $closeCurrent . "\",";
		echo "\"forwardUrl\":\"" . $url . "\"";
		echo "}";
		exit ();
	}
	public static function successAlert($content) {
		echo "{";
		echo "\"statusCode\":\"200\",";
		echo "\"message\":\"$content\"";
		echo "}";
		exit ();
	}
	public static function success($url, $navTabId = '') {
		echo "{";
		echo "\"statusCode\":\"200\",";
		echo "\"message\":\"操作成功\",";
		echo "\"navTabId\":\"" . $navTabId . "\",";
		echo "\"callbackType\":\"forward\",";
		echo "\"reloadFlag\":\"1\",";
		echo "\"forwardUrl\":\"" . $url . "\"";
		echo "}";
		exit ();
	}
	public static function successReload($navTabId) {
		echo "{";
		echo "\"statusCode\":\"200\",";
		echo "\"message\":\"操作成功\",";
		echo "\"navTabId\":\"" . $navTabId . "\",";
		// echo "\"callbackType\":\"forward\",";
		echo "\"reloadFlag\":\"1\"";
		// echo "\"forwardUrl\":\"".$url."\"";
		echo "}";
		exit ();
	}
	public static function successClose($navTabId = '', $msg = '操作成功') {
		echo "{";
		echo "\"statusCode\":\"200\",";
		echo "\"message\":\"" . $msg . "\",";
		echo "\"navTabId\":\"" . $navTabId . "\",";
		echo "\"reloadFlag\":\"1\",";
		echo "\"callbackType\":\"closeCurrent\"";
		echo "}";
		exit ();
	}
	public static function err($msg = '') {
		if (empty ( $msg )) {
			$msg = "操作失败";
		}
		echo "{";
		echo "\"statusCode\":\"300\",";
		echo "\"message\":\"" . $msg . "\"";
		echo "}";
		exit ();
	}
	public static function auth($msg = '') {
		if (empty ( $msg )) {
			$msg = "操作失败";
		}
		echo "{";
		echo "\"statusCode\":\"300\",";
		echo "\"message\":\"" . $msg . "\"";
		echo "}";
		exit ();
	}
	public static function errTxt($msg = '', $navTabId = '') {
		echo "{";
		echo "\"statusCode\":\"300\",";
		echo "\"message\":\"" . $msg . "\",";
		echo "\"navTabId\":\"" . $navTabId . "\",";
		// echo "\"forwardUrl\":\"".$url."\",";
		// echo "\"reloadFlag\":\"1\","
		echo "\"callbackType\":\"closeCurrent\"";
		echo "}";
		exit ();
	}
	public static function successLayout($navTabId = '') {
		echo "{";
		echo "\"statusCode\":\"200\",";
		echo "\"message\":\"操作成功\",";
		echo "\"navTabId\":\"\",";
		// echo "\"forwardUrl\":\"".$url."\",";
		echo "\"rel\":\"jbsxBox\",";
		echo "\"forwardUrl\":\"\",";
		echo "\"callbackType\":\"\",";
		echo "\"reloadFlag\":\"1\"";
		echo "}";
		exit ();
	}
	public static function ajaxTimeout() {
		$msg = "登录会话超时，请重新登录";
		echo "{";
		echo "\"statusCode\":\"301\",";
		echo "\"message\":\"" . $msg . "\"";
		echo "}";
		exit ();
	}
	public static function goBack($msg) {
		echo "<script>alert('" . $msg . "');history.go(-1);</script>";
		exit ();
	}
	public static function goToUrl($url) {
		echo "<script>window.location.href='" . $url . "'</script>";
		exit ();
	}
}
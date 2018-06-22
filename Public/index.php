<?php
\session_start();
\header("Content-Type:text/html; charset=utf-8");
\date_default_timezone_set('PRC');
\define('APP_PATH', \str_replace('Public', '', __DIR__));
require APP_PATH . 'Config/Config.php';
require APP_PATH . 'Config/Constant.php';
require APP_PATH . 'Core/Core.php';
require APP_PATH . 'vendor/autoload.php';
$url = $_SERVER['REQUEST_URI'];
$position = \strpos($url, '?');
$url = ($position === false) ? $url : \substr($url, 0, $position);
$url = \trim(\substr($url, \strlen(APP_SITE_PATH)));
if (! empty($url)) {
    $urlArray = \explode('/', $url);
    $urlArray = \array_filter($urlArray);
    if ($urlArray[0] == APP_API || $urlArray[0] == APP_ADMIN || $urlArray[0] == APP_CALL) {
        \define('RUN_PATH', \strtolower($urlArray[0]));
    } else {
        \define('RUN_PATH', \strtolower('www'));
    }
} else {
    \define('RUN_PATH', \strtolower('www'));
}
(new \Core\Core($config))->run();
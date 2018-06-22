<?php
namespace Core;

use Core\DB\DBQ;
use Core\Extend\Redis;
use Core\Lib;

// use Core\Extend\RedisAliMulti;
class Filter
{

    public $controller;

    public $token;

    public $uid;

    public $timestamp;

    public function __construct()
    {
        $headers = Lib::getAllHeaders();
        $this->timestamp = $headers['TIME'];
    }

    public function run($controllerName, $actionName)
    {
        $apiVersions = Lib::loadFile('Config/APIVersion.php');
        $this->controller = 'App\\' . \strtoupper(RUN_PATH) . '\\Controller\\' . ucfirst($controllerName);
        if (RUN_PATH === APP_API) {
            $version = Lib::post('version');
            $appid = Lib::post('appid');
            $appsecret = Lib::post('appsecret'); 
            if (! isset($appid)) {
                $data = [
                    'status' => 'fail',
                    'code' => 10008,
                    'msg' => '缺少appid参数'
                ];
                Lib::outputJson($data);
            }
            if (ucfirst($controllerName) != 'GetConfig') {
                if (! isset($appsecret)) {
                    $data = [
                        'status' => 'fail',
                        'code' => 10010,
                        'msg' => '缺少appsecret参数'
                    ];
                    Lib::outputJson($data);
                }
            }
            
            if (isset($version)) {
                $apiVersion = $version;
                $apiVersionsKeys = \array_keys($apiVersions);
                
                if (! \in_array($apiVersion, $apiVersionsKeys)) {
                    $data = [
                        'status' => 'fail',
                        'code' => 10006,
                        'msg' => '无效的版本信息'
                    ];
                    Lib::outputJson($data);
                } else {
                    $apiVersionArr = $apiVersions[$apiVersion];
                    if (! $apiVersionArr['status']) {
                        $data = [
                            'status' => 'fail',
                            'code' => 10007,
                            'msg' => $apiVersionArr['msg']
                        ];
                        
                        Lib::outputJson($data);
                    }
                }
                
                // 验证传的参数是否正确
                $file = APP_PATH . "Config/MercConfig/" . $appid . "_Config.php";
                if (! file_exists($file)) {
                    $data = [
                        'status' => 'fail',
                        'code' => 10006,
                        'msg' => '参数不正确'
                    ];
                    Lib::outputJson($data);
                } else {
                    $dbJson = file_get_contents($file);
                    $dbArr = json_decode($dbJson, true);
                    
                    $status = $dbArr['status'];
                    if ($status == - 1) {
                        $data = [
                            'status' => 'fail',
                            'code' => 10006,
                            'msg' => '该商户已被禁用'
                        ];
                        Lib::outputJson($data);
                    }
                }
                $this->controller = 'App\\' . strtoupper(RUN_PATH) . '\\' . $apiVersion . '\\Controller\\' . $controllerName;
            } else {
                $data = [
                    'status' => 'fail',
                    'code' => 10001,
                    'msg' => '缺少VERSION参数'
                ];
                Lib::outputJson($data);
            }
        }
    }
}
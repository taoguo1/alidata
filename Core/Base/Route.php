<?php
namespace Core\Base;

use Core\Extend\Session;

class Route
{

    // 路由处理
    public function __construct()
    {
        $controllerName = DEFAULT_CONTROLLER;
        $actionName = DEFAULT_ACTION;
        $param = [];
        $url = $_SERVER['REQUEST_URI'];
        // 清除?之后的内容
        $position = strpos($url, '?');
        $url = $position === false ? $url : substr($url, 0, $position);
        // 删除前后的/
        if (APP_SITE_PATH == "/") {
            $url = substr($url, 1);
        } else {
            $url = str_replace(APP_SITE_PATH, '', $url);
        }
        $url = preg_replace('/([^:])([\/\\\\]+([\.][\/\\\\]+)+|[\/\\\\]{2,})/', '$1/', $url);
        $url = preg_replace('#/$#', '', $url);
        if ($url) {
            // 使用/分割字符串，并保存在数组中
            $urlArray = explode('/', $url);
            // 删除空的数组元素,为什么要删除空数组元素呢,0都为我删除了,还是unset吧
            // $urlArray = array_filter ( $urlArray );
            foreach ($urlArray as $k => $v) {
                if (! isset($v)) {
                    unset($urlArray[$k]);
                }
            }
            if (RUN_PATH != 'www') {
                // 获取控制器名
                $controllerName = isset($urlArray[1]) ? ucfirst($urlArray[1]) : 'index';
                unset($urlArray[0]);
                // 获取动作名
                array_shift($urlArray);
                $actionName = ! empty($urlArray[0]) ? $urlArray[0] : $actionName;
                // 获取URL参数
                array_shift($urlArray);
                $param = $urlArray ? $urlArray : array();
            } else {
                // 获取控制器名
                $controllerName = isset($urlArray[0]) ? ucfirst($urlArray[0]) : 'index';
                // unset ( $urlArray [0] );
                // 获取动作名
                array_shift($urlArray);
                $actionName = ! empty($urlArray[0]) ? $urlArray[0] : $actionName;
                // 获取URL参数
                array_shift($urlArray);
                $param = $urlArray ? $urlArray : array();
            }
        }
        // 判断是否API接口
        
        $filter = new \Core\Filter();
        $filter->run($controllerName,$actionName);
        // 判断控制器和操作是否存在
        // $controller = 'App\\' . strtoupper(RUN_PATH) . '\\Controller\\' . $controllerName;
        $controller = $filter->controller;
        if (! \class_exists($controller)) {
            $data = [
                'status' => 'fail',
                'code' => 10004,
                'msg' => $controller . '控制器不存在'
            ];
            \Core\Lib::outputJson($data);
        }
        if (! \method_exists($controller, $actionName)) {
            $data = [
                'status' => 'fail',
                'code' => 10005,
                'msg' => $actionName . '方法不存在'
            ];
            \Core\Lib::outputJson($data);
        }
        // 如果控制器和操作名存在，则实例化控制器，因为控制器对象里面
        // 还会用到控制器名和操作名，所以实例化的时候把他们俩的名称也
        // 传进去。结合Controller基类一起看
        $dispatch = new $controller($controllerName, $actionName);
        
        $this->isLogin($controllerName, $actionName);
        // $dispatch保存控制器实例化后的对象，我们就可以调用它的方法，
        // 也可以像方法中传入参数，以下等同于：$dispatch->$actionName($param)
        call_user_func_array(array(
            $dispatch,
            $actionName
        ), $param);
    }

    // 后台则验证是否登录
    public function isLogin($controllerName, $actionName){
        if (RUN_PATH == APP_ADMIN) {
           //var_dump($_SESSION['account']);
            if ($controllerName != 'Login') {
                // 没有登录
                if ($_SESSION['account']==''||$_SESSION['account']==NULL||$_SESSION['account']==null) {
                    $loginUrl = \Core\Lib::getUrl('Login', "index", "act=admin");
                    //die($loginUrl.$account);
                    echo "<script>location.href='".$loginUrl."';</script>";
                    //header('location:' . $loginUrl);
                }else{
                    new \Core\Extend\AdminAuth($controllerName, $actionName);
                }
            }
        }
    }
}
<?php
namespace Core\Base;
use Core\Lib;

/**
 * 控制器基类
 */
class Controller
{
    protected $_controller;
    protected $_action;
    protected $_view;
    protected $_m;
    // 构造函数，初始化属性，并实例化对应模型
    public function __construct($controller, $action)
    {
        $this->_controller = $controller;
        $this->_action = $action;
        $this->_view = new View($controller, $action);
        //$this->_m = new $model ();
    }
    public function M() {
        $model = $this->_controller;
        $headers = Lib::getAllHeaders();
        
        if(strtoupper(RUN_PATH)=='API')
        {
            if($headers['VERSION'])
            {
                $VERSION = $headers['VERSION'];
            }
            $model = "\\App\\".strtoupper(RUN_PATH)."\\".$VERSION."\\Model\\".$model;
        }
        else
        {
            $model = "\\App\\".strtoupper(RUN_PATH)."\\Model\\".$model;
        }
        $m = new $model();
        return $m;
    }
   // 分配变量
    public function assign($name, $value)
    {
        $this->_view->assign($name, $value);
    }
   // 渲染视图
    public function view($action = '')
    {
        
        $this->_view->view($action);
    }
}

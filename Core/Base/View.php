<?php
namespace Core\Base;

/**
 * 视图基类
 */
class View
{

    protected $variables = [];

    protected $_controller;

    protected $_action;

    function __construct($controller, $action)
    {
        $this->_controller = $controller;
        $this->_action = $action;
    }

    // 分配变量
    public function assign($name, $value)
    {
        
        $this->variables[$name] = $value;
    }

    // 渲染显示
    public function view($action)
    {
        if(!empty($action)) {
            $this->_action = $action;
        }
        extract($this->variables);
        $defaultHeader = APP_PATH . 'App/' . strtoupper(RUN_PATH) . '/View/Public/header.php';
        $defaultFooter = APP_PATH . 'App/' . strtoupper(RUN_PATH) . '/View/Public/footer.php';
        $controllerHeader = APP_PATH . 'App/' . strtoupper(RUN_PATH) . '/View/' . ucfirst($this->_controller) . '/header.php';
        $controllerFooter = APP_PATH . 'App/' . strtoupper(RUN_PATH) . '/View/' . ucfirst($this->_controller) . '/footer.php';
        $controllerLayout = APP_PATH . 'App/' . strtoupper(RUN_PATH) . '/View/' . ucfirst($this->_controller) . '/' . $this->_action . '.php';
        // 页头文件
        if (file_exists($controllerHeader)) {
            include ($controllerHeader);
        } else {
            if (file_exists($defaultHeader)) {
                include ($defaultHeader);
            }
        }
        // 判断视图文件是否存在
        if (is_file($controllerLayout)) {
            include ($controllerLayout);
        } else {
            echo "<h1>无法找到视图文件</h1>";
        }
        // 页脚文件
        if (file_exists($controllerFooter)) {
            include ($controllerFooter);
        } else {
            if (file_exists($defaultHeader)) {
                include ($defaultFooter);
            }
        }
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: David
 * Date: 2018/1/18
 * Time: 9:21
 */

namespace Core\Extend;
class Session
{
    //private static $obj;
    public $handler;
    public function __construct(){
           \session_start();
   
    }
    public function set($key, $value){
        $_SESSION[$key] = $value;

    }

    public function get($key){
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }

    public function remove($key){
        unset($_SESSION[$key]);
    }

    public function getID(){
        return \session_id();
    }

    public function destroy(){
        \setcookie(\session_name(), null, -1, '/');
        \session_destroy();
    }

    public function setCookieTime($lifetime = 0){
        \session_set_cookie_params($lifetime);
    }
}


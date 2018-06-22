<?php
/**
 * Created by jixiang.
 * User: pc
 * Date: 2018/4/24
 * Time: 14:02
 */
namespace App\CALL\Model;
use Core\Base\Model;
use Core\Lib;
use Core\DB\DBQ;

class Notice extends Model
{

    public function getRmbFmt($foreignAmt)
    {
        $rate = 1.6171;//MYR TO RMB
        $rmb = $foreignAmt * $rate;
        return  \round($rmb);
    }

    public function getSign(array $params, $secret)
    {
        if($params['signature']) {
            unset($params['signature']);
        }
        $arr = [];
        foreach($params as $k => $param) {
            if (empty($param)) {
                unset($params[$k]);
            }
            $arr[] = $k . '=' .$param;
        }
        $str = \implode('&',$arr);
        $str .= '&'.$secret;
        return \md5($str);
    }

    public function checkSign($json, $secret)
    {
        $data = \json_decode($json, true);
        $remoteSign = $data['signature'];
        unset($data['signature']);
        $localSign = $this->getSign($data, $secret);
        return $remoteSign === $localSign;
    }

    public function isPost()
    {
        return $_SERVER[ 'REQUEST_METHOD' ] === 'POST' ? true : false;
    }

    public function redirect($url, $status = 302){
        header("Location: $url", true, $status);
        exit();
    }

    public function myLog($name,$content){
        file_put_contents('Logs/'.date('Ymd-H',time()).'-'.$name,$content."\n",FILE_APPEND);
    }

}
<?php
/**
 * Created by jixiang.
 * User: pc
 * Date: 2018/4/24
 * Time: 14:02
 */

namespace App\WWW\Model;


use Core\Base\Model;
use Core\DB\DBQ;

class Overseas extends Model
{
    public function __construct ()
    {
        parent::__construct();
    }

    public function addOrder(array $data)
    {
        return DBQ::add('myr_order', $data);
    }

    public function updOrder($data, $localOrderId)
    {
        return DBQ::upd('myr_order', $data, ['order_id' => $localOrderId]);
    }

    public function serialGen($prefix, $salt)
    {
        $prefixLens = \strlen( $prefix );
        if ($prefixLens >= 32) {
            return \substr( $prefix, 0, 32 );
        }
        $s = $_SERVER[ 'REMOTE_PORT' ] . $_SERVER[ 'REQUEST_TIME_FLOAT' ] . $_SERVER[ 'REMOTE_ADDR' ];
        $s = md5( \uniqid( $prefix, true ) . $s . $salt);
        $s = \strtoupper( $s );
        if (0 == $prefixLens) {
            return $s;
        }
        $result = str_split( $s, 1 );
        $datum  = [];
        for ($i = 0; $i < $prefixLens; $i++) {
            $pos = \rand( 1, 31 );
            if (\in_array( $pos, $datum )) {
                $i = $i - 1;
                continue;
            }
            unset( $result[ $pos ] );
            $datum[] = $pos;
        }
        $prefix = \strtoupper( $prefix );
        $result = $prefix . implode( '', $result );
        return $result;
    }

    public function apiCall($url,array $data)
    {
        $json = \json_encode($data,JSON_UNESCAPED_UNICODE);

        $headers = array();
        $headers[] = 'Content-Type: text/plain';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        $output = curl_exec($ch);
        curl_close($ch);
        return \json_decode($output,true);
    }

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

    protected function getUserLimit($card)
    {
        static $row = [];
        $condition = [
            'card_no' => $card,
        ];
        $today = date('Y-m-d', \time());
        if (empty($row)) {
            $ret = DBQ::getRow('myr_limit', '*', $condition);
            if(empty($ret)) {
                $data = [
                    'card_no' => $card,
                    'date' => $today
                ];
                DBQ::add('myr_limit', $data);
            } elseif ($ret['date'] != $today) {
                $data = [
                    'count' => 0,
                    'time' => 0,
                    'amt' => 0,
                    'date' => $today
                ];
                DBQ::upd('myr_limit', $data, $condition);
                $data['card_no'] = $card;
                $row = $data;
            } else {
                $row = $ret;
            }
        }
        return $row;
    }

    public function isMaxCount($card)
    {
        $row = $this->getUserLimit($card);
        return $row['count'] >= 3 ? true : false;
    }

    public function isLess20Minutes($card)
    {
        $row = $this->getUserLimit($card);
        $now = \time();
        $expired = $row['time'] + 1200;
        return $now < $expired ? true : false;
    }

    public function isSameAmount($card, $foreignAmtFmt)
    {
        $row = $this->getUserLimit($card);
        return $row['amt'] == $foreignAmtFmt;
    }

    public function checkSign($json, $secret)
    {
        $data = \json_decode($json, true);
        $remoteSign = $data['signature'];
        unset($data['signature']);
        $localSign = $this->getSign($data, $secret);
        return $remoteSign === $localSign;
    }

    public function updateLimit($limitData, $card)
    {
        return DBQ::upd('myr_limit',$limitData,['card_no' => $card]);
    }

    public function isPost()
    {
        return $_SERVER[ 'REQUEST_METHOD' ] === 'POST' ? true : false;
    }

    public function redirect($url, $status = 302)
    {
        header("Location: $url", true, $status);
        exit();
    }
}
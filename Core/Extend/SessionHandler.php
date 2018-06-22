<?php
/**
 * Created by PhpStorm.
 * User: David
 * Date: 2018/1/25
 * Time: 15:30
 */

namespace Core\Extend;
use Core\DB\DBQ;
use SessionHandlerInterface;


class SessionHandler implements SessionHandlerInterface
{

    public function open($savePath, $sessionName)
    {
        return true;
    }

    public function close()
    {
        return true;
    }

    public function read($id)
    {
        $ret = DBQ::getOne('session','session_value',['session_id'=>$id]);
        if(empty($ret))
        {
            $ret = "";
        }
        return $ret;
    }

    public function write($key, $value)
    {
        $rs = DBQ::getOne('session', 'session_id', ['session_id' => $key]);
        if ($rs){
            $data = [
                'session_id' => $key,
                'session_key' => '',
                'session_value' => $value,
                'expire' => time() + 1440,
                'create_time' => time()
            ];
            $result = DBQ::upd('session', $data, [
                "session_id" => $key
            ]);
        }else{
            if($value){
                $data = [
                    'session_id' => $key,
                    'session_key' => '',
                    'session_value' => $value,
                    'expire' => time() + 1440,
                    'create_time' => time()
                ];
                $result = DBQ::add('session', $data);
            }
        }
        if($result){
            return true;
        }else{
            return false;
        }

    }

    public function destroy($id)
    {
        DBQ::del('session', [
            'session_id' => $id,
        ]);
        return true;
    }

    public function gc($maxlifetime)
    {
        $result = DBQ::del('session', [
            'expire[<]'=>time(),
        ]);
        if($result){
            return true;
        }else{
            return false;
        }
    }

    public function create_sid(){
        $id = '';
        $prefix = \md5($_SERVER['SERVER_ADDR']);
        $prefix .= \md5(\rand());
        $prefix .= \md5(\rand());
        while(true){
            $id = \uniqid();
            $id = $prefix . md5($id);
            $id = \strtoupper($id);
            $id = \str_shuffle($id);
            try{
                $data = [
                    'session_id'=>$id,
                    'session_key'=>'',
                    'session_value'=>'',
                    'expire'=> time() + 30,
                    'create_time'=> time()
                ];
                DBQ::add('session',$data);
                break;
            }
            catch (\Exception $e){
            }
        }
        return $id;
    }

}
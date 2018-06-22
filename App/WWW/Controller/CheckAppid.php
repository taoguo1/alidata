<?php

namespace App\WWW\Controller;
use Core\Base\Controller;
use Core\Lib;


class CheckAppid extends Controller
{

    public function checkappid(){

        $data=[];
        $appid=Lib::post('appid');
        if(empty($appid)){
            $data=array('status' => 'fail', 'code' => 1000, 'msg' =>"无效的APPID,请检查APPID是否存在");
        }else{
            $rest=$this->M()->checkappid($appid);
            if(!empty($rest)){
                $data=array('status' => 'success', 'code' => 10000, 'msg' =>"查询成功",'data'=>$rest);
            }else{
                $data=array('status' => 'fail', 'code' => 1000, 'msg' =>"APPID不存在,请检查APPID是否正确");
            }
        }
        Lib::outputJson($data);
    }


}




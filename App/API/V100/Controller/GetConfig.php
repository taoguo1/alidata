<?php
namespace App\API\V100\Controller;
use Core\Base\Controller;
use Core\Lib;
use Core\DB\DBQ;
class GetConfig extends Controller
{
    public function index()
    {
        //设置IP白名单
        $ret = Lib::isSafeIp('',SAFE_IPS);
        if($ret==false)
        {
            $data = [
                'status' => 'fail',
                'msg' => 'IP不合法'
            ];
            Lib::outputJson($data);
        }
        else 
        {
            $appid = Lib::post('appid');
            $file = APP_PATH.'Config/MercConfig/'.$appid."_Config.php";
            echo $content = file_get_contents($file);
        }
    }
    public function bank()
    {
        //设置IP白名单
        $ret = Lib::isSafeIp('',SAFE_IPS);
        if($ret==false)
        {
            $data = [
                'status' => 'fail',
                'msg' => 'IP不合法'
            ];
            Lib::outputJson($data);
        }
        else 
        {
            $file = APP_PATH.'Config/Bank/BankConfig.php';
            echo $content = file_get_contents($file);

        }
    }

     public function content(){
        //设置IP白名单
        $ret = Lib::isSafeIp('',SAFE_IPS);
        if($ret==false)
        {
            $data = [
                'status' => 'fail',
                'msg' => 'IP不合法'
            ];
            Lib::outputJson($data);
        }
        else 
        {
            
            $res=DBQ::getAll('push','*',['appid'=>Lib::request('appid')]);
            $arr=[];
            foreach($res as $k=>$v){
                $arr[$k]=DBQ::getOne('system_message','*',['id'=>$v['content_id']]);
            }
            
            //获取推送列表
            $info=json_encode($arr);
            echo $info;
            
        }
    }

    public function contentDetail(){
        $info=DBQ::getOne('system_message','*',['id'=>Lib::request('id')]);
        $info2=json_encode($info);
        echo $info2;
    }
}


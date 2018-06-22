<?php
namespace App\CALL\Model;
use Core\Base\Model;
use Core\DB\DB;
use PDO;
use Core\Lib;

class Gdb extends Model
{
    public $dbOem;
    public $Conf;
    //获取配置
    public function getConf($appid){
        if(!$appid){
            exit("参数错误");
        }
        $postData = [
            'appid' => $appid,
            'version'=> OEM_CTRL_URL_VERSION
        ];
        $ret = Lib::httpPostUrlEncode(OEM_CTRL_URL.'api/getConfig', $postData);
        $ret = json_decode($ret,true);

        $this->Conf = $ret;
        if(!$ret['status']=='fail')
        {
            exit($ret['msg']);
        }else{
            if(!$ret['status']==-1){
                exit("该账户异常");
            }
        }
        return $this->Conf;
    }
    //获取DB对象
    public function getDb($appid){
        $ret = $this->getConf($appid);
        $this->dbOem = new DB([
            'databaseType' => 'mysql',
            'databaseName' => $ret['db_name'],
            'server' => $ret['db_ip'],
            'userName' => $ret['db_user'],
            'password' => $ret['db_password'],
            'charSet' => 'utf8',
            'debugMode' => false,
            'logging' => true,
            'port' => $ret['db_port'],
            'prefix' => $ret['db_prefix'],
            'option' => [
                PDO::ATTR_CASE => PDO::CASE_NATURAL
            ],
            'command' => [
                'SET SQL_MODE=ANSI_QUOTES'
            ]
        ]);
        return $this->dbOem;
    }


    //获取代理树
    public function getAgent($pid,$appid,&$data=[]){
        $dbHandle = $this->getDb($appid);
        $rs = $dbHandle->get(
            'agent (A)',
            [
                '[>]agent_ext (B)' => [
                    'A.id' => 'agent_id'
                ]
            ],
            ['A.id','A.pid','A.rate','A.skrate','B.userCode'],
            ['A.id'=>$pid]
        );
        $data[] = $rs;
        if($rs['pid']) {
            $this->getAgent($rs['pid'],$appid, $data);
        }
        return $data;
    }

    //写日志
    public function myLog($name,$content){
        $path = 'Logs/Gather/'.date('Y',time()).'/'.date('m',time()).'/'.date('d',time());
        if(!file_exists($path)){
            if(\mkdir($path,0777,true)){
                file_put_contents($path.'/'.date('H',time()).'-'.$name,json_encode($content)."\n",FILE_APPEND);
            }
        }else{
            file_put_contents($path.'/'.date('H',time()).'-'.$name,json_encode($content)."\n",FILE_APPEND);
        }
    }


}
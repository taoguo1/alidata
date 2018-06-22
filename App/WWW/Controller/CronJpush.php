<?php
namespace App\WWW\Controller;
use Core\Base\Controller;
use Core\Extend\Redis;
use Core\Lib;

class CronJpush extends Controller
{
    public function repayment(){
        $redisMsg = Redis::instance('plan');
        $data = $redisMsg->zRangeByScore('jpush_repayment','-inf',time(),['withscores'=>false,'limit'=>[0,1]]);
        if($data) {
            $valRds = json_decode($data[0], true);
            //插入数据库推送记录表中
            //从模型中获取当前记录中对应appid的数据库操作句柄
            $dbHandle = $this->M()->getDb($valRds['appid']);
            //构造要插入的数据
            $pushData = [
                'uid' => $valRds['deviceid'],
                'title' => '还款通知',
                'content' => $valRds['content'],
                'user_type' => 1,
                'read_unread' => 2,
                'type' => 2,
                'status' => 1,
                'create_time' => Lib::getMs()
            ];
            $dbHandle->insert('system_message',$pushData);
            $insertId = $dbHandle->id();
            $valRds['pars'] = '{"id":'.$insertId.'}';
            Lib::pLog('r.txt','url:'.EX_DOMAIN.'exchange/MutiJpushes/send,'.'rs:'.json_encode($valRds)."\n",'Jpush');
            $r = Lib::httpPostUrlEncode(EX_DOMAIN . 'exchange/MutiJpushes/send', $valRds);
            Lib::pLog('r.txt','RESULT:'.$r."\n",'Jpush');
            //删除redis或临时表中的数据
            $redisMsg->zRem('jpush_repayment',$data[0]);
            die('还款通知推送成功！');
        }else{
            die('暂无数据');
        }
    }

    public function consume(){
        $redisMsg = Redis::instance('plan');
        $data = $redisMsg->zRangeByScore('jpush_consume','-inf',time(),['withscores'=>false,'limit'=>[0,1]]);
        if($data) {
            $valRds = json_decode($data[0], true);
            //插入数据库推送记录表中
            //从模型中获取当前记录中对应appid的数据库操作句柄
            $dbHandle = $this->M()->getDb($valRds['appid']);
            //构造要插入的数据
            $pushData = [
                'uid' => $valRds['deviceid'],
                'title' => '消费通知',
                'content' => $valRds['content'],
                'user_type' => 1,
                'read_unread' => 2,
                'type' => 2,
                'status' => 1,
                'create_time' => Lib::getMs()
            ];
            $dbHandle->insert('system_message',$pushData);
            $insertId = $dbHandle->id();
            $valRds['pars'] = '{"id":'.$insertId.'}';
            Lib::pLog('c.txt','url:'.EX_DOMAIN.'exchange/MutiJpushes/send,'.'rs:'.json_encode($valRds)."\n",'Jpush');
            $r = Lib::httpPostUrlEncode(EX_DOMAIN . 'exchange/MutiJpushes/send', $valRds);
            Lib::pLog('c.txt','RESULT:'.$r."\n",'Jpush');
            //删除redis或临时表中的数据
            $redisMsg->zRem('jpush_consume', $data[0]);
            die('消费通知推送成功！');
        }else{
            die('暂无数据');
        }
    }

}


?>
<?php

namespace App\WWW\Controller;
use Core\Base\Controller;
use Core\Extend\Redis;
use Core\DB\DBQ;
use Core\Lib;


class CronGather extends Controller
{
    public $appid;
    public function __construct($controller, $action)
    {
        parent::__construct($controller, $action);
        $this->appid = Lib::request('appid');
    }

    public function index(){
        $redis = Redis::instance('plan');
        $data = $redis->zRangeByScore($this->appid.'_gather','-inf','inf',['withscores'=>true,'limit'=>[0,1000]]);
        //var_dump($data);
        //exit;
        if($data) {
            foreach ($data as $val => $k) {
                $v = json_decode($val, true);
                if ($v['card_type'] == 1) {
                    //请求代付查询接口

                    if (true) {
                        $v['is_pay'] = 1;
                        $r = DBQ::add('bill', $v);
                        if ($r) {
                            $redis->zRemRangeByScore($this->appid . '_gather', $k, $k);
                        }
                        echo "<p>success</p>";
                    }
                }
                if ($v['card_type'] == 2) {
                    //请求代收查询接口
                    if (true) {
                        $v['is_pay'] = 1;
                        $r = DBQ::add('bill', $v);
                        if ($r) {
                            $redis->zRemRangeByScore($this->appid . '_gather', $k, $k);
                        }
                        echo "<p>success</p>";
                    }
                }
            }
        }else{
            echo "no record\n";
        }

    }


}




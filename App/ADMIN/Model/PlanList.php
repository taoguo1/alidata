<?php
namespace App\ADMIN\Model;

use Core\Base\Model;
use Core\DB\DBQ;
use Core\Extend\Redis;

class PlanList extends Model
{

    public function getList($pageArr = null, $condition = null)
    {
        // $pageArr, $table, $join, $columns = null, $where = null
        $data = DBQ::pages($pageArr, 'plan_list (P)', [
            '[>]user (U)' => [
                'P.user_id' => 'id'
            ]
        ], [
            'P.id',
            'U.real_name',
            'P.amount',
            'P.plan_id',
            'P.plan_type',
            'P.start_time',
            'P.end_time',
            'P.order_sn',
            'P.status',
            'P.create_time'
        ], $condition);

        return $data;
    }

    public function add($data)
    {
        return DBQ::add('plan_list', $data);
    }

    public function del($id = 0)
    {
        return DBQ::del('plan_list', [
            'id' => $id
        ]);
    }

    public function delAll($ids)
    {
        return DBQ::del('plan_list', [
            'id' => $ids
        ]);
    }

    public function redisAll()
    {
        $redis = Redis::instance();
        $dataold = $redis->zRangeByScore('plan_list_ing:start_time','-inf','inf',['withscores','limit']);
        foreach($dataold as $k => $v){
            $redis->zRem('plan_list_ing:start_time',$v);
        }
        $time = strtotime("+2 day");
        $time = date('Y-m-d',$time);
        $stime = strtotime($time." 00:00:00");
        $etime = strtotime($time." 23:59:59");
        $data = DBQ::getAll('plan_list','*',['start_time[>=]'=>$stime,'start_time[<=]'=>$etime]);

        if($data) {
            foreach($data as $k => $v){
                $redis->zAdd('plan_list_ing:start_time',$v['start_time'],json_encode($v));
            }
            return 1;
        }else{
            return 0;
        }
    }

    public function inIngAll()
    {
        $time = strtotime("+6 day");
        $time = date('Y-m-d',$time);
        $stime = strtotime($time." 00:00:00");
        $etime = strtotime($time." 23:59:59");
        $data = DBQ::getAll('plan_list','*',['start_time[>=]'=>$stime,'start_time[<=]'=>$etime]);
        if($data) {
            $ret = DBQ::add('plan_list_ing', $data);
        }
        return $ret;
    }

    public function edit($id = 0, $data)
    {
        return DBQ::upd('plan_list', $data, [
            'id' => $id
        ]);
    }
}
<?php
namespace App\ADMIN\Model;

use Core\Lib;
use Core\Base\Model;
use Core\DB\DBQ;
use Core\Extend\Dwz;
use App\WWW\Model\Cron;
class SystemMessage extends Model{
    public function getList($pageArr = null, $condition = null) {
        $data = $this->page($pageArr, 'system_message', '*', $condition);
        return $data;
    }
    public function add()
    {
//  	$user = DBQ::getRow('user', '*', ['id' => Lib::post('user_id')]);
        return $this->insert('system_message', [
//  			'uid' => $user['id'],
        		'status' => 1 ,
        		'title' => Lib::post ( 'title' ),
//              'user_type' => Lib::post ( 'user_type' ),
                'type' => Lib::post ( 'type' ),
                'describe' => Lib::post ( 'describe' ),
                'content' => Lib::post ( 'content' ),
                'read_unread' => Lib::post ( 'read_unread' ),
                'create_time' => lib::getMs()
        ]);
    }
    //推送
    public function addagent($data){
        
        //将推送信息插入总平台push表
        $ret = DBQ::add('push',$data);
        //将推送文章id插入商户数据表
        for($user=0;$user<count($data);$user++){
            $dbConfig =new Cron();
            $info=$dbConfig->getDb($data[$user]['appid']);
            $push=$info->insert('push',['create_time'=>$data[$user]['create_time'],'content_id'=>$data[$user]['content_id']]);
        }
        return $push;
    }

    public function del($id = 0)
    {
        return DBQ::del('system_message', [
            'id' => $id
        ]);
    }

    public function delAll($ids)
    {
        return DBQ::del('system_message', [
            'id' => $ids
        ]);
    }

    public function edit($id = 0, $data=[])
    {   
    	$user = DBQ::getRow('user', '*', ['id' => Lib::post('user_id')]);
        $data=[
//      		'uid' => $user['id'],
				'uid' => Lib::post('user_id'),
        		'status' => 1 ,
        		'read_unread' => Lib::post ( 'read_unread' ),
                'title' => Lib::post ( 'title' ),
                'user_type' => Lib::post ( 'user_type' ),
                'type' => Lib::post ( 'type' ),
                'describe' => Lib::post ( 'describe' ),
                'content' => Lib::post ( 'content' ),
               
        ];
        return DBQ::upd('system_message', $data, [
            'id' => $id
        ]);
    }
        /**
     * 获取所有商户
     */
    public function  oemList($pageArr = null,$condition=null)
    {
        $sql = "SELECT A.* FROM dzz_merc A " . $condition;
        $data = DBQ::origPage($pageArr, $sql);
        return $data;
    }
}
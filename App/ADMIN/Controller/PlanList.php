<?php
namespace App\ADMIN\Controller;
use Core\Lib;
use Core\Base\Controller;
use Core\Extend\Dwz;
use App\WWW\Model\Cron;
use Core\Extend\Redis;
class PlanList extends Controller {
	/**
     *
     * @name 查询
     */
    public function index($id = null) {
        $real_name 		= Lib::request ( 'real_name' );
        $plan_type 		= Lib::request ( 'plan_type' );
        $start_time 	= Lib::request ( 'start_time' );
        $end_time 		= Lib::request ( 'end_time' );
        $finish_type 	= Lib::request ( 'finish_type' );
        $status 		= Lib::request ( 'status' );
        $order_sn 		= Lib::request ( 'order_sn' );

        $condition = null;
        ($id) ? $condition['AND']['P.plan_id'] = $id : null;
        ($real_name) ? $condition['AND']['U.real_name[~]'] = $real_name : null;
        ($plan_type) ? $condition ['AND'] ['P.plan_type'] = $plan_type : null;
        ($order_sn) ? $condition ['AND'] ['P.order_sn'] = $order_sn : null;
        ($start_time) ? $condition ['AND'] ['P.start_time[>=]'] = strtotime($start_time) : null;
        ($end_time) ? $condition ['AND'] ['P.end_time[<=]'] = strtotime($end_time . " 23:59:59") : null;
        ($finish_type) ? $condition ['AND'] ['P.finish_type'] = $finish_type : null;
        ($status) ? $condition ['AND'] ['P.status'] = $status : null;
        $condition ['ORDER'] = [
            'P.id' => 'DESC'
        ];

        
        $pageArr = Lib::setPagePars ();
        if ($pageArr ['orderField']) {
            $columns ['ORDER'] = [
                $pageArr ['orderField'] => strtoupper ( $pageArr ['orderDirection'] )
            ];
        }
        $condition ['LIMIT'] = [
            ($pageArr['pageNum']- 1)*$pageArr['numPerPage'],$pageArr['numPerPage']
        ];
		$dbConfig =new Cron();
		$appid=Lib::request ( 'appid' );
		$info=$dbConfig->getDb($appid);
		$data=$info->select('plan_list (P)', [
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
		$data['list']=$data;
		
		//查询redis信息
		$redis = Redis::instance('plan');
		foreach($data['list'] as $k=>$v){
			$dat = $redis->hGet('rc_plan_data',$appid.'_'.$v['id']);
			if($dat==1){
				$data['list'][$k]['redis']=1;
			}else{
				$data['list'][$k]['redis']=0;
			}
		}
        $data2=$info->select('plan_list (P)', [
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
        ]);
		$data['totalCount']=count($data2);
		$data['numPerPage']=$pageArr['numPerPage'];
		$data['pageNum']=$pageArr['pageNum'];
        $dic = Lib::loadFile('Config/Dictionary.php');
		$this->assign ( "appid", $appid );
        $this->assign ( "data", $data );
        $this->assign ( "planlistType", $dic['planlistType'] );
        $this->assign ( "planlistStatus", $dic['planlistStatus'] );
        $this->view ();
    }


	public function ListForDay(){
		$real_name 		= Lib::request ( 'real_name' );
        $plan_type 		= Lib::request ( 'plan_type' );
        $start_time 	= Lib::request ( 'start_time' );
        $end_time 		= Lib::request ( 'end_time' );
        $finish_type 	= Lib::request ( 'finish_type' );
        $status 		= Lib::request ( 'status' );
        $order_sn 		= Lib::request ( 'order_sn' );

        $condition = null;
        ($id) ? $condition['AND']['P.plan_id'] = $id : null;
        ($real_name) ? $condition['AND']['U.real_name[~]'] = $real_name : null;
        ($plan_type) ? $condition ['AND'] ['P.plan_type'] = $plan_type : null;
        ($order_sn) ? $condition ['AND'] ['P.order_sn'] = $order_sn : null;
        //($end_time) ? $condition ['AND'] ['P.end_time[<=]'] = strtotime($end_time . " 23:59:59") : null;
        ($finish_type) ? $condition ['AND'] ['P.finish_type'] = $finish_type : null;
        $condition ['AND'] ['P.status[!]'] = 6 ;		
		$daystart=strtotime(date('Y-m-d',time())." 00:00:00");
        $dayend=strtotime(date('Y-m-d',time())." 23:59:59");
		$condition['AND']['P.start_time[<>]'] =[$daystart,$dayend];
        $condition ['ORDER'] = [
            'P.id' => 'DESC'
        ];
        $pageArr = Lib::setPagePars ();
        if ($pageArr ['orderField']) {
            $columns ['ORDER'] = [
                $pageArr ['orderField'] => strtoupper ( $pageArr ['orderDirection'] )
            ];
        }
        /*$condition ['LIMIT'] = [
            ($pageArr['pageNum']- 1)*$pageArr['numPerPage'],$pageArr['numPerPage']
        ];*/
		
		//获取所有商家信息
		$model = new \App\ADMIN\Model\Index();
		$mersInfo=$model->getMercInfo();		
		for($user=0;$user<count($mersInfo);$user++){
			$dbConfig =new Cron();
			$info=$dbConfig->getDb($mersInfo[$user]['appid']);
			$data[$user]=$info->select('plan_list (P)', [
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
			foreach($data[$user] as $k=>$v){
				$data[$user][$k]['name']=$mersInfo[$user]['app_name'];
				$data[$user]['list'][]=$data[$user][$k];	
			}
		}
		foreach($data as $k1=>$v1){
			array_pop($v1);
			if(is_array($v1)&&!empty($v1)){
				foreach($v1 as $k2=>$v2){
					if(!empty($v2)){
						$data['list'][]=$v2;
					}
				}
			}
		}

		//查询redis信息
		/*$redis = Redis::instance('plan');
		foreach($data['list'] as $k=>$v){
			$dat = $redis->hGet('rc_plan_data',$appid.'_'.$v['id']);
			if($dat==1){
				$data['list'][$k]['redis']=1;
			}else{
				$data['list'][$k]['redis']=0;
			}
		}
        $data2=$info->select('plan_list (P)', [
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
        ]);
		$data['totalCount']=count($data2);*/
		
		$data['numPerPage']=$pageArr['numPerPage'];
		$data['pageNum']=$pageArr['pageNum'];
        $dic = Lib::loadFile('Config/Dictionary.php');
		$this->assign ( "appid", $appid );
        $this->assign ( "data", $data );
        $this->assign ( "planlistType", $dic['planlistType'] );
        $this->assign ( "planlistStatus", $dic['planlistStatus'] );
        $this->view ();
	}


    //删除redis
    public function delRedis(){
    	$planListId=Lib::request ('planListId');
		$appid=Lib::request ('appid');
		$redis = Redis::instance('plan');
		$isDel=$redis->hdel('rc_plan_data',$appid.'_'.$planListId);
		if($isDel){
			echo json_encode('1');
		}else{
			echo json_encode('0');
		}
    }
        
    /**
     *
     * @name 查询
     */
    public function search() {
    	$id 		= Lib::request ( 'id' );
        $real_name 		= Lib::request ( 'real_name' );
        $plan_type 		= Lib::request ( 'plan_type' );
        $start_time 	= Lib::request ( 'start_time' );
        $end_time 		= Lib::request ( 'end_time' );
        $finish_type 	= Lib::request ( 'finish_type' );
        $status 		= Lib::request ( 'status' );

        $condition = null;
        ($id) ? $condition['AND']['P.plan_id'] = $id : null;
        ($real_name) ? $condition['AND']['U.real_name[~]'] = $real_name : null;
        ($plan_type) ? $condition ['AND'] ['P.plan_type'] = $plan_type : null;
        ($start_time) ? $condition ['AND'] ['P.start_time[>=]'] = strtotime($start_time) : null;
        ($end_time) ? $condition ['AND'] ['P.end_time[<=]'] = strtotime($end_time . " 23:59:59") : null;
        ($finish_type) ? $condition ['AND'] ['P.finish_type'] = $finish_type : null;
        ($status) ? $condition ['AND'] ['P.status'] = $status : null;
        $condition ['ORDER'] = [
            'P.id' => 'DESC'
        ];

        $pageArr = Lib::setPagePars2 ();
        if ($pageArr ['orderField']) {
            $columns ['ORDER'] = [
                $pageArr ['orderField'] => strtoupper ( $pageArr ['orderDirection'] )
            ];
        }
		$condition ['LIMIT'] =" (".$pageArr['pageNum']."- 1) * ".$pageArr['numPerPage'].",".$pageArr['numPerPage'];
		$dbConfig =new Cron();
		$appid=Lib::request ( 'appid' );
		$info=$dbConfig->getDb($appid);
		$data=$info->select('plan_list (P)', [
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
		$data['list']=$data;
		//查询redis信息
		$redis = Redis::instance('plan');
		foreach($data['list'] as $k=>$v){
			$dat = $redis->hGet('rc_plan_data',$appid.'_'.$v['id']);
			if($dat==1){
				$data['list'][$k]['redis']=1;
			}else{
				$data['list'][$k]['redis']=0;
			}
		}
		$data['totalCount']=count($data);
		$data['numPerPage']=$pageArr['numPerPage'];
		$data['pageNum']=$pageArr['pageNum'];
        $dic = Lib::loadFile('Config/Dictionary.php');
		$this->assign ( "appid", $appid );
        //print_r($data);
        $this->assign ( "data", $data );
		$this->assign ( "id", $id );
        $this->assign ( "planlistType", $dic['planlistType'] );
        $this->assign ( "planlistStatus", $dic['planlistStatus'] );
        $this->view ();
    }

	
	/**
	 *
	 * @name 删除
	 * @param number $id        	
	 */
	public function del($id = 0) {
		if ($this->M ()->del ( $id )) {
		    Dwz::success ( Lib::getUrl ( $this->M ()->modelName), $this->M ()->modelName );
		} else {
			Dwz::err ();
		}
	}
	
	/**
	 *
	 * @name 批量删除
	 */
	public function delAll() {
		$ids = explode ( ',', Lib::post ( 'ids' ) );
		if ($this->M ()->delAll ( $ids )) {
			Dwz::success ( Lib::getUrl ( $this->M ()->modelName ), $this->M ()->modelName );
		} else {
			Dwz::err ();
		}
	}

    /**
     *
     * @name 批量移动
     */
    public function redisAll() {

        $ids = explode ( ',', Lib::post ( 'ids' ) );
        if ($this->M ()->redisAll ( $ids )) {
            Dwz::success ( Lib::getUrl ( $this->M ()->modelName ), $this->M ()->modelName );
        } else {
            Dwz::err ();
        }

        //$this->ingAll();
    }

    /**
     *
     * @name 批量移动
     */
    public function ingAll() {
        /*
        $ret = $this->M()->inIngAll();
        if ($ret) {
            Dwz::success ( Lib::getUrl ( $this->M ()->modelName ), $this->M ()->modelName );
        } else {
            Dwz::err ();
        }
        */
    }
	
	/**
	 *
	 * @name 编辑
	 * @param number $id        	
	 * @param $act        	
	 */
	public function edit() {
		$this->view ();
	}
}
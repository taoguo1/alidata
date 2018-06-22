<?php
namespace App\ADMIN\Controller;
use Core\Lib;
use Core\Base\Controller;
use Core\Extend\Dwz;
use Core\DB\DBQ;
use App\WWW\Model\Cron;
use Core\Extend\Redis;
class Plan extends Controller {
	/**
	 *
	 * @name 查询
	 */
	public function index() {
		
		$real_name 		= Lib::request ( 'real_name' );
		$card_no 		= Lib::request ( 'card_no' );
		$start_time 	= Lib::request ( 'start_time' );
		$end_time 		= Lib::request ( 'end_time' );
		$finish_type 	= Lib::request ( 'finish_type' );
		$status 		= Lib::request ( 'status' );
		$condition = null;
		($real_name) ? $condition['AND']['U.real_name[~]'] = $real_name : null;
		($card_no) ? $condition ['AND'] ['P.card_no'] = $card_no : null;
		($start_time) ? $condition ['AND'] ['P.start_time[>=]'] = strtotime($start_time) : null;
		($end_time) ? $condition ['AND'] ['P.end_time[<=]'] = strtotime($end_time . " 23:59:59") : null;
		($finish_type) ? $condition ['AND'] ['P.finish_type'] = $finish_type : null;
		($status) ? $condition ['AND'] ['P.status'] = $status : null;
		$pageArr = Lib::setPagePars ();
		if ($pageArr ['orderField']) {
			$columns ['ORDER'] = [
				$pageArr ['orderField'] => strtoupper ( $pageArr ['orderDirection'] )
			];
		}
		$condition ['LIMIT'] = [
            ($pageArr['pageNum']- 1)*$pageArr['numPerPage'],$pageArr['numPerPage']
        ];
		$condition ['ORDER'] = [
			'P.id' => 'DESC'
		];
		//var_dump($condition);
		$dbConfig =new Cron();
		$appid=Lib::request ( 'appid' );
		$info=$dbConfig->getDb($appid);
		$data=$info->select('plan (P)', [
            '[>]user (U)' => [
                'P.user_id' => 'id'
            ]
        ], [
            'P.id',
            'U.real_name',
            'P.amount',
            'P.card_no',
            'P.start_time',
            'P.end_time',
            'P.duration',
            'P.poundage',
            'P.finish_time',
            'P.finish_type',
            'P.status',
            'P.create_time'
        ], $condition);



		$data['list']=$data;
        $data2=$info->select('plan (P)', [
            '[>]user (U)' => [
                'P.user_id' => 'id'
            ]
        ], [
            'P.id',
            'U.real_name',
            'P.amount',
            'P.card_no',
            'P.start_time',
            'P.end_time',
            'P.duration',
            'P.poundage',
            'P.finish_time',
            'P.finish_type',
            'P.status',
            'P.create_time'
        ]);
        $data['totalCount']=count($data2);


		$data['numPerPage']=$pageArr['numPerPage'];
		$data['pageNum']=$pageArr['pageNum'];
		
       
		$this->assign ( "data", $data );
        $this->assign ( "appid", $appid );
        $dic = Lib::loadFile('Config/Dictionary.php');
        $this->assign ( "planFinishType", $dic['planFinishType'] );
        $this->assign ( "planStatus", $dic['planStatus'] );
		$this->view ();
	}
	

   


     public function PlanForDay(){
        //$real_name      = Lib::request ( 'real_name' );
        //$plan_type      = Lib::request ( 'plan_type' );
        //$start_time     = Lib::request ( 'start_time' );
        $end_time       = Lib::request ( 'end_time' );
        //$finish_type    = Lib::request ( 'finish_type' );
        //$status         = Lib::request ( 'status' );
        //$order_sn       = Lib::request ( 'order_sn' );

        $condition = null;
        //($id) ? $condition['AND']['P.plan_id'] = $id : null;
        //($real_name) ? $condition['AND']['U.real_name[~]'] = $real_name : null;
        //($plan_type) ? $condition ['AND'] ['P.plan_type'] = $plan_type : null;
        //($order_sn) ? $condition ['AND'] ['P.order_sn'] = $order_sn : null;
        //($finish_type) ? $condition ['AND'] ['P.finish_type'] = $finish_type : null;
        //$condition ['AND'] ['P.status[!]'] = 6 ;        
        $daystart=strtotime($end_time);
        //$dayend=strtotime($end_time." 23:59:59");
        //var_dump($end_time);
        if($end_time){
            $condition['AND']['P.end_time'] =$daystart;
        }
        
        $condition ['ORDER'] = [
            'P.id' => 'DESC'
        ];
        $pageArr = Lib::setPagePars ();
        if ($pageArr ['orderField']) {
            $columns ['ORDER'] = [
                $pageArr ['orderField'] => strtoupper ( $pageArr ['orderDirection'] )
            ];
        }
        //获取所有商家信息
        $model = new \App\ADMIN\Model\Index();
        $mersInfo=$model->getMercInfo();        
        for($user=0;$user<count($mersInfo);$user++){
            $dbConfig =new Cron();
            $info=$dbConfig->getDb($mersInfo[$user]['appid']);
            $data[$user]=$info->select('plan(P)', [
                '[>]user (U)' => [
                    'P.user_id' => 'id'
                ]
            ], [
                'P.id',
                'U.real_name',
                'P.amount',
                'P.status',
                'P.card_no',
                'P.bank_name',
                'P.start_time',
                'P.end_time',
                'P.duration',
                'P.finish_type',
                'P.finish_time',
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
        $data['numPerPage']=$pageArr['numPerPage'];
        $data['pageNum']=$pageArr['pageNum'];
        $dic = Lib::loadFile('Config/Dictionary.php');
        $this->assign ( "appid", $appid );
        $this->assign ( "data", $data );
        $this->assign ( "planlistType", $dic['planlistType'] );
        $this->assign ( "planlistStatus", $dic['planlistStatus'] );
        $this->view ();
    }
	/**
	 *
	 * @name 添加
	 */
	public function add($act = null) {

        if ($act == 'add') {
            //根据账单日计算开始还款时间
            $stamp = mktime(null,null,null,date('m',time()),6,date('Y',time()));
            $startstampBase = strtotime('+32 hours',(int)$stamp);

            $data = [
                'user_id' => Lib::post ( 'user_id' ),
                'amount' => Lib::post ( 'amount' ),
                'card_no' => Lib::post ( 'card_no' ),
                'start_time' => $startstampBase,
                'end_time' => $startstampBase+3600*24*10,
                'duration' => 10,
                'status' => 1,
                'create_time' => Lib::getMs(),
            ];
            $r = DBQ::add('plan',$data);
            $insertId = DBQ::insertID();

            $tamount = 0;
            //$pamount = Lib::splitAmount($data['amount'],10,950);
            for($i = 1;$i <= 10; $i++){
                $paystampBase = strtotime("+$i days",(int)$stamp);
                $randBase = rand(10,240) + 4800;
                $paystamp = strtotime("+$randBase minutes",(int)$paystampBase);
                if($i == 10){
                    $current_amount = $data['amount'] - $tamount;
                }else{
                    $current_amount = $data['amount'] / 10 + rand(-50,50);
                }
                $tamount += $current_amount;
                $data_1 = [
                    'user_id' => Lib::post ( 'user_id' ),
                    'amount' => $current_amount,
                    'plan_id' => $insertId,
                    'plan_type' => 1,
                    'start_time' => $paystamp,
                    'end_time' => $paystamp + rand(1,60),
                    'order_sn' => Lib::createOrderNo(),
                    'status' => 1,
                    'channel' => 2,
                    'create_time' => Lib::getMs(),
                ];
                $rets_1 = DBQ::add('plan_list',$data_1);
                $tamount_2 = 0;
                $consumestampBase = $paystamp;
                $randconsumeBase = rand(10,30);
                //$camount = Lib::splitAmount($pamount[$i],10,950);
                $consumeNum = rand(2,3);
                for($j = 1;$j <= $consumeNum; $j++){
                    $consumestampBase = strtotime("+$randconsumeBase minutes",(int)$consumestampBase);
                    if($j == $consumeNum){
                        $current_amount_2 = $current_amount - $tamount_2;
                    }else{
                        $current_amount_2 = ($current_amount / $consumeNum) + rand(-100,100);
                    }
                    $tamount_2 += $current_amount_2;
                    $data_2 = [
                        'user_id' => Lib::post ( 'user_id' ),
                        'amount' => $current_amount_2,
                        'plan_id' => $insertId,
                        'plan_type' => 2,
                        'start_time' => $consumestampBase,
                        'end_time' => $consumestampBase + rand(1,60),
                        'order_sn' => Lib::createOrderNo(),
                        'status' => 1,
                        'channel' => 2,
                        'create_time' => Lib::getMs(),
                    ];
                    $rets_2 = DBQ::add('plan_list',$data_2);
                }

            }
            if ($r) {
                Dwz::successDialog ( $this->M ()->modelName, '', 'closeCurrent' );
            } else {
                Dwz::err ();
            }
        }

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
	 * @name 编辑
	 * @param number $id        	
	 * @param $act        	
	 */
	public function edit() {
		$this->view ();
	}
	
	
	//强制完成计划
	public function finishPlan(){
		$dbConfig =new Cron();
		$appid=Lib::request ( 'appid' );
        if(!$appid){
           Dwz::err ();
        }
		$info=$dbConfig->getDb($appid);
        $plan_id = Lib::request('id');
        if(!$plan_id){
            Dwz::err ();
        }
		//根据plan_id获取用户id
		$data1=$info->get('plan',[
            'user_id'
        ],['id'=>$plan_id]);
		if(!$data1['user_id']){
            Dwz::err ();
        }
        $ret = $this->M ()->editPlan($data1['user_id'],$plan_id,3,$appid);
		$data =  $info->select('plan_list','*',['plan_id'=>$plan_id,'user_id'=>$data1['user_id']]);
        foreach($data as $k => $v){
            $redis = Redis::instance('plan');
            $redis->hdel('rc_plan_data',$appid.'_'.$v['id']);
        }	
        if($ret==1){     
            Dwz::successReload ( $this->M ()->modelName );
        }else{    
			Dwz::err ();
        }
	}
}
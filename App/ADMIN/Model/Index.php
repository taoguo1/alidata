<?php
namespace App\ADMIN\Model;
use Core\Lib;
use Core\Base\Model;
use Core\DB\DBQ;
class Index extends Model {
	public $listTreeHtml = '';
	public $listHeaderTreeHtml = '';
	public $listTreeArray = [ ];
	public function getTreeList() {		
	    $listOne = DBQ::getAll ( 'tree', '*', [ 
				'AND' => [ 
						'status[!]' => - 1,						
						'pid' => 0 
				],
				'ORDER' => [ 
						'sort' => 'ASC' 
				] 
		] );
		$this->listTreeArray = $listOne;
		// 查询三级菜单，最大支持3级
		foreach ( $listOne as $kOne => $vOne ) {
			
			$this->listTreeHtml .= '
				<div class="accordionHeader">
					<h2><span>Folder</span>' . $vOne ['name'] . '</h2>
				</div>
				<div class="accordionContent">
					<ul class="tree treeFolder">';
			// 查询二级
			$listTwo = $this->select ( 'tree', '*', [ 
					'AND' => [ 
							'status[!]' => - 1,
							'pid' => $vOne ['id'] 
					],
					'ORDER' => [ 
							'sort' => 'ASC' 
					] 
			] );
			$this->listTreeArray [$kOne] ['two'] = $listTwo;
			
			foreach ( $listTwo as $kTwo => $vTwo ) {
				// 查询三级
				$listThree = $this->select ( 'tree', '*', [ 
						'AND' => [ 
								'status[!]' => - 1,
								'pid' => $vTwo ['id'] 
						],
						'ORDER' => [ 
								'sort' => 'ASC' 
						] 
				] );
				$this->listTreeArray [$kOne] ['two'] [$kTwo] ['three'] = $listThree;
				
				if (! empty ( $listThree )) {
					
					if (! empty ( $vTwo ['controller'] ) && $vTwo ['controller'] != '#') {
						$url = "javascript:void(0);";
						if (! empty ( $vTwo ['controller'] ) && $vTwo ['controller'] != '#') {
							$url = Lib::getUrl ( $vTwo ['controller'], $vTwo ['action'], $vTwo ['pars'] );
						}
						$this->listTreeHtml .= '<li><a href="' . $url . '"  target="' . $vTwo ['target'] . '" rel="' . $vTwo ['controller'] . '" fresh="true">' . $vTwo ['name'] . '</a><ul>';
					} else {
						
						$this->listTreeHtml .= '<li><a>' . $vTwo ['name'] . '</a><ul>';
					}
					
					foreach ( $listThree as $vThree ) {
						$icon = APP_ADMIN_STATIC . 'image/default_list.png';
						if (! empty ( $vThree ['icon'] )) {
							$icon = APP_SITE_PATH . $vThree ['icon'];
						}
						$url = "javascript:void(0);";
						if (! empty ( $vThree ['controller'] ) && $vThree ['controller'] != '#') {
						    $url = Lib::getUrl ( $vThree ['controller'], $vThree ['action'], $vThree ['pars'] );
						}
						$this->listTreeHtml .= '<li><a href="' . $url . '"  target="' . $vThree ['target'] . '" rel="' . $vThree ['alias'] . '" fresh="true"><img class="tree-img" src=' . $icon . '>' . $vThree ['name'] . '</a>';
					}
					$this->listTreeHtml .= '</ul>';
				} else {
					$icon = APP_ADMIN_STATIC . 'image/default_list.png';
					if (! empty ( $vTwo ['icon'] )) {
						$icon = APP_SITE_PATH . $vTwo ['icon'];
					}
					$url = "javascript:void(0);";
					if (! empty ( $vTwo ['controller'] ) && $vTwo ['controller'] != '#') {
						$url = Lib::getUrl ( $vTwo ['controller'], $vTwo ['action'], $vTwo ['pars'] );
					}
					
					$this->listTreeHtml .= '<li><a href="' . $url . '" target="' . $vTwo ['target'] . '" rel="' . $vTwo ['alias'] . '" fresh="true"><img class="tree-img" src=' . $icon . '>' . $vTwo ['name'] . '</a>';
				}
				$this->listTreeHtml .= '</li>';
			}
			$this->listTreeHtml .= '</ul></div>';
		}
		$array = [ 
				'left' => $this->listTreeHtml,
				'data' => $this->listTreeArray 
		];
		return $array;
	}

	
	
	public function getMercInfo(){
		return DBQ::getAll( 'merc',['appid','app_name'], [ 
				'ORDER' => [ 
						'create_time' => 'ASC' 
				] 
		] );
	}
	public function getHeaderNavList() {
		return DBQ::getAll( 'header_nav', '*', [ 
				'ORDER' => [ 
						'sort' => 'ASC' 
				] 
		] );
	}
	public function getLoginInfo() {
		return DBQ::getRow( 'system_config', '*' );
	}

	//汇总看板
	public function summaryPlate(){
		$data = array();
		//消费金额
		$condition 	= null;
		$condition	= ['status'=>1];
		$data['billAmount']	= DBQ::getSum('bill',['amount'],$condition);
		//消费笔数
		$condition 	= null;
		$condition	= ['status'=>1];
		$data['billNum']	= DBQ::getCount('bill',$condition);
		//会员余额
		$condition 	= null;
		$condition	= ['U.status'=>1];
		$data['userBalance'] = DBQ::getSum('user(U)', ['[>]user_ext(UE)' => ['U.id' => 'user_id']], ['UE.balance'],$condition);
		//会员总数
		$condition 	= null;
		$condition	= ['status'=>1];
		$data['userNum']	= DBQ::getCount('user',$condition);
		//代理余额
		$condition 	= null;
		$condition	= ['A.status'=>1];
		$data['agentBalance'] = DBQ::getSum('agent(A)', ['[>]agent_ext(AE)' => ['A.id' => 'agent_id']], ['AE.total_commission'],$condition);
		//代理总数
		$condition 	= null;
		$condition	= ['status'=>1];
		$data['agentNum']	= DBQ::getCount('agent',$condition);
		//信用卡
		$condition 	= null;
		$condition	= ['status'=>1];
		$data['creditCard']	= DBQ::getCount('credit_card',$condition);
		//储蓄卡
		$condition 	= null;
		$condition	= ['status'=>1];
		$data['debitCard']	= DBQ::getCount('debit_card',$condition);

		//还款计划
		$condition 	= null;
		$condition	= ['status'=>1];
		$data['plan']	= DBQ::getCount('plan',$condition);
		//还款任务
		$condition 	= null;
		$condition	= ['status'=>1];
		$data['planList']	= DBQ::getCount('plan_list',$condition);

		//代付金额
        $data['takePay']	= DBQ::getSum('plan','amount');

        //消费任务
        $condition 	= null;
        $condition	= ['status'=>2];
        $data['resumeAmount']	= DBQ::getSum('plan_list_ing','amount',$condition);

		return $data;
	}

	/**
	 * 走势图获取信用卡数据
	 * @return string
	 */
	public function getCreditCard(){
		$monthDataArr	= $this->getMonth();
		$lastYear = strtotime("-1 year")*1000;
		$sql = "
SELECT
	FROM_UNIXTIME(
		create_time / 1000,
		'%Y%m'
	)AS dates,
	(
		SELECT
			COUNT(1)
		FROM
			dzz_credit_card
		WHERE
			FROM_UNIXTIME(
				create_time / 1000,
				'%Y%m'
			)= dates AND status = 1
	)AS num
FROM
	`dzz_credit_card`
WHERE create_time>$lastYear AND status = 1
GROUP BY
	dates
		";

		$data = $this->db->query($sql)->fetchAll();
		$dates 	= array_column($data,'dates');
		$num 	= array_column($data,'num');
		$data_ext	= array();
		foreach($monthDataArr as $k=>$v){
			$data_ext[$k]['mounth']	= $v;
			$data_ext[$k]['num']	= 0;;
			foreach($dates as $ak=>$av){
				if($v ==  $av){
					$data_ext[$k]['num']	= $num[$ak];
				}
			}
		}
		$data_ext	= array_reverse($data_ext);
		$dataStr['mounth'] 	= '['.implode(',',array_column($data_ext,'mounth')).']';
		$dataStr['num'] 	= '['.implode(',',array_column($data_ext,'num')).']';
		return $dataStr;
	}

	/**
	 * 走势图获取储蓄卡数据
	 * @return string
	 */
	public function getDebitCard(){
		$monthDataArr	= $this->getMonth();
		$lastYear = strtotime("-1 year")*1000;
		$sql = "
SELECT
	FROM_UNIXTIME(
		create_time / 1000,
		'%Y%m'
	)AS dates,
	(
		SELECT
			COUNT(1)
		FROM
			dzz_debit_card
		WHERE
			FROM_UNIXTIME(
				create_time / 1000,
				'%Y%m'
			)= dates AND status = 1
	)AS num
FROM
	`dzz_debit_card`
WHERE create_time>$lastYear AND status = 1
GROUP BY
	dates
		";
		$data = $this->db->query($sql)->fetchAll();
		$dates 	= array_column($data,'dates');
		$num 	= array_column($data,'num');
		$data_ext	= array();
		foreach($monthDataArr as $k=>$v){
			$data_ext[$k]['mounth']	= $v;
			$data_ext[$k]['num']	= 0;;
			foreach($dates as $ak=>$av){
				if($v ==  $av){
					$data_ext[$k]['num']	= $num[$ak];
				}
			}
		}
		$data_ext	= array_reverse($data_ext);
		$dataStr['mounth'] 	= '['.implode(',',array_column($data_ext,'mounth')).']';
		$dataStr['num'] 	= '['.implode(',',array_column($data_ext,'num')).']';
		return $dataStr;
	}

	/**
	 * 走势图获取账单数据
	 * @return string
	 */
	public function getBillMount(){
		$monthDataArr	= $this->getMonth();
		$sql = "
SELECT
	FROM_UNIXTIME(
		create_time / 1000,
		'%Y%m'
	)AS dates,
	(
		SELECT
			SUM(amount)
		FROM
			dzz_bill
		WHERE
			FROM_UNIXTIME(
				create_time / 1000,
				'%Y%m'
			)= dates AND status = 1
	)AS amount
FROM
	`dzz_bill`
WHERE status = 1
GROUP BY
	dates
		";
		$data = $this->db->query($sql)->fetchAll();
		$amount 	= array_column($data,'amount');
		$datadates 	= array_column($data,'dates');
		$data_ext	= array();
		foreach($monthDataArr as $k=>$v){
			$data_ext[$k]['mounth']	= $v;
			$data_ext[$k]['amount']	= 0;;
			foreach($datadates as $ak=>$av){
				if($v ==  $av){
					$data_ext[$k]['amount']	= $amount[$ak];
				}
			}
		}
		$data_ext	= array_reverse($data_ext);
		$dataStr['mounth'] 	= '['.implode(',',array_column($data_ext,'mounth')).']';
		$dataStr['amount'] 	= '['.implode(',',array_column($data_ext,'amount')).']';
		return $dataStr;
	}

	/**
	 * 走势图获取还款天数数据
	 * @return string
	 */
	public function getRepayDay(){
		$dayDataArr	= $this->getDay();
		$sql = "
SELECT
	FROM_UNIXTIME(
		create_time / 1000,
		'%Y%m%d'
	)AS dates,
	(
		SELECT
			SUM(duration)
		FROM
			dzz_plan
		WHERE
			FROM_UNIXTIME(
				create_time / 1000,
				'%Y%m%d'
			)= dates AND status = 1
	)AS durationSum
FROM
	`dzz_plan`
WHERE
 	FROM_UNIXTIME(
		create_time / 1000,
		'%Y%m'
	) = DATE_FORMAT( CURDATE( ) , '%Y%m' ) AND status = 1
GROUP BY
	dates
		";
		$data = $this->db->query($sql)->fetchAll();
		$dates 			= array_column($data,'dates');
		$durationSum 	= array_column($data,'durationSum');
		$data_ext	= array();
		foreach($dayDataArr as $k=>$v){
			$data_ext[$k]['dates']			= $v;
			$data_ext[$k]['durationSum']	= 0;
			foreach($dates as $ak=>$av){
				if($v ==  $av){
					$data_ext[$k]['durationSum']	= $durationSum[$ak];
				}
			}
		}
		$data_ext	= array_reverse($data_ext);
		$dataStr['dates'] 		= '['.implode(',',array_column($data_ext,'dates')).']';
		$dataStr['durationSum'] = '['.implode(',',array_column($data_ext,'durationSum')).']';
		return $dataStr;
	}


	/**
	 * 获取前30天
	 * @return array
	 */
	public function getDay(){
		$dateArr = array();
		for ($x=1; $x<=30; $x++) {
			$dateArr[] = date('Ymd',strtotime("-$x day"));
		}
		return $dateArr;
	}

	/**
	 * 获取前12个月
	 * @return array
	 */
	public function getMonth(){
		$dateArr[] = date('Ym');
		for ($x=1; $x<=12; $x++) {
			$dateArr[] = date('Ym',strtotime("-$x month"));
		}
		return $dateArr;
	}
}
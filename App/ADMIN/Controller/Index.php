<?php
namespace App\ADMIN\Controller;
use Core\Base\Controller;

class Index extends Controller {
	/**
	 *
	 * @name 系统主页
	 */
	public function index() {
	    $model = new \App\ADMIN\Model\Index();
		$listTreeArray = $model->getTreeList ();
		$listTreeHtml = $listTreeArray ['left'];
		//填充计划管理  two字段数据
		$mersInfo=$model->getMercInfo();
		$treeInfo='';
		$username=[];
		foreach($mersInfo as $k=>$v){
			$listTreeArray ['data'][3]['two'][$k]['target']='navTab';
			$listTreeArray ['data'][3]['two'][$k]['name']=$v['app_name'];
			$listTreeArray ['data'][3]['two'][$k]['three'][0]['name']='计划管理';
			$listTreeArray ['data'][3]['two'][$k]['three'][1]['name']='计划详情管理';
			$username[$k]['name']=$v['app_name'];
			$username[$k]['controller']='Plan';
			$username[$k]['action']='index';
			$username[$k]['x_controller']='PlanList';
			$username[$k]['x_action']='index';
			$username[$k]['appid']=$v['appid'];
			$treeInfo.='<ul class="tree treeFolder collapse" > <li>	
				      <a>'.$username[$k]['name'].'</a>
				      <ul>
				      
				         <li>
				          <a href="/admin/'.$username[$k]['controller'].'/'.$username[$k]['action'].'?appid='.$username[$k]['appid'].'" target="navTab" rel="Plan" fresh="true">
				            <img class="tree-img" src=/Uploads/treeIcon/20180129155535_796231-local.png>计划管理</a>
				          <li>
				            <a href="/admin/'.$username[$k]['x_controller'].'/'.$username[$k]['x_action'].'?appid='.$username[$k]['appid'].'" target="navTab" rel="PlanList" fresh="true">
				              <img class="tree-img" src=/Uploads/treeIcon/20180129155603_979318-local.png>计划详情管理</a></ul>
				      </li>
                    </ul>';
		}
		$str='<div class="accordionHeader">
					<h2><span>Folder</span>基本管理</h2>
				</div>
				<div class="accordionContent">
					<ul class="tree treeFolder"><li><a href="/admin/Merc/" target="navTab" rel="Merc" fresh="true"><img class="tree-img" src=/Uploads/treeIcon/20180129151929_239004-local.png>OEM商家管理</a></li><li><a href="/admin/Bank/" target="navTab" rel="Bank" fresh="true"><img class="tree-img" src=/Static/Admin/image/default_list.png>银行分类</a></li>
					<li><a href="/admin/SystemMessage/" target="navTab" rel="SystemMessage" fresh="true"><img class="tree-img" src=/Static/Admin/image/default_list.png>消息通知管理</a></li>	
						
						
						<li><a href="/admin/OverseasBill/" target="navTab" rel="OverseasBill" fresh="true"><img class="tree-img" src=/Static/Admin/image/default_list.png>境外支付账单</a></li>

<li><a href="/admin/OverseasBillByMl/" target="navTab" rel="OverseasBillByMl" fresh="true"><img class="tree-img" src=/Static/Admin/image/default_list.png>马来境外支付账单管理</a></li>


<li><a href="/admin/OverseasProfitByMl/" target="navTab" rel="OverseasProfitByMl" fresh="true"><img class="tree-img" src=/Static/Admin/image/default_list.png>马来境外金额管理</a></li>

<li><a href="/admin/KcpOrder/" target="navTab" rel="KcpOrder" fresh="true"><img class="tree-img" src=/Static/Admin/image/default_list.png>卡测评统计</a></li>

					</ul></div>
				<div class="accordionHeader">
					<h2><span>Folder</span>账单统计</h2>
				</div>
				<div class="accordionContent">
					<ul class="tree treeFolder">


					    <li><a href="/admin/BillStatistics/" target="navTab" rel="BillStatistics" fresh="true"><img class="tree-img" src=/Static/Admin/image/default_list.png>账单统计</a></li>

						<li><a href="/admin/CreditAmount/" target="navTab" rel="CreditAmount" fresh="true"><img class="tree-img" src=/Static/Admin/image/default_list.png>消费金额</a></li>


						<li><a href="/admin/ConsumeCount/" target="navTab" rel="ConsumeCount" fresh="true"><img class="tree-img" src=/Static/Admin/image/default_list.png>消费笔数</a></li>
						
						<li><a href="/admin/CreditHkAmount/" target="navTab" rel="CreditHkAmount" fresh="true"><img class="tree-img" src=/Static/Admin/image/default_list.png>还款统计</a></li>

					</ul></div>

					

					


				<div class="accordionHeader">
					<h2><span>Folder</span>系统设置</h2>
				</div>
				<div class="accordionContent">
					<ul class="tree treeFolder"><li><a>角色权限</a><ul><li><a href="/admin/Role/"  target="navTab" rel="Role" fresh="true"><img class="tree-img" src=/Uploads/treeIcon/20180129155535_796231-local.png>角色管理</a><li><a href="/admin/Admin/"  target="navTab" rel="Admin" fresh="true"><img class="tree-img" src=/Uploads/treeIcon/20180129155603_979318-local.png>管理员管理</a></ul></li><li><a href="/admin/SystemConfig/" target="navTab" rel="SystemConfig" fresh="true"><img class="tree-img" src=/Uploads/treeIcon/20180129155637_453457-local.png>系统配置</a></li><li><a href="/admin/Help/" target="navTab" rel="Help" fresh="true"><img class="tree-img" src=/Uploads/treeIcon/20180129155711_998818-local.png>开发帮助</a></li><li><a href="/admin/Tree/" target="navTab" rel="Tree" fresh="true"><img class="tree-img" src=/Uploads/treeIcon/20180129155746_395147-local.png>菜单树管理</a></li><li><a href="/admin/HeaderNav/" target="navTab" rel="HeaderNav" fresh="true"><img class="tree-img" src=/Uploads/treeIcon/20180129155819_351339-local.png>顶部导航配置</a></li></ul></div>
				<div class="accordionHeader">
					<h2><span>Folder</span>计划管理-bak</h2>
				</div>	
				<div class="accordionContent">
				    <ul class="tree treeFolder">
				    <li><a href="/admin/PlanBak/index" target="navTab" rel="PlanBak" fresh="true"><img class="tree-img" src=/Uploads/treeIcon/20180129155637_453457-local.png>计划管理-bak</a></li>
				    <li><a href="/admin/PlanListBak/ListForDay" target="navTab" rel="ListForDay" fresh="true"><img class="tree-img" src=/Uploads/treeIcon/20180129155637_453457-local.png>计划详情管理-bak</a></li>
				    </ul>
				</div>
				<div class="accordionHeader">
					<h2><span>Folder</span>计划管理</h2>
				</div>
				<div class="accordionContent">
				    <ul class="tree treeFolder">
				    <li><a href="/admin/Plan/PlanForDay" target="navTab" rel="PlanForDay" fresh="true"><img class="tree-img" src=/Uploads/treeIcon/20180129155637_453457-local.png>计划管理</a></li>

				    <li><a href="/admin/PlanList/ListForDay" target="navTab" rel="ListForDay" fresh="true"><img class="tree-img" src=/Uploads/treeIcon/20180129155637_453457-local.png>当天计划详情管理</a></li>				    

				    </ul>
					'.$treeInfo.'
               </div>';		
		$dataTree= $listTreeArray ['data'];
		$this->assign ( 'listHeaderNav', $model->getHeaderNavList () );
		$this->assign ( 'systemConfig', $model->getLoginInfo () );
		$this->assign ( 'listTreeHtml', $str );
		$this->assign ( 'dataTree', $dataTree);
		$this->view ();
	}
}
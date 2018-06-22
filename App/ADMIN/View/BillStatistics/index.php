<form id="pagerForm" method="post" action="#rel#">
<!--<form id="pagerForm" method="post" action="<?=\Core\lib::getUrl('BillStatistics');?>">-->
    <input type="hidden" name="pageNum" value="<?=$data['pageNum']?>" />
    <input type="hidden" name="numPerPage" value="<?=$data['numPerPage']?>" />
    <input type="hidden" name="orderField" value="<?=$data['orderField']?>" />
    <input type="hidden" name="orderDirection" value="<?=$data['orderDirection']?>" />
</form>

<div class="pageHeader">
    <form rel="pagerForm" onsubmit="return navTabSearch(this);" action="<?=\Core\Lib::getUrl('BillStatistics')?>" method="post">
        <div class="searchBar">
            <ul class="searchContent">
                <li><label>应用名称：</label> <input type="text" name="app_name" value="<?=\Core\Lib::request('app_name')?>" /></li>                               
                <li><label>应用ID：</label> <input type="text" name="appid" value="<?=\Core\Lib::request('appid')?>" /></li>
				<li><label>姓名：</label> <input type="text" name="real_name" value="<?=\Core\Lib::request('real_name')?>" /></li>
				<li><label>电话：</label> <input type="text" name="mobile" value="<?=\Core\Lib::request('mobile')?>" /></li>
                <li><label>添加时间：</label>
                    <input type="text" class="date" size="10" name="start_date"
                           value="<?= \Core\Lib::request('start_date') ?>"/>
                    至
                    <input type="text" class="date" size="10" name="end_date"
                           value="<?= \Core\Lib::request('end_date') ?>"/>
                </li>
                
                <li>
                    <label>状态：</label>
                    <select name="status">
                        <option value="">全部</option>
                        <option value="1" <?php if(\Core\Lib::request('status')==1){echo 'selected';}?>>正常</option> 
                        <option value="-1" <?php if(\Core\Lib::request('status')==-1){echo 'selected';}?>>禁用</option>  
                    </select>
                </li>
                <li>
                    <div class="buttonActive">
                        <div class="buttonContent">
                            <button type="submit">查询</button>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </form>
</div>
<div class="pageContent">
    <div class="panelBar">
        <ul class="toolBar">
          
        </ul>
    </div>
    <table class="list" width="100%" layoutH="90">
        <thead>
        <tr>
            <th align="center" orderField="B.id" class="<?=($data['orderField']=='B.id')?$data['orderDirection']:'';?>">编号</th>
            <th align="center"style="width:180px;" orderField="B.app_name" class="<?=($data['orderField']=='B.app_name')?$data['orderDirection']:'';?>">应用名称</th>
            <th align="center"style="width:180px;" orderField="B.appid" class="<?=($data['orderField']=='B.appid')?$data['orderDirection']:'';?>">应用ID</th>
            <th align="center" orderField="B.real_name" class="<?=($data['orderField']=='B.real_name')?$data['orderDirection']:'';?>">姓名</th>
            <th align="center" orderField="B.mobile" class="<?=($data['orderField']=='B.mobile')?$data['orderDirection']:'';?>">电话</th>            
            <th align="center" orderField="B.card_no" class="<?=($data['orderField']=='B.card_no')?$data['orderDirection']:'';?>">卡号</th>
            <th align="center" orderField="B.amount" class="<?=($data['orderField']=='B.amount')?$data['orderDirection']:'';?>">金额</th>
            <th align="center" orderField="B.poundage" class="<?=($data['orderField']=='B.poundage')?$data['orderDirection']:'';?>">手续费</th>
            <th align="center" orderField="B.bill_type" class="<?=($data['orderField']=='B.bill_type')?$data['orderDirection']:'';?>">账单类型</th>
            <th align="center" orderField="B.card_type" class="<?=($data['orderField']=='B.card_type')?$data['orderDirection']:'';?>">卡类型</th>
            <th align="center" orderField="B.create_time" class="<?=($data['orderField']=='B.create_time')?$data['orderDirection']:'';?>">创建时间</th>
        </tr>
        </thead>
        <tbody>
        	<?php foreach ($data['list'] as $k=>$v){?>
			<tr target="id" rel="<?=$v['id']?>">
                	<td align="center"><?=$v['id']?></td>
                	<td align="center"><?=$v['app_name']?></td>
                	<td align="center"><?=$v['appid']?></td>
                	<td align="center"><?=$v['real_name']?></td>                	
               		<td align="center"><?=$v['mobile']?></td>
                	<td align="center"><?=$v['card_no']?></td>
                	<td align="center"><?=$v['amount']?></td>
                	<td align="center"><?=$v['poundage']?></td>                	
                	 <td align="center">
					<?php
					if($v['bill_type']==1){
						echo '还款';
					}elseif($v['bill_type']==2){
						echo '消费';
					}elseif($v['bill_type']==3){
						echo '提现';
					}elseif($v['bill_type']==4){
						echo '充值';
					}elseif($v['bill_type']==5){
						echo '卡验证';
					}elseif($v['bill_type']==6){
						echo '余额平帐';
					}elseif($v['bill_type']==7){
						echo '强制扣款';
					}elseif($v['bill_type']==8){
						echo '收款';
					}elseif($v['bill_type']==9){
						echo '红包';
					}elseif($v['bill_type']==10){
						echo '购买邀请码';
					}elseif($v['bill_type']==11){
						echo '套现';
					}
					?>
					</td>
                	<td align="center"><?=($v['card_type']==1)?'信用卡':'<font>储蓄卡'.'</font>'?></td>
                	<td align="center"><?= \Core\Lib::uDate('Y-m-d H:i:s x',$v['create_time']);?></td>
            </tr>
        <?php }?>
        </tbody>
    </table>
    <div class="panelBar">
        <div class="pages">
            <span>显示</span>
            <select class="combox" name="numPerPage" onchange="navTabPageBreak({numPerPage:this.value})">
                <option value="25" <?php if($data['numPerPage']=='25'){echo 'selected';}?>>25</option>
                <option value="50" <?php if($data['numPerPage']=='50'){echo 'selected';}?>>50</option>
                <option value="100" <?php if($data['numPerPage']=='100'){echo 'selected';}?>>100</option>
                <option value="200" <?php if($data['numPerPage']=='200'){echo 'selected';}?>>200</option>
            </select> <span>条，共<?php echo $data['totalCount']?>条</span>
        </div>
        <div class="pagination" targetType="navTab" totalCount="<?php echo $data['totalCount']?>" numPerPage="<?php echo $data['numPerPage']?>" pageNumShown="10" currentPage="<?php echo $data['pageNum']?>"></div>
    </div>
</div>
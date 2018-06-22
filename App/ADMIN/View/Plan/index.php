<form id="pagerForm" method="post" action="#rel#">
	<input type="hidden" name="pageNum" value="<?=$data['pageNum']?>" /> 
	<input type="hidden" name="numPerPage" value="<?=$data['numPerPage']?>" /> 
	<input type="hidden" name="orderField" value="<?=$data['orderField']?>" />
	<input type="hidden" name="orderDirection" value="<?=$data['orderDirection']?>" />
</form>
<div class="pageHeader">
	<form rel="pagerForm" onsubmit="return navTabSearch(this);" action="<?=\Core\Lib::getUrl('Plan','index?appid='.$appid)?>" method="post">
		<div class="searchBar">
			<ul class="searchContent">

				<li><label>姓名：</label> <input type="text" name="real_name" value="<?=\Core\Lib::request('real_name')?>" /></li>

				<li><label>卡号：</label> <input type="text" name="card_no" value="<?=\Core\Lib::request('card_no')?>" /></li>

				<li><label>起止时间：</label>
				<input type="text" class="date" size="10" name="start_time" value="<?=\Core\Lib::request('start_time')?>" />
				至 
				<input type="text" class="date" size="10" name="end_time" value="<?=\Core\Lib::request('end_time')?>" />
				</li>
				
				<li>
					<label>完成方式：</label>
                    <select name="finish_type">
                        <option value="">全部</option>
                        <?php
                        foreach($planFinishType as $key => $value) {
                            ?>
                            <option value="<?php echo $key; ?>" <?php if (\Core\Lib::request('finish_type') == $key) {
                                echo 'selected';
                            } ?>><?php echo $value; ?>
                            </option>
                            <?php
                        }
                        ?>
                    </select>
				</li>

				<li>
					<label>计划状态：</label>
                    <select name="status">
                        <option value="">全部</option>
                        <?php
                        foreach($planStatus as $key => $value) {
                            ?>
                            <option value="<?php echo $key; ?>" <?php if (\Core\Lib::request('status') == $key) {
                                echo 'selected';
                            } ?>><?php echo $value; ?>
                            </option>
                            <?php
                        }
                        ?>
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
           <li><a title="确定要强制完成吗？" target="ajaxTodo" href="<?=\Core\Lib::getUrl('Plan', 'finishPlan?appid='.$appid.'&id={id}');?>" class="delete"><span>强制完成</span></a></li>
           <li><a class="icon"  title="查看计划详情" rel="planDetail" href="<?=\Core\Lib::getUrl('PlanList', 'search?appid='.$appid.'&id={id}');?>" target="dialog" width="1200" height="780"><span>查看计划详情</span></a></li>
		</ul>
	</div>
	<table class="list" width="100%" layoutH="90">
		<thead>
			<tr>
				<th width="30" align="center"><input type="checkbox" group="ids" class="checkboxCtrl"></th>
				<th width="60" align="center" orderField="P.id" class="<?=($data['orderField']=='P.id')?$data['orderDirection']:'';?>">编号</th>
				<th  align="center" orderField="real_name"  class="<?=($data['orderField']=='real_name')?$data['orderDirection']:'';?>">用户姓名</th>
				<th  align="center" orderField="amount"  class="<?=($data['orderField']=='amount')?$data['orderDirection']:'';?>">金额</th>
				<th  align="center" orderField="card_no"  class="<?=($data['orderField']=='title')?$data['orderDirection']:'';?>">卡号</th>
				<th  align="center" orderField="start_time"  class="<?=($data['orderField']=='start_time')?$data['orderDirection']:'';?>">开始时间</th>
				<th  align="center" orderField="end_time"  class="<?=($data['orderField']=='end_time')?$data['orderDirection']:'';?>">结束时间</th>
				<th  align="center" orderField="duration"  class="<?=($data['orderField']=='duration')?$data['orderDirection']:'';?>">耗时天数</th>
				<th  align="center" orderField="poundage"  class="<?=($data['orderField']=='poundage')?$data['orderDirection']:'';?>">手续费</th>
				<th  align="center" orderField="finish_time"  class="<?=($data['orderField']=='finish_time')?$data['orderDirection']:'';?>">完成时间</th>
				<th  align="center" orderField="finish_type"  class="<?=($data['orderField']=='finish_type')?$data['orderDirection']:'';?>">完成方式</th>
				<th  align="center" orderField="P.status"  class="<?=($data['orderField']=='P.status')?$data['orderDirection']:'';?>">计划状态</th>
				<th  align="center" orderField="P.create_time"  class="<?=($data['orderField']=='P.create_time')?$data['orderDirection']:'';?>">添加时间</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($data['list'] as $k=>$v){?>
			<tr target="id" rel="<?=$v['id']?>">
				<td align="center"><input name="ids" value="<?=$v['id']?>" type="checkbox"></td>
				<td align="center"><?=$v['id']?></td>
				<td align="center"><?php echo $v['real_name'];?></td>
				<td align="center"><?=$v['amount']?></td>
				<td align="center">
                    <?php
                        echo  \Core\Lib::idCardHide(\Core\Lib::aesDecrypt($v['card_no']));
                    ?>
                </td>
				<td align="center"><?=date('Y-m-d',$v['start_time'])?></td>
				<td align="center">
					
					<?php
                    if(empty($v['end_time'])){
                        echo "未结束";
                    }else{
                    	echo date('Y-m-d H:i:s',$v['end_time']);
                    }
                    ?>
				</td>
				<td align="center"><?=$v['duration']?></td>
				<td align="center"><?=$v['poundage']?></td>
				<td align="center">
				<?if(!empty($v['finish_time'])){ echo date('Y-m-d H:i:s',$v['finish_time']); }?>
				</td>
				<td align="center"<?php if($v['finish_type'] == 2){ ?> style="color:red;"<?php } ?>><?=$planFinishType[$v['finish_type']]?></td>
				<td align="center"><?=$planStatus[$v['status']]?></td>
				<td align="center"><?=\Core\Lib::uDate('Y-m-d H:i:s x',$v['create_time'])?></td>
			</tr>
			<?php }?>
		</tbody>
	</table>
	<div class="panelBar">
		<div class="pages">
			<span>显示</span> <select class="combox" name="numPerPage"
				onchange="navTabPageBreak({numPerPage:this.value})">
				<option value="25" <?php if($data['numPerPage']=='25'){echo 'selected';}?>>25</option>
				<option value="50" <?php if($data['numPerPage']=='50'){echo 'selected';}?>>50</option>
				<option value="100" <?php if($data['numPerPage']=='100'){echo 'selected';}?>>100</option>
				<option value="200" <?php if($data['numPerPage']=='200'){echo 'selected';}?>>200</option>
			</select> <span>条，共<?php echo $data['totalCount']?>条</span>
		</div>
		<div class="pagination" targetType="navTab" totalCount="<?php echo $data['totalCount']?>" numPerPage="<?php echo $data['numPerPage']?>" pageNumShown="10" currentPage="<?php echo $data['pageNum']?>"></div>
	</div>
</div>

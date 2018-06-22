<form id="pagerForm" method="post" action="#rel#">
	<input type="hidden" name="pageNum" value="<?=$data['pageNum']?>" /> 
	<input type="hidden" name="numPerPage" value="<?=$data['numPerPage']?>" /> 
	<input type="hidden" name="orderField" value="<?=$data['orderField']?>" />
	<input type="hidden" name="orderDirection" value="<?=$data['orderDirection']?>" />
</form>
<div class="pageHeader">
	<form rel="pagerForm" onsubmit="return navTabPageBreak(this, 'dialog');" action="<?=\Core\Lib::getUrl('PlanList','search?appid='.$appid.'&id='.$id)?>" method="post">
		<div class="searchBar">
			<ul class="searchContent">
				<!--<li><label>姓名：</label> <input type="text" name="real_name" value="<?=\Core\Lib::request('real_name')?>" /></li>-->
                <!--<li>
                    <label>类型：</label>
                    <select name="plan_type">
                        <option value="">全部</option>
                        <?php
                        foreach($planlistType as $key => $value) {
                            ?>
                            <option value="<?php echo $key; ?>" <?php if (\Core\Lib::request('status') == $key) {
                                echo 'selected';
                            } ?>><?php echo $value; ?>
                            </option>
                            <?php
                        }
                        ?>
                    </select>
                </li>-->

				<li><label>起止时间：</label>
				<input type="text" class="date" size="10" name="start_time" value="<?=\Core\Lib::request('start_time')?>" />
				至 
				<input type="text" class="date" size="10" name="end_time" value="<?=\Core\Lib::request('end_time')?>" />
				</li>

				<li>
					<label>计划状态：</label>
					<select name="status">
						<option value="">全部</option>
                        <?php
                            foreach($planlistStatus as $key => $value) {
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
            <li><a title="确实要移动吗?" target="selectedTodo" rel="ids" postType="string" href="<?=\Core\Lib::getUrl('PlanList','redisAll');?>" class="icon"><span>移动到redis</span></a></li>
		</ul>
	</div>
	<table class="list" width="100%" layoutH="90">
		<thead>
			<tr>
				<th width="30" align="center"><input type="checkbox" group="ids" class="checkboxCtrl"></th>
				<th width="60" align="center">编号</th>
                <th width="60" align="center">计划ID</th>
				<th  align="center">用户姓名</th>
				<th  align="center">金额</th>
				<th  align="center">类型</th>
				<th  align="center">开始时间</th>
				<th  align="center">结束时间</th>
				<th  align="center" orderField="redis"  class="">redisInfo</th>
				<th  align="center">订单号</th>
				<th  align="center">计划状态</th>
				<th  align="center">添加时间</th>
				<th  align="center" orderField="doPlan"  class="">操作</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($data['list'] as $k=>$v){?>
			<tr target="id" rel="<?=$v['id']?>">
				<td align="center"><input name="ids" value="<?=$v['id']?>" type="checkbox"></td>
				<td align="center"><?=$v['id']?></td>
                <td align="center"><?=$v['plan_id']?></td>
				<td align="center"><?=$v['real_name']?></td>
				<td align="center"><?=$v['amount']?></td>
				<td align="center"<?php if($v['plan_type'] == 1){ ?> style="color:red;"<?php } ?>><?=$planlistType[$v['plan_type']]?></td>
				<td align="center"><?=date('Y-m-d H:i:s',$v['start_time'])?></td>
				<td align="center"><?php
                    if(empty($v['end_time'])){
                        echo "未结束";
                    }else{
                    	echo date('Y-m-d H:i:s',$v['end_time']);
                    }
                    ?></td>
				<td align="center"><?=$v['redis']?></td>
				<td align="center"><?=$v['order_sn']?></td>
				<td align="center"><?=$planlistStatus[$v['status']]?></td>
				<td align="center"><?=\Core\Lib::uDate('Y-m-d H:i:s x',$v['create_time'])?></td>
				<td align="center" ><div class="button" style="float:none;display:inline-block;overflow:hidden;"> <div style="display:none" class="inputs" ><?=$v['id']?></div> <div onClick="delRedis(this)" class="buttonContent"><button  type="button">删除redis</button></div></div> </td>
			</tr>
			<?php }?>
		</tbody>
	</table>
	<script type="text/javascript">
		function delRedis(obj){
			var planListId;
			var appid;
			planListId=$(obj).siblings(".inputs").text();
			appid="<?php echo $appid; ?>";
			$.ajax({  
                type:'post',  
                url:"<?=\Core\Lib::getUrl('PlanList', 'delRedis');?>",  
                dataType:'json',  
                data:{appid:appid,planListId:planListId},  
                success:function(json){  
                    if(json ==1){  
                        alertMsg.correct('删除redis成功')
                    }else{  
                        alertMsg.error('操作失败') 
                    }
                    navTabPageBreak();  
                }  
            }); 
		}
	</script>
	<!--<div class="panelBar">
		<div class="pages">
			<span>显示</span> <select class="combox" name="numPerPage"
				onchange="dwzPageBreak({targetType:'dialog', numPerPage:this.value})">
				<option value="25" <?php if($data['numPerPage']=='25'){echo 'selected';}?>>25</option>
				<option value="50" <?php if($data['numPerPage']=='50'){echo 'selected';}?>>50</option>
				<option value="100" <?php if($data['numPerPage']=='100'){echo 'selected';}?>>100</option>
				<option value="200" <?php if($data['numPerPage']=='200'){echo 'selected';}?>>200</option>
			</select> <span>条，共<?php echo $data['totalCount']?>条</span>
		</div>
		<div class="pagination" targetType="dialog" totalCount="<?php echo $data['totalCount']?>" numPerPage="<?php echo $data['numPerPage']?>" pageNumShown="10" currentPage="<?php echo $data['pageNum']?>"></div>
	</div>-->
</div>

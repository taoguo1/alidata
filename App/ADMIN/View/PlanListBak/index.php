<form id="pagerForm" method="post" action="#rel#">
	<input type="hidden" name="pageNum" value="<?=$data['pageNum']?>" /> 
	<input type="hidden" name="numPerPage" value="<?=$data['numPerPage']?>" /> 
	<input type="hidden" name="orderField" value="<?=$data['orderField']?>" />
	<input type="hidden" name="orderDirection" value="<?=$data['orderDirection']?>" />
</form>
<div class="pageHeader">
	<form rel="pagerForm" onsubmit="return navTabSearch(this);" action="<?=\Core\Lib::getUrl('PlanList','index?appid='.$appid)?>" method="post">
		<div class="searchBar">
			<ul class="searchContent">
				<li><label>姓名：</label> <input type="text" name="real_name" value="<?=\Core\Lib::request('real_name')?>" /></li>
                <li>
                    <label>类型：</label>
                    <select name="plan_type">
                        <option value="">全部</option>
                        <?php
                        foreach($planlistType as $key => $value) {
                            ?>
                            <option value="<?php echo $key; ?>" <?php if (\Core\Lib::request('plan_type') == $key) {
                                echo 'selected';
                            } ?>><?php echo $value; ?>
                            </option>
                            <?php
                        }
                        ?>
                    </select>
                </li>
				<li><label>订单号：</label> <input type="text" name="order_sn" value="<?=\Core\Lib::request('order_sn')?>" /></li>
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
            <!--
			<li><a class="add" rel="PlanAdd" title="添加计划" href="<?=\Core\Lib::getUrl('Plan', 'add');?>" target="dialog" width="500" height="400"><span>添加</span></a></li>
			-->
			<li><a title="确定要删除吗？" target="ajaxTodo" href="<?=\Core\Lib::getUrl('PlanList', 'del','id={id}');?>" class="delete"><span>删除</span></a></li>
			<li><a title="确实要删除这些记录吗?" target="selectedTodo" rel="ids" postType="string" href="<?=\Core\Lib::getUrl('PlanList','delAll');?>" class="delete"><span>批量删除</span></a></li>
			<!--
            <li><a class="edit"  title="编辑计划" rel="articleEdit" href="<?=\Core\Lib::getUrl('Plan', 'edit','id={id}');?>" target="dialog"><span>编辑</span></a></li>
            -->
            <li><a title="确实要移动吗?" target="selectedTodo" rel="ids" postType="string" href="<?=\Core\Lib::getUrl('PlanList','redisAll');?>" class="icon"><span>移动到redis</span></a></li>
		</ul>
	</div>
	<table class="list" width="100%" layoutH="90">
		<thead>
			<tr>
				<th width="30" align="center"><input type="checkbox" group="ids" class="checkboxCtrl"></th>
				<th width="60" align="center" orderField="P.id" class="<?=($data['orderField']=='P.id')?$data['orderDirection']:'';?>">编号</th>
                <th width="60" align="center" orderField="P.plan_id" class="<?=($data['orderField']=='P.plan_id')?$data['orderDirection']:'';?>">计划ID</th>
				<th  align="center" orderField="U.real_name"  class="<?=($data['orderField']=='U.real_name')?$data['orderDirection']:'';?>">用户姓名</th>
				<th  align="center" orderField="amount"  class="<?=($data['orderField']=='amount')?$data['orderDirection']:'';?>">金额</th>
				<th  align="center" orderField="plan_type"  class="<?=($data['orderField']=='plan_type')?$data['orderDirection']:'';?>">类型</th>
				<th  align="center" orderField="start_time"  class="<?=($data['orderField']=='start_time')?$data['orderDirection']:'';?>">开始时间</th>
				<th  align="center" orderField="end_time"  class="<?=($data['orderField']=='end_time')?$data['orderDirection']:'';?>">结束时间</th>
				
				<th  align="center" orderField="redis"  class="">redisInfo</th>
				
				<th  align="center" orderField="order_sn"  class="<?=($data['orderField']=='order_sn')?$data['orderDirection']:'';?>">订单号</th>
				<th  align="center" orderField="P.status"  class="<?=($data['orderField']=='P.status')?$data['orderDirection']:'';?>">计划状态</th>
				<th  align="center" orderField="P.create_time"  class="<?=($data['orderField']=='P.create_time')?$data['orderDirection']:'';?>">添加时间</th>
				<th  align="center" orderField="doPlan"  class="">操作</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($data['list'] as $k=>$v){?>
			<tr target="id" rel="<?=$v['id']?>">
				<td align="center"><input name="ids"   value="<?=$v['id']?>" type="checkbox"></td>
				<td align="center"><?=$v['id']?></td>
                <td align="center"><?=$v['plan_id']?></td>
				<td align="center"><?=\Core\Lib::starReplace($v['real_name'])?></td>
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
				
			<!--<?php if($v['redis']==1){ ?>
					
					<td align="center" onClick="delRedis(this)"><button class="button">删除redis</button> </td>
				<?php }else{ ?>
					<td align="center"><button class="button">删除成功</button> </td>;
				<?php }?>-->
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

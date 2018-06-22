<form id="pagerForm" method="post" action="#rel#">
	<input type="hidden" name="pageNum" value="<?=$data['pageNum']?>" /> 
	<input type="hidden" name="numPerPage" value="<?=$data['numPerPage']?>" /> 
	<input type="hidden" name="orderField" value="<?=$data['orderField']?>" />
	<input type="hidden" name="orderDirection" value="<?=$data['orderDirection']?>" />
</form>
<div class="pageHeader">
	<form rel="pagerForm" onsubmit="return navTabSearch(this);" action="<?php echo \Core\Lib::getUrl('admin')?>" method="post">
		<div class="searchBar">
			<ul class="searchContent">
				<li><label>帐号：</label> <input type="text" name="account" value="<?php echo \Core\Lib::post('account')?>" />
				<li><label>姓名：</label> <input type="text" name="real_name" value="<?php echo \Core\Lib::post('real_name')?>" />
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
			<li><a class="add" rel="adminAdd" title="添加管理员" href="<?php echo \Core\Lib::getUrl('admin', 'add');?>" target="dialog" width="500" height="400"><span>添加</span></a></li>
			<li><a title="确定要删除吗？" target="ajaxTodo" href="<?php echo \Core\Lib::getUrl('admin', 'del','id={id}');?>" class="delete"><span>删除</span></a></li>
			<li><a class="edit"  title="编辑管理员" rel="adminEdit" href="<?php echo \Core\Lib::getUrl('admin', 'edit','id={id}');?>" target="dialog" width="500" height="400"><span>编辑</span></a></li>
			<li><a class="add" title="确定要启用吗？" target="ajaxTodo" href="<?php echo \Core\Lib::getUrl('admin', 'enable','id={id}');?>"><span>启用</span></a></li>
			<li><a class="delete" title="确定要禁用吗？" target="ajaxTodo" href="<?php echo \Core\Lib::getUrl('admin', 'disable','id={id}');?>"><span>禁用</span></a></li>
		</ul>
	</div>
	<table class="list" width="100%" layoutH="90">
		<thead>
			<tr>
				<th width="60" align="center" orderField="id" class="<?=($data['orderField']=='id')?$data['orderDirection']:'';?>">编号</th>
				<th width="200" orderField="account" align="center" class="<?=($data['orderField']=='account')?$data['orderDirection']:'';?>">登录帐号</th>
				<th align="center" width="100">所属角色</th>
				<th width="80" orderField="real_name" align="center" class="<?=($data['orderField']=='real_name')?$data['orderDirection']:'';?>">真实姓名</th>
				<th width="130" orderField="tel" align="center" class="<?=($data['orderField']=='tel')?$data['orderDirection']:'';?>">电话</th>
				<th width="60" align="center" orderField="status" class="<?=($data['orderField']=='status')?$data['orderDirection']:'';?>">状态</th>
				<th width="120" align="center" orderField="last_login_time" class="<?=($data['orderField']=='last_login_time')?$data['orderDirection']:'';?>">最后登录时间</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($data['list'] as $k=>$v){?>
			<tr target="id" rel="<?=$v['id']?>">
				<td align="center"><?=$v['id']?></td>
				<td align="center"><?=$v['account']?></td>
				<td align="center"><?=$v['name']?></td>
				<td align="center"><?=$v['real_name']?></td>
				<td align="center"><?=$v['tel']?></td>
				<td align="center"><?=($v['status']==-1)?'<font style="color:red">禁用</font>':'正常'?></td>
                <td align="center"><?= \Core\Lib::uDate('Y-m-d H:i:s x',$v['last_login_time']);?></td>
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

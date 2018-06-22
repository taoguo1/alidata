<form id="pagerForm" method="post" action="<?=\Core\lib::getUrl('Role');?>">
	<input type="hidden" name="pageNum" value="<?=$data['pageNum']?>" /> 
	<input type="hidden" name="numPerPage" value="<?=$data['numPerPage']?>" /> 
	<input type="hidden" name="orderField" value="<?=$data['orderField']?>" />
	<input type="hidden" name="orderDirection" value="<?=$data['orderDirection']?>" />
</form>

<div class="pageContent">
	<div class="panelBar">
		<ul class="toolBar">
			<li><a class="add" rel="RoleAdd" title="添加角色" href="<?=\Core\lib::getUrl('role', 'add');?>" target="navTab"><span>添加</span></a></li>
			<li><a title="确定要删除吗？" target="ajaxTodo" href="<?php echo \Core\lib::getUrl('role', 'del','id={id}');?>" class="delete"><span>删除</span></a></li>
			<li><a class="edit"  title="编辑角色" rel="RoleEdit" href="<?=\Core\lib::getUrl('role', 'edit','id={id}');?>" target="navTab"><span>编辑</span></a></li>
		</ul>
	</div>
	<table class="list" width="100%" layoutH="53">
		<thead>
			<tr>
				<th width="60" align="center" orderField="id" class="<?=($data['orderField']=='id')?$data['orderDirection']:'';?>">编号</th>
				<th width="100" orderField="name" align="center" class="<?=($data['orderField']=='name')?$data['orderDirection']:'';?>">登录帐号</th>
				<th align="center">权限</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($data['list'] as $k=>$v){?>
			<tr target="id" rel="<?=$v['id']?>" style="border-bottom:solid  #ededed 1px">
				<td align="center"><?=$v['id']?></td>
				<td align="center"><div style="font-size: 16px;line-height:30px;margin:0 auto;width:auto;overflow:hidden;word-break:break-all;"><?=$v['name']?></div></td>
				<td align="left"><?=$v['tree_name']?></td>
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

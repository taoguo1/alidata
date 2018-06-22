<form id="pagerForm" method="post"
	action="<?php echo \Core\Lib::getUrl('tree','index');?>">
	<input type="hidden" name="pageNum" value="1" /> <input type="hidden"
		name="numPerPage" value="" /> <input type="hidden" name="orderField"
		value="" /> <input type="hidden" name="orderDirection" value="" />
</form>

<div class="pageContent">
	<div class="panelBar">
		<ul class="toolBar">
			<li><a class="add" rel="treeAdd" href="<?php echo \Core\Lib::getUrl('tree', 'add');?>" title="添加菜单" target="dialog" width="500" height="450"><span>添加</span></a></li>
			<li><a title="确实要删除这些记录吗?" target="ajaxTodo" href="<?php echo \Core\Lib::getUrl('tree', 'del','id={id}');?>" class="delete"><span>删除</span></a></li>
			<li><a rel="treeEdit" class="edit" title="编辑菜单" href="<?php echo \Core\Lib::getUrl('tree', 'edit','id={id}');?>" target="dialog" width="500" height="450"><span>编辑</span></a></li>
			<li><a class="add" title="确定要启用吗？" target="ajaxTodo" href="<?=\Core\Lib::getUrl('tree', 'enable','id={id}');?>"><span>启用</span></a></li>
			<li><a class="delete" title="确定要禁用吗？" target="ajaxTodo" href="<?=\Core\Lib::getUrl('tree', 'disable','id={id}');?>"><span>禁用</span></a></li>
		</ul>
	</div>
	<table class="list" width="100%" layoutH="30">
		<thead>
			<tr>

				<th width="50" align="center">编号</th>
				<th>菜单名称</th>
				<th width="200" align="center">别名</th>

				<th width="200" align="center">控制器</th>
				<th width="200" align="center">方法</th>
				<th width="200" align="center">参数</th>
				
				<th width="200" align="center">打开方式</th>

				<th width="70" align="center">图标</th>
				<th width="70" align="center">顺序</th>
				<th width="70" align="center">状态</th>
				
			</tr>
		</thead>
		<tbody>
			<?php echo $listStr;?>
		</tbody>
	</table>

</div>

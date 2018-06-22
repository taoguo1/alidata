<form id="pagerForm" method="post" action="<?php echo \Core\Lib::getUrl('HeaderNav')?>">
	<input type="hidden" name="pageNum" value="<?=$data['pageNum']?>" /> 
	<input type="hidden" name="numPerPage" value="<?=$data['numPerPage']?>" /> 
	<input type="hidden" name="orderField" value="<?=$data['orderField']?>" />
	<input type="hidden" name="orderDirection" value="<?=$data['orderDirection']?>" />
</form>

<div class="pageContent">
	<div class="panelBar">
		<ul class="toolBar">
			<li><a class="add" rel="HeaderNavAdd" title="添加顶部导航" href="<?php echo \Core\Lib::getUrl('HeaderNav', 'add');?>" target="dialog" width="500" height="400"><span>添加</span></a></li>
			<li><a title="确定要删除吗？" target="ajaxTodo" href="<?php echo \Core\Lib::getUrl('HeaderNav', 'del','id={id}');?>" class="delete"><span>删除</span></a></li>
			<li><a class="edit"  title="编辑顶部导航" rel="HeaderNavEdit" href="<?php echo \Core\Lib::getUrl('HeaderNav', 'edit','id={id}');?>" target="dialog" width="500" height="400"><span>编辑</span></a></li>
			</ul>
	</div>
	<table class="list" width="100%" layoutH="53">
		<thead>
			<tr>
				<th width="60" align="center" orderField="id" class="<?=($data['orderField']=='id')?$data['orderDirection']:'';?>">编号</th>
				<th orderField="name"  align="center" class="<?=($data['orderField']=='name')?$data['orderDirection']:'';?>">菜单名称</th>
				<th  align="center">完整URL</th>
				
				<th width="200" orderField="controller" align="center" class="<?=($data['orderField']=='controller')?$data['orderDirection']:'';?>">控制器</th>
				<th width="80" orderField="action" align="center" class="<?=($data['orderField']=='action')?$data['orderDirection']:'';?>">方法</th>
				<th width="130" orderField="pars" align="center" class="<?=($data['orderField']=='pars')?$data['orderDirection']:'';?>">参数</th>
				<th width="60" align="center" orderField="target" class="<?=($data['orderField']=='target')?$data['orderDirection']:'';?>">打开方式</th>
				<th width="120" align="center" orderField="sort" class="<?=($data['orderField']=='sort')?$data['orderDirection']:'';?>">排序</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($data['list'] as $k=>$v){?>
			<tr target="id" rel="<?=$v['id']?>">
				<td align="center"><?=$v['id']?></td>
				<td align="center"><?=$v['name']?></td>
				<td align="center">
				
				<?php echo \Core\Lib::getUrl($v['controller'],$v['action'],$v['pars'])?>
				</td>
				<td align="center"><?=$v['controller']?></td>
				<td align="center"><?=$v['action']?></td>
				<td align="center"><?=$v['pars']?></td>
				<td align="center"><?=$v['target']?></td>
				<td align="center"><?=$v['sort']?></td>
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

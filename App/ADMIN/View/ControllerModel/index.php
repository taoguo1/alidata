<div class="pageContent">
	<!-- <div class="panelBar">
		<ul class="toolBar">
			<li><a class="edit"  title="编辑管理员" rel="adminEdit" href="<?php echo \Core\Lib::getUrl('admin', 'edit','id={id}');?>" target="dialog" width="500" height="400"><span>编辑</span></a></li>
			<li><a class="add" title="确定要启用吗？" target="ajaxTodo" href="<?php echo \Core\Lib::getUrl('admin', 'enable','id={id}');?>"><span>启用</span></a></li>
			<li><a class="delete" title="确定要禁用吗？" target="ajaxTodo" href="<?php echo \Core\Lib::getUrl('admin', 'disable','id={id}');?>"><span>禁用</span></a></li>
		</ul>
	</div> -->
	<table class="list" width="100%" layoutH="5">
		<thead>
			<tr>
				<th width="40" align="center">编号</th>
				<th  align="center" width="300">控制器/方法</th>
				<th align="center" width="200">名称</th>
				<th align="center">注释</th>
				
			</tr>
		</thead>
		<tbody>
		<?php 
			$no = 1;
			foreach ($data as $k=>$v){?>
			<tr target="id" rel="">
				<td align="center" height="30" style="font-weight: bold;padding-left:10px;color:red;">No.<?php echo $no;?> </td>
				<td align="left" style="font-weight: bold;padding-left:10px;color:red;"><?=$v['controller']?> </td>
				<td align="center"><?php echo \Core\Lib::getClassName($v['controller'].'Controller');?></td>
				<td align="left"><?php echo \Core\Lib::getClassNotes($v['controller'].'Controller');?></td>
			</tr>
			<?php 
			$nom = 1;
			foreach ($v['methods'] as $km=>$vm){?>
			<tr target="id" rel="">
				<td align="center" height="30"> <?php echo $no;?>-<?php echo $nom;?></td>
				<td align="left" style="padding-left:10px;">|--<?=$vm?> </td>
				<td align="center"><?php echo \Core\Lib::getFunctionName($v['controller'].'Controller',$vm);?></td>
				<td align="left"><?php echo \Core\Lib::getFunctionNotes($v['controller'].'Controller',$vm);?></td>
			</tr>
			<?php 
				$nom++;
				}
			$no++;
			}?>
		</tbody>
	</table>
	
</div>

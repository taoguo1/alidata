<div class="pageContent">
	<form method="post"
		action="<?php echo \Core\Lib::getUrl('tree','edit','id='.$list['id'].'&act=edit');?>"
		class="pageForm required-validate"
		onsubmit="return validateCallback(this, dialogAjaxDone);">

		<div class="pageFormContent" layoutH="56">
			<div class="unit">
				<dl>
					<dt>上级菜单：</dt>
					<dd>
						<select name="pid" class="required combox">
							<option value="0">顶级菜单</option>
							<?php echo $listOptionStr;?>
						</select>
					</dd>
				</dl>
			</div>
			<div class="unit">
				<dl>
					<dt>菜单名称：</dt>
					<dd>
						<input type="text" name="name" class="required" value="<?php echo $list['name'];?>" />
					</dd>
				</dl>
			</div>

			
			<div class="unit">
				<dl>
					<dt>控制器：</dt>
					<dd>
					<select class="required" id="edit_controller" name="controller" onchange="getClsMethods(this.value)">
					<option value="#">#</option>
					<?php foreach ($controllerList as $v){?>
					<option <?php if($list['controller']==$v){echo 'selected';}?> value="<?php echo $v;?>"><?php echo $v;?></option>
					<?php }?>
					</select>
					</dd>
				</dl>
			</div>
			
			<div class="unit">
				<dl>
					<dt>方法：</dt>
					<dd>
						<select class="required"   id="edit_action"   name="action">
						<option value="#">#</option>
					</select>
					</dd>
				</dl>
			</div>
			<div class="unit">
				<dl>
					<dt>参数：</dt>
					<dd>
						<input type="text" name="pars" value="<?php echo $list['pars'];?>" />
					</dd>
				</dl>
			</div>
			
			<div class="unit">
				<dl>
					<dt>别名：</dt>
					<dd>
						<input type="text" name="alias" id="edit_alias" value="<?php echo $list['alias'];?>" />
					</dd>
				</dl>
			</div>
			<div class="unit">
				<dl>
					<dt>打开方式：</dt>
					<dd>
						<select name="target" class="required combox">
							<option value="">请选择打开方式</option>
							<option value="navTab" <?php if($list['target']=='navTab'){echo 'selected';}?>>navTab(页签)</option>
							<option value="dialog" <?php if($list['target']=='dialog'){echo 'selected';}?>>dialog(窗口)</option>
						</select>
					</dd>
				</dl>
			</div>
			<div class="unit">
				<table cellpadding="0" cellspacing="0">
					<tr>
						<td>
							<label>图标：</label> 
							<input type="text"  id="icon" name="icon" onclick="upload_file('<?php echo \Core\Lib::getUrl('upload','index','id=icon&path=treeIcon')?>')" value="<?php echo $list['icon'];?>"/> 
							<span style="line-height: 24px; padding-left: 10px;" onclick="$('#icon').val('');$('#icon_img').attr('src','')">清除</span></td>
						<td width="60" align="center">
						<img src="<?php echo APP_SITE_PATH.$list['icon'];?>" id="icon_img" onerror="javascript:this.src='<?=APP_ADMIN_STATIC?>image/no_pic.png';" width="30" /></td>
					</tr>
				</table>
			</div>
			<div class="unit">
				<dl>
					<dt>顺序：</dt>
					<dd>
						<input type="text" name="sort" class="required"
							value="<?php echo $list['sort'];?>" />
					</dd>
				</dl>
			</div>


		</div>
		<div class="formBar">
			<ul>
				<li><div class="button">
						<div class="buttonContent">
							<button type="submit">保存</button>
						</div>
					</div></li>
				<li>
					<div class="button">
						<div class="buttonContent">
							<button type="button" class="close">取消</button>
						</div>
					</div>
				</li>
			</ul>
		</div>
	</form>
</div>
<script>
	getClsMethods('<?php echo $list['controller'];?>');
	function getClsMethods(controller)
	{
		if(controller!='#')
		{
			$('#edit_alias').val(controller);
		}
		callAjax('<?php echo \Core\Lib::getUrl('tree','edit','id=0&act=getClsMethods')?>',{controller:controller},function(ret){
			var str = '';
			for(var i=0;i<ret.length;i++)
			{
				str+='<option value="'+ret[i]+'">'+ret[i]+'</option>';
			}
			$('#edit_action').html(str);
		});
	}
</script>

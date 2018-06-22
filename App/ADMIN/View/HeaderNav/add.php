<div class="pageContent">
	<form method="post" action="<?php echo \Core\Lib::getUrl('HeaderNav','add','act=add');?>" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);">
		<div class="pageFormContent" layoutH="56">
			
			<div class="unit">
				<dl>
					<dt>导航名称：</dt>
					<dd>
						<input type="text" name="name" class="required" value="" />
					</dd>
				</dl>
			</div>

			

			<div class="unit">
				<dl>
					<dt>控制器：</dt>
					<dd>
					<select class="required" name="controller" onchange="getHeaderNavClsMethods(this.value)">
					<option value="#">#</option>
					<?php foreach ($controllerList as $v){?>
					<option value="<?php echo $v;?>"><?php echo $v;?></option>
					<?php }?>
					</select>
					</dd>
				</dl>
			</div>
			
			<div class="unit">
				<dl>
					<dt>方法：</dt>
					<dd>
						<select class="required" style="width:100px;" id="add_header_nav_action"   name="action">
						<option value="#">#</option>
					</select>
					</dd>
				</dl>
			</div>
			<div class="unit">
				<dl>
					<dt>参数：</dt>
					<dd>
						<input type="text" name="pars"  value="" />
					</dd>
				</dl>
			</div>
			
			
			<div class="unit">
				<dl>
					<dt>打开方式：</dt>
					<dd>
						<select name="target" class="required">
							<option value="">请选择打开方式</option>
							<option value="navTab">navTab(页签)</option>
							<option value="dialog">dialog(窗口)</option>
						</select>
					</dd>
				</dl>
			</div>
			
			<div class="unit">
				<dl>
					<dt>顺序：</dt>
					<dd>
						<input type="text" name="sort" class="required" value="255" />
					</dd>
				</dl>
			</div>
		</div>
		<div class="formBar">
			<ul>
				<li>
					<div class="button">
						<div class="buttonContent">
							<button type="submit">保存</button>
						</div>
					</div>
				</li>
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
	function getHeaderNavClsMethods(controller)
	{
		callAjax('<?php echo \Core\Lib::getUrl('HeaderNav','add','act=getClsMethods')?>',{controller:controller},function(ret){
			var str = '';
			for(var i=0;i<ret.length;i++)
			{
				str+='<option value="'+ret[i]+'">'+ret[i]+'</option>';
			}
			$('#add_header_nav_action').html(str);
		});
	}
</script>
<div class="pageContent">
	<form method="post" action="<?php echo \Core\Lib::getUrl('admin','edit','id='.$list['id']);?>" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);">
		<input type="hidden" name="act" value="edit">
		<div class="pageFormContent" layoutH="56">
			<div class="unit">
				<dl>
					<dt>所属角色：</dt>
					<dd>
						<?php foreach ($roleList as $v){?>
						<label style="width: auto">
						<input type="checkbox" <?php if(in_array($v['id'],explode(',', $list['role_id']))){echo 'checked';}?> name="role_id[]"  value="<?=$v['id'];?>"><?=$v['name'];?>
						</label>
						<?php }?>
					</dd>
				</dl>
			</div>
			<div class="unit">
				<dl>
					<dt>登录帐号：</dt>
					<dd>
						<input type="text" name="account"  disabled value="<?php echo $list['account'];?>" />
					</dd>
				</dl>
			</div>
			<div class="unit">
				<dl>
					<dt>登录密码：</dt>
					<dd>
						<input type="text"  name="password"  class="alphanumeric" minlength="6" maxlength="20" value="" />
					</dd>
				</dl>
			</div>
			<div class="unit">
				<dl>
					<dt>手机号码：</dt>
					<dd>
						<input type="text" name="tel" class="phone" value="<?php echo $list['tel'];?>" />
					</dd>
				</dl>
			</div>
			<div class="unit">
				<dl>
					<dt>真实姓名：</dt>
					<dd>
						<input type="text" name="real_name"  value="<?php echo $list['real_name'];?>" />
					</dd>
				</dl>
			</div>
			<div class="unit">
				<dl>
					<dt>状态：</dt>
					<dd>
						<select name="status" class="required combox">
							<option value="0" <?php if($list['status']==0){echo 'selected';}?>>正常</option>
							<option value="-1" <?php if($list['status']==-1){echo 'selected';}?>>禁用</option>
						</select>
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

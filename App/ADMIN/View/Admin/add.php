<div class="pageContent">
	<form method="post" action="<?php echo \Core\Lib::getUrl('admin','add');?>" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);">
		<input type="hidden" name="act" value="add">
		<div class="pageFormContent" layoutH="56">
			<div class="unit">
				<dl>
					<dt>所属角色：</dt>
					<dd>
						<?php foreach ($roleList as $v){?>
						<label style="width: auto"><input type="checkbox"  name="role_id[]"  value="<?=$v['id'];?>"><?=$v['name'];?></label>
						<?php }?>
					</dd>
				</dl>
			</div>
			<div class="unit">
				<dl>
					<dt>登录帐号：</dt>
					<dd>
						<input type="text" name="account" class="required" value="" />
					</dd>
				</dl>
			</div>
			<div class="unit">
				<dl>
					<dt>登录密码：</dt>
					<dd>
						<input type="text"  name="password"  class="required alphanumeric" minlength="6" maxlength="20" value="" />
					</dd>
				</dl>
			</div>
			<div class="unit">
				<dl>
					<dt>手机号码：</dt>
					<dd>
						<input type="text" name="tel" class="phone" value="" />
					</dd>
				</dl>
			</div>
			<div class="unit">
				<dl>
					<dt>真实姓名：</dt>
					<dd>
						<input type="text" name="real_name"  value="" />
					</dd>
				</dl>
			</div>
			<div class="unit">
				<dl>
					<dt>状态：</dt>
					<dd>
						<select name="status" class="required combox">
							<option value="0">正常</option>
							<option value="-1">禁用</option>
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

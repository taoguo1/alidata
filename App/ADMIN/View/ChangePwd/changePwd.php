<div class="pageContent">
	<form method="post" action="<?php echo Core\Lib::getUrl('changePwd','changePwd',"act=upd");?>" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);">
		<input type="hidden" name="act" value="add">
		<div class="pageFormContent" layoutH="56">
			
			<div class="unit">
				<dl>
					<dt>旧密码：</dt>
					<dd>
						<input type="password" name="oldPwd" class="required" value="" AUTOCOMPLETE="off"/>
					</dd>
				</dl>
			</div>
			<div class="unit">
				<dl>
					<dt>新密码：</dt>
					<dd>
						<input type="password" id="newPwd"  name="newPwd"  class="required alphanumeric" minlength="6" maxlength="20" value="" />
					</dd>
				</dl>
			</div>
			
			<div class="unit">
				<dl>
					<dt>确认密码：</dt>
					<dd>
						<input type="password"  name="newPwd1"  class="required" equalto="#newPwd" value="" />
					</dd>
				</dl>
			</div>
			
			
		</div>
		<div class="formBar">
			<ul>
				<li>
					<div class="button">
						<div class="buttonContent">
							<button type="submit">修改</button>
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

<div class="pageContent">
	<form method="post" action="<?php echo \Core\Lib::getUrl('login','login');?>" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);">
		<div class="pageFormContent" layoutH="56">
			<input type="hidden" name="act" value="timeOut" />
			
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
						<input type="text" onfocus="$(this).attr('type','password')"  name="password"  class="required"  value="" />
					</dd>
				</dl>
			</div>
			
		</div>
		<div class="formBar">
			<ul>
				<li>
					<div class="button">
						<div class="buttonContent">
							<button type="submit">登录</button>
						</div>
					</div>
				</li>
				
			</ul>
		</div>
	</form>
</div>

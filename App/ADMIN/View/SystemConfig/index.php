<div class="pageContent">
	<form method="post"
		action="<?php echo \Core\Lib::getUrl('SystemConfig','index');?>"
		class="pageForm required-validate"
		onsubmit="return validateCallback(this, dialogAjaxDone);">
		<input type="hidden" name="act" value="edit">

		<div class="pageFormContent" layoutH="56">
			<div class="tabs" currentIndex="0" eventType="click">
				<div class="tabsHeader">
					<div class="tabsHeaderContent">
						<ul>
							<li><a href="javascript:;"><span>基本设置</span></a></li>
						</ul>
					</div>
				</div>
				<div class="tabsContent">
					<div>
						<div class="unit">
							<dl>
								<dt>登录页面标题：</dt>
								<dd>
									<input type="text" size="50" name="login_title" class="required" value="<?php echo $list['login_title'];?>" />
								</dd>
							</dl>
						</div>
						<div class="divider"></div>
						<div class="unit">
							<dl>
								<dt>登录页面名称：</dt>
								<dd>
									<input type="text" size="50" name="login_header_title" class="required" value="<?php echo $list['login_header_title'];?>" />
								</dd>
							</dl>
						</div>
						<div class="divider"></div>
						<div class="unit">
							<dl>
								<dt>登录页面版权：</dt>
								<dd>
									<input type="text" size="50" name="login_footer_copyright" class="required" value="<?php echo $list['login_footer_copyright'];?>" />
								</dd>
							</dl>
						</div>
						<div class="divider"></div>
						<div class="unit">
							<dl>
								<dt>系统界面标题：</dt>
								<dd>
									<input type="text" size="50" name="system_title" class="required" value="<?php echo $list['system_title'];?>" />
								</dd>
							</dl>
						</div>
						<div class="divider"></div>
						<div class="unit">
							<dl>
								<dt>系统页面名称：</dt>
								<dd>
									<input type="text" size="50" name="system_name" class="required" value="<?php echo $list['system_name'];?>" />
								</dd>
							</dl>
						</div>
						<div class="divider"></div>
						<div class="unit">
							<dl>
								<dt>系统页面版权：</dt>
								<dd>
									<input type="text" size="50" name="system_copyright" class="required" value="<?php echo $list['system_copyright'];?>" />
								</dd>
							</dl>
						</div>
						<div class="divider"></div>
						<div class="unit">
							<dl>
								<dt>是否开启侧边菜单：</dt>
								<dd>
									<label style="width: auto"><input type="radio" <?php if($list['is_show_left_tree']==0){echo 'checked';}?> name="is_show_left_tree" value="0">开启</label>
									<label style="width: auto"><input type="radio" <?php if($list['is_show_left_tree']==-1){echo 'checked';}?> name="is_show_left_tree" value="-1">隐藏</label>
								</dd>
							</dl>
						</div>


						

						


						<div class="divider"></div>
						<div class="unit">
								<dl>
									<dt>技术支持：</dt>
									<dd>
										<input type="text" size="50"  disabled  value="西安缔造者网络科技有限公司" />
									</dd>
								</dl>
						</div>
						
						
						<div class="divider"></div>
						<div class="unit">
								<dl>
									<dt>技术支持邮箱：</dt>
									<dd>
										<input type="text" size="50" disabled  value="service@dizaozhe.cn" />
									</dd>
								</dl>
						</div>
					</div>
					
<!-- 					<div>tab2</div> -->
				</div>
				<div class="tabsFooter">
					<div class="tabsFooterContent"></div>
				</div>
			</div>



		</div>
		<div class="formBar">
			<ul>
				<li><div class="buttonActive">
						<div class="buttonContent">
							<button type="submit">保存</button>
						</div>
					</div></li>

			</ul>
		</div>

	</form>
</div>

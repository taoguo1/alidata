<div class="pageContent">
	<form method="post" action="<?php echo \Core\Lib::getUrl('SystemMessage','edit','id='.$list['id'].'&act=edit');?>" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);">
		<div class="pageFormContent" layoutH="56">
			<div class="unit">
				<dl>
					<dt>标题：</dt>
					<dd>
						<input type="text" style="width:350px;" name="title" class="required" value="<?php echo $list['title'];?>" placeholder="请输入标题，最大不能超过200个字符" />
					</dd>
				</dl>
			</div>
		    <div class="unit">
                <dl>
                    <dt>用户类型：</dt>
                    <dd>
                        <select name="user_type" class="required" style="width: 80px;">
                        	<option value="1" <?php if ($list['user_type'] == 1){echo 'selected';}; ?>>用户</option>
                        	<option value="2" <?php if ($list['user_type'] == 2){echo 'selected';}; ?>>代理商</option>
                        </select>
                    </dd>
                </dl>
           </div>
           <div class="unit">
                <dl>
                    <dt>信息类型：</dt>
                    <dd>
                        <select name="type" class="required"style="width: 80px;">
                        	<option value="1" <?php if ($list['type'] == 1){echo 'selected';}; ?>>紧急</option>
                        	<option value="2" <?php if ($list['type'] == 2){echo 'selected';}; ?>>重要</option>
                        	<option value="3" <?php if ($list['type'] == 3){echo 'selected';}; ?>>一般</option>
                        </select>
                    </dd>
                </dl>
           </div>
           <div class="unit">
                <dl>
                    <dt>状态：</dt>
                    <dd>
                        <select name="read_unread" class="required"style="width: 80px;">
                        	<option value="1" <?php if ($list['read_unread'] == 1){echo 'selected';}; ?>>已读</option>
                        	<option value="2" <?php if ($list['read_unread'] == 2){echo 'selected';}; ?>>未读</option>                                               
                        </select>
                    </dd>
                </dl>
            </div>
            <!--<div class="unit">
                <dl>
                    <dl>
                        <dt>选择用户：</dt>
                        <dd>
                            <input type="text"  class="" name="user.real_name" style="width:280px;" value="<?=$list['real_name']?>" lookupGroup="user" disabled/>
                            <input type="hidden"  class="" name="user.id" value="<?php echo $list['user_id'] ?>" lookupGroup="user" />
                            <a class="btnLook" href="<?php echo \Core\Lib::getUrl('UserAccount','getUserList');?>" lookupGroup="user">选择用户</a>
                        </dd>
                    </dl>
                </dl>
            </div>-->
            <div class="unit nowrap">
				<dl>
					<dt>描述：</dt>
					<dd>
						<textarea name="describe" placeholder="请输入描述" style="width:580px;" rows="3"><?php echo $list['describe'];?></textarea>	
					</dd>
				</dl>
			</div>
			<div class="unit">
				<dl>
					<dt>文章内容：</dt>
					<dd>
					<textarea class="editor" name="content" rows="20" cols="70"
						upLinkUrl="<?php echo  \Core\Lib::getUrl('Upload','uploadEditorFile','path=ArticleEditorFile')?>" upLinkExt="zip,rar,txt,pdf,ppt,doc,docx,xls,xlsx,pptx" 
						upImgUrl="<?php echo  \Core\Lib::getUrl('Upload','uploadEditor','path=ArticleEditor')?>" upImgExt="jpg,jpeg,gif,png,bmp" 
						upFlashUrl="<?php echo  \Core\Lib::getUrl('Upload','uploadEditorSwf','path=ArticleEditorSwf')?>" upFlashExt="swf"
						upMediaUrl="<?php echo  \Core\Lib::getUrl('Upload','uploadEditorMedia','path=ArticleEditorMedia')?>" upMediaExt:"avi,mp4"><?php echo $list['content']?></textarea>	
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

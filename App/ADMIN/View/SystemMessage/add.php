<div class="pageContent">
	<form method="post" action="<?php echo \Core\Lib::getUrl('SystemMessage','add','&act=add');?>" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);">
		<div class="pageFormContent" layoutH="56">
			<div class="unit">
				<dl>
					<dt>标题：</dt>
					<dd>
						<input type="text" style="width:350px;" name="title" class="required" value="" placeholder="请输入标题，最大不能超过200个字符" />
					</dd>
				</dl>
			</div>

            <div class="unit">
                <dl>
                    <dt>用户类型：</dt>
                    <dd>
                        <select name="user_type" class="required"style="width: 80px;">
                        	<option value="">请选择</option>
                            <option value="1">用户</option>
                            <option value="2">代理商</option>                            
                        </select>
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>信息类型：</dt>
                    <dd>
                        <select name="type" class="required"style="width: 80px;">
                        	<option value="">请选择</option>
                            <option value="1">紧急</option>
                            <option value="2">重要</option>
                            <option value="3">一般</option>                              
                        </select>
                    </dd>
                </dl>
            </div>  
            <!--<div class="unit">
                    <dl>
                        <dt>选择用户：</dt>
                        <dd>
                            <input type="text"  class="required" style="width:280px;" name="user.real_name" value="" lookupGroup="user" disabled/>
                            <input type="hidden"  class="required" name="user.id" value="" lookupGroup="user" />
                            <a class="btnLook" href="<?php echo \Core\Lib::getUrl('UserAccount','getUserList');?>" lookupGroup="user">选择用户</a>
                        </dd>
                    </dl>

            </div>          -->
            <div class="unit nowrap">
				<dl>
					<dt>描述：</dt>
					<dd>
						<textarea name="describe" placeholder="请输入描述" style="width:580px;" rows="3"></textarea>	
					</dd>
				</dl>
			</div>
			<div class="unit">
                <dl>
                    <dt>状态：</dt>
                    <dd>
                        <select name="read_unread" class="required"style="width: 80px;">
                        	<option value="2">未读</option> 
                            <option value="1">已读</option>
                                                      
                        </select>
                    </dd>
                </dl>
            </div>
            <div class="unit nowrap">
				<dl>
					<dt>内容：</dt>
					<dd>
					<textarea class="editor" name="content" rows="20" cols="70"
						upLinkUrl="<?php echo  \Core\Lib::getUrl('Upload','uploadEditorFile','path=ArticleEditorFile')?>" upLinkExt="zip,rar,txt,pdf,ppt,doc,docx,xls,xlsx,pptx" 
						upImgUrl="<?php echo  \Core\Lib::getUrl('Upload','uploadEditor','path=ArticleEditor')?>" upImgExt="jpg,jpeg,gif,png,bmp" 
						upFlashUrl="<?php echo  \Core\Lib::getUrl('Upload','uploadEditorSwf','path=ArticleEditorSwf')?>" upFlashExt="swf"
						upMediaUrl="<?php echo  \Core\Lib::getUrl('Upload','uploadEditorMedia','path=ArticleEditorMedia')?>" upMediaExt:"avi,mp4"></textarea>	
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
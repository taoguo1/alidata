<div class="pageContent">
	<form method="post" action="<?=\Core\Lib::getUrl('Role','edit','id='.$list['id'].'&act=edit');?>" class="pageForm required-validate" onsubmit="return validateCallback(this, navTabAjaxDone);">
		<div class="pageFormContent" layoutH="56">			
			<div class="unit">
				<dl>
					<dt>角色名称：</dt>
					<dd>
						<input type="text" name="name" size="40" class="required" value="<?php echo $list['name'];?>" />
					</dd>
				</dl>
			</div>
			<div class="divider"></div>
			<div class="unit">
				<dl>
					<dt>角色功能</dt>
					
				</dl>
			</div>
			<div class="unit">
				<?php echo $treeList;?>
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
$(function(){
	
	$('.check_role').change(function(){
		   var oneself = $(this);
		   subset(this,oneself);
           parenting(this,oneself);
           finallyElm(this,oneself);
					
	});
	$('.check_action').change(function(){
		var oneself = $(this);
		parentAct(this,oneself);
	});
});

//递归

function subset(obj,one){
	
	 var vid = $(obj).attr("vid");
	 var pi = $('.pid_'+vid);
	 if(pi.length >0){
		 pi.each(function(){
		 
		 	if(one.prop("checked")){
		 		$(this).prop("checked",true);
		 	}else{
		 		$(this).prop("checked",false);
		 	}
		 	
		   subset(this,one);
		})
	 }else{
	 	return false;
	}
}

function parenting(obj,one){
	var pid = $(obj).attr("pid");
	var vi = $('.vid_'+pid);
	 if(vi.length >0){
		 vi.each(function(){
		 	if(one.prop("checked")){
		 		$(this).prop("checked",true);
		 	}else{
		 		var pid = $(obj).attr('pid');
		 		var pi = $('.pid_'+pid);	
		 	
		 		var isFalse = true;
		 		for(var i= 0;i<pi.length;i++){
		 			if(pi.eq(i).prop("checked") && !pi.eq(i).hasClass('check_action')){
		 				isFalse = false;
		 				break;
		 			}
		 		}
		 		if(isFalse){
		 		  $(this).prop("checked",false);
		 		}		 		
		 	}
		 	
		   parenting(this,one);
		})
	 }else{
	 	return false;
	}
	
}

function finallyElm(obj,one){
	var vid = $(obj).attr("vid");
	var vidElm = $('.vid_'+vid);
	
	if(vidElm.length >0 ){
		for(var i= 0;i<vidElm.length;i++){
			if(vidElm.eq(i).hasClass("check_action")){
				vidElm.eq(i).each(function(){
					if(one.prop("checked")){
				 		$(this).prop("checked",true);
				 	}else{
				 		$(this).prop("checked",false);
				 	}
			
				})
           
			}
			
		}
		
	}else{
		
		return false;
	}
	
}

function parentAct(obj,one){
	var vid = $(obj).attr("vid");
	var vidAll = $('.vid_'+vid);
	var parVid = null;
	var isTrue = false;
	if(vidAll.length>0){
		for(var i= 0;i<vidAll.length;i++){
			if(!vidAll.eq(i).hasClass("check_action")){
				parVid = vidAll.eq(i);
				
			}
			if(vidAll.eq(i).prop("checked") && vidAll.eq(i).hasClass("check_action")){
				isTrue=true;
			  }
			
		}
	}
	if(one.prop("checked")){
		 parVid.prop("checked",true);
	}else{
		 if(isTrue){
		 	return false;
		 }else{
		 	parVid.prop("checked",false);
		 }
		 
	}
	parenting(parVid,one);
}


</script>
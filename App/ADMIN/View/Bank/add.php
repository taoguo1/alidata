<?php $dictionary = \Core\Lib::loadFile('Config/Dictionary.php');?>
<div class="pageContent">
	<form method="post" action="<?php echo \Core\Lib::getUrl('Bank','add','&act=add');?>" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);">
		<div class="pageFormContent" layoutH="56">
			<div class="unit">
			</div>
		<div class="unit">
				<dl>
					<dt>银行名称：</dt>
					<dd>
						<input type="text" name="name" placeholder="" style="width:100px;" value="" />
					</dd>
				</dl>
			</div>
			<div class="unit">
				<dl>
					<dt>通道代码：</dt>
					<dd>
						<input type="text" name="code_hlb" placeholder="" style="width:100px;" value="" />
					</dd>
				</dl>
			</div>
			<!--<div class="unit">
				<dl>
					<dt>通道代码yb：</dt>
					<dd>
						<input type="text" name="code_yb" placeholder="" style="width:100px;" value="" />
					</dd>
				</dl>
			</div>-->
			<div class="unit">
				<table cellpadding="0" cellspacing="0">
					<tr>
						<td>
							<label>logo：</label>
							<input type="text" id="bank_pic" placeholder="点击上传图片" style="width:200px;" name="logo" onclick="upload_file('<?php echo \Core\Lib::getUrl('upload','index','id=bank_pic&path=bank')?>')" />
							<span style="line-height: 24px; padding-left: 10px;" onclick="$('#bank_pic').val('');$('#bank_pic_img').attr('src','')">清除</span>
						</td>
						<td width="60" align="center">
							<img src="" id="bank_pic_img" name="logo"onerror="javascript:this.src='<?=APP_ADMIN_STATIC?>image/no_pic.png';" width="30" />
						</td>
					</tr>
				</table>
			</div>
			<div class="unit">
				<table cellpadding="0" cellspacing="0">
					<tr>
						<td>
							<label>back_image：</label>
							<input type="text" id="bank_pics" placeholder="点击上传图片" style="width:200px;" name="back_image" onclick="upload_file('<?php echo \Core\Lib::getUrl('upload','index','id=bank_pics&path=bank')?>')" />
							<span style="line-height: 24px; padding-left: 10px;" onclick="$('#bank_pics').val('');$('#bank_pics_img').attr('src','')">清除</span>
						</td>
						<td width="60" align="center">
							<img src="" id="bank_pics_img" name="back_image"onerror="javascript:this.src='<?=APP_ADMIN_STATIC?>image/no_pic.png';" width="30" />
						</td>
					</tr>
				</table>
			</div>
			  <div class="unit">
                <dl>
                    <dt>是否支持：</dt>
                    <dd>
                        <select name="ybskb" class="required">
                            <option value="">请选择类型</option>
                            <?php
                            foreach ($dictionary['userPush'] as $k=>$v) {
                                ?>
                                <option value="<?php echo $k ;?>"><?php echo $v ;?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </dd>
                </dl>
            </div>
			  <div class="unit">
                <dl>
                    <dt>状态：</dt>
                    <dd>
                        <select name="status" class="required">
                            <option value="">请选择</option>
                            <?php
                            foreach ($dictionary['userState'] as $k=>$v) {
                                ?>
                                <option value="<?php echo $k ;?>"><?php echo $v ;?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </dd>
                </dl>
            </div>
              <div class="unit">
                <dl>
                    <dt>卡类型：</dt>
                    <dd>
                        <select name="bank_type" class="required">
                            <option value="">请选择类型</option>
                            <?php
                            foreach ($dictionary['cardType'] as $k=>$v) {
                                ?>
                                <option value="<?php echo $k ;?>"><?php echo $v ;?></option>
                                <?php
                            }
                            ?>
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
<script language="javascript">
    function insDelPicsBox(id)
    {
    	$("#ins_pics_box"+id).remove();
    }
	function insAddPicsBox()
	{
		var len = $('#ins_pics_box').children('div').length;
		var id = parseInt(len)+1;
		var str = '<div id="ins_pics_box'+id+'">';
			str += '<input name="pics[]" value="" id="ins_pics'+id+'" style="width: 400px;" onclick=upload_file("<?php echo \Core\Lib::getUrl('upload','index','id=ins_pics')?>'+id+'")>';
			str += '<span style="font-size: 24px; padding-left:10px; color:#ff0000; cursor:pointer" onclick="insDelPicsBox('+id+')"> - </span>';
			str += '</div>';
		$("#ins_pics_box").append(str);
	}
</script>
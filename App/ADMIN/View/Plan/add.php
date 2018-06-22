<div class="pageContent">
	<form method="post" action="<?php echo \Core\Lib::getUrl('Plan','add','act=add');?>" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);">
		<div class="pageFormContent" layoutH="56">
            <h6></h6>
            <div class="unit">
                <dl>
                    <dt>用户：</dt>
                    <dd>
                        <input type="text"  class="" name="user.real_name" value="" lookupGroup="user" style="width:200px;" disabled/>
                        <input type="hidden"  class="" name="user.id" value="" lookupGroup="user" />
                        <a class="btnLook" href="<?php echo \Core\Lib::getUrl('UserAccount','getUserList');?>" lookupGroup="user">选择用户</a>
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>卡号：</dt>
                    <dd>
                        <input type="text" name="card_no" class="required creditcard" value="" style="width:200px;" />
                    </dd>
                </dl>
            </div>
			<div class="unit">
				<dl>
					<dt>还款金额：</dt>
					<dd>
						<input type="text" name="amount" class="required digits" max="1000000" value="" style="width:200px;" />
					</dd>
				</dl>
			</div>
			<div class="unit">
				<dl>
					<dt>账单日：</dt>
					<dd>
						<input type="text" name="bill_day" id="bill_day" value="" style="width:200px;" />
					</dd>
				</dl>
			</div>
            <div class="unit">
                <dl>
                    <dt>最后还款日：</dt>
                    <dd>
                        <input type="text" name="last_payment_day" id="last_payment_day" value="" style="width:200px;" />
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

    (function($){
        if ($.validator) {

        }

    })(jQuery);

</script>
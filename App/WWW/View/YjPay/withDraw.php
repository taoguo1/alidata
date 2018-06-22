<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <title></title>
    <link rel="stylesheet" type="text/css" href="<?=$cssUrl?>"/>
</head>
<body>
    <form method='post' action="<?=$formUrl?>">
        <div class="ui-form-item ui-border-b">
            <label>信用卡：</label>
            <select name="creditCardNo" id="creditCardNo">
                <?php foreach($creditCards as $creditCard): ?>
                    <option value="<?=$creditCard['card_no']?>"><?=$creditCard['card_no']?></option>
                <?php endforeach;?>
            </select>
        </div>
        <div class="ui-form-item ui-border-b">
            <label>实时到账：</label>
            <select name="isPromptly" id="isPromptly">
                <option value="true">是</option>
                <option value="false">否</option>
            </select>
        </div>
        <div class="ui-form-item ui-border-b">
            <label>借记卡号:</label>
            <input type='number' name="debitCardNo" pattern="[0-9]*" id="debitCardNo"  value='<?php echo $user['debit_card'];?>'>
        </div>
        <div class="ui-form-item ui-border-b">
            <label>提现金额:</label>
            <input type='number' name="amount" pattern="[0-9]*" id="amount"  value='' />
        </div>
        <div class="ui-btn-wrap">
            <input type="button" id="submitForm" class="ui-btn-lg ui-btn-primary"  value="提现" />
        </div>

    </form>
</body>

<script src="<?=$jqueryUrl?>"></script>
<script src="<?=$getSignUrl?>"></script>
<script>
    $(function(){
        var
            submitBtn = $("#submitForm"),
            creditCardNo = '',
            isPromptly = '',
            debitCardNo = '',
            amount = '';

        submitBtn.on('click',function(){
            creditCardNo = $("#creditCardNo").val();
            isPromptly = $("#isPromptly").val();
            debitCardNo = $.trim($("#debitCardNo").val());
            amount = $.trim($("#amount").val());
            if(!(checkDebit(debitCardNo) && checkMoney(amount))){
                return false;
            }
            var
                formDom = $("form"),
                preFormUrl = "<?=$preFormUrl?>",
                data = {
                    "creditCardNo" : creditCardNo,
                    "isPromptly" : isPromptly,
                    "debitCardNo" : debitCardNo,
                    "amount" : amount,
                    "userId" : "<?=$user['user_id']?>",
                    "appId" : "<?=$user['app_id']?>"
                };

            getSign(preFormUrl, data, formDom);
        });
    });

    function checkDebit(debitCardNo){
        if(debitCardNo.length == 0){
            alert("借记卡号不能为空");
            return false;
        }else{
            return true;
        }
    }
    function checkMoney(amount){

        if(amount.length == 0){

            alert("提现金额不能为空");
            return false;
        }else{
            return true;
        }
    }
</script>

</html>
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
    <div class="ui-form ui-border-t">
        <form method='post' id="info" action="<?= $formUrl ?>">
            <div class="ui-form-item ui-border-b">
                <label>联系电话：</label>
                <input type="text" name="phone" id="phone" value=""/>
            </div>
            <div class="ui-form-item ui-border-b">
                <label>信用卡号：</label>
                <input type="text" name="creditCardNo" id="creditCardNo" value=""/>
            </div>
            <div class="ui-form-item ui-border-b">
                <label>真实名称：</label>
                <input type="text" name="realName" id="realName" value=""/>
            </div>
            <div class="ui-form-item ui-border-b">
                <label>身份证号:</label>
                <input type="text" name="identityNo" id="identityNo" value=""/>
            </div>
            <div class="ui-form-item ui-border-b">
                <label>银行预留电话:</label>
                <input type="text" name="bankPhone" id="bankPhone" value=""/>
            </div>
            <div class="ui-btn-wrap">
                <input type="button" id="submitForm"  class="ui-btn-lg ui-btn-primary" value="提交"/>
            </div>
        </form>
        <input type="hidden" id="idFront" />
        <input type="hidden" id="idBack" />
    </div>
</body>


<script src="<?=$jqueryUrl?>"></script>
<script src="<?=$getSignUrl?>"></script>
<script>
    $(function () {
        var
            submitBtn = $("#submitForm"),
            creditCardNo = '',
            realName = '',
            identityNo = '',
            phone = '',
            bankPhone = '';

        submitBtn.on('click', function () {
            var
                formDom = $("#info"),
                preFormUrl = "<?=$preFormUrl?>",
                data = {};


            creditCardNo = $.trim($("#creditCardNo").val());
            realName = $.trim($("#realName").val());
            identityNo = $.trim($("#identityNo").val());
            phone = $.trim($("#phone").val());
            bankPhone = $.trim($("#bankPhone").val());

            if(!(checkPhone(phone) && checkCredit(creditCardNo) && checkName(realName) && checkCard(identityNo) && checkBankPhone(bankPhone))){
                return false;
            }

            data = {
                "creditCardNo": creditCardNo,
                "realName": realName,
                "identityNo": identityNo,
                "phone": phone,
                "bankPhone": bankPhone,
                "appId" : "<?=$appId?>",
                "userId": "<?=$userId?>"
            };
            getSign(preFormUrl, data, formDom);

        });

    });
    function checkBankPhone(bankPhone){//银行预留手机号
        if(bankPhone.length == 0){
            alert("预留手机号不能为空");
            return false;
        }

        var reg = /^1[3-9]\d{9}$/;

        if(reg.test(bankPhone)){
            return true;
        }else{
            alert("手机号码格式错误");

            return false;
        }

    }
    function checkCard(identityNo){//身份证号

        if(identityNo.length == 0){
            alert("身份证号不能为空");
            return false;
        }

        if(cardId(identityNo)){
            return true;
        }else{

            alert("身份证号格式错误");
            return false;
        }
    }

    function cardId(sId){
        var aCity = {
            11: "北京",
            12: "天津",
            13: "河北",
            14: "山西",
            15: "内蒙古",
            21: "辽宁",
            22: "吉林",
            23: "黑龙江",
            31: "上海",
            32: "江苏",
            33: "浙江",
            34: "安徽",
            35: "福建",
            36: "江西",
            37: "山东",
            41: "河南",
            42: "湖北",
            43: "湖南",
            44: "广东",
            45: "广西",
            46: "海南",
            50: "重庆",
            51: "四川",
            52: "贵州",
            53: "云南",
            54: "西藏",
            61: "陕西",
            62: "甘肃",
            63: "青海",
            64: "宁夏",
            65: "新疆",
            71: "台湾",
            81: "香港",
            82: "澳门",
            91: "国外"
        }
        var iSum = 0;
        var info = "";
        if(!/^\d{17}(\d|x)$/i.test(sId)) {
            return false;
        }
        sId = sId.replace(/x$/i, "a");
        if(aCity[parseInt(sId.substr(0, 2))] == null) {
            return false;
        }

        var sBirthday = sId.substr(6, 4) + "-" + Number(sId.substr(10, 2)) + "-" + Number(sId.substr(12, 2));
        var d = new Date(sBirthday.replace(/-/g, "/"));
        if(sBirthday != (d.getFullYear() + "-" + (d.getMonth() + 1) + "-" + d.getDate())) {
            return false;
        }

        for(var i = 17; i >= 0; i--) {
            iSum += (Math.pow(2, i) % 11) * parseInt(sId.charAt(17 - i), 11);
        }

        if(iSum % 11 != 1) {
            return false;
        }
        sId = sId.replace(/a$/i, "X");
        var last = sId.substr(-1);
        if(getjy(sId) != last){
            return false;
        }

        if(/^\d{17}(\d|x)$/i.test(sId)) {
            return true;
        }
    }
    function checkName(realName){//姓名
        if(realName.length ==0){
            alert("姓名不能为空");
            return false;
        }else{
            return true;
        }
    }

    function getjy(id) {
        var arr = new Array();//分别取出身份证的前17位加入到这个数组
        var arr1 = new Array();//对向相应的次方
        var num = 0;
        for (var i = 0; i < 17; i++) {
            arr[i] = id.charAt(i);
        }
        for (var i = 0; i < arr.length; i++) {
            var len = arr.length - (i);
            arr1[i] = arr[i] * qcf(len);
        }
        for (var i = 0; i < arr1.length; i++) {
            num += arr1[i];
        }

        num = num % 11;//结果对11取余算出最后结果
        switch (num) {
            case 0:
                return "1";
            case 1:
                return "0";
            case 2:
                return "X";
            case 3:
                return "9";
            case 4:
                return "8";
            case 5:
                return "7";
            case 6:
                return "6";
            case 7:
                return "5";
            case 8:
                return "4";
            case 9:
                return "3";
            case 10:
                return "2";
        }

    }
    function qcf(n) {
        var num = 2;
        for (var i = 1; i < n; i++) {
            num *= 2;
        }
        return num;
    }

    function setNumWord(obj,num){
        var str = obj.value;
        str = str.replace(/[^0-9]/,"");
        if(str.length>num){
            str =  str.substr(0,num);
            obj.value = str;
        }else{
            obj.value = str;
        }
    }
    function checkPhone(phone){//手机号
        if(phone.length == 0){
            alert("联系电话不能为空");
            return false;
        }

        var reg = /^1[3-9]\d{9}$/;

        if(reg.test(phone)){
            return true;
        }else{
            alert("手机号码格式错误");

            return false;
        }

    }
    function checkCredit(creditCardNo){//信用卡号
        if(creditCardNo.length == 0){
            alert("信用卡号不能为空");
            return false;
        }else{
            return true;
        }
    }
</script>

</html>
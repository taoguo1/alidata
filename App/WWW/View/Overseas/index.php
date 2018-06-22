<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title></title>
    <style type="text/css">
        * {
            margin: 0;
            padding: 0;
        }

        body {
            font-size: 16px;
background:#fff;

        }

        .w_top {
            width: 100%;
        }

        .w_top img {
            width: 100%;
        }

        .bg {
            color: #fff;
        }

        .w_mid {
            padding-top: 20px;
        }

        ul,
        li {
            list-style: none;
        }

        .w_mid ul {
            width: 74%;
            margin: 0 auto;
        }

        input {
            border: 0;
            outline: 0;
            -webkit-appearance: none;
        }

        .box {
            display: -webkit-box;
        }

        .box-flex-1 {
            -webkit-box-flex: 1;
        }

        .box-align {
            -webkit-box-align: center;
        }

        .input_wrap {
            /*border-bottom: 1px solid #dcdcdc;*/
            height: 30px;
            line-height: 30px;
        }

        .input_wrap input {
            width: 100%;
            text-indent: 10px;
            font-size: 16px;
        }

        .label_img {
            width: 50px;
        }

        .me-border-b {
            position: relative;
        }

        .me-border-b:after {
            content: "";
            pointer-events: none;
            /* 防止点击触发 */
            box-sizing: border-box;
            position: absolute;
            width: 250%;
            left: 0;
            bottom: 0;
            /*border-radius: 8px;*/
            border-bottom: 1px solid #e0e0e0;
            -webkit-transform: scale(0.4);
            -webkit-transform-origin: left bottom;
            transform: scale(0.4);
            transform-origin: left bottom;
            z-index: 10000;
        }

        .label_img img {
            height: 30px;
            display: block;
        }

        .w_mid ul li {
            padding-bottom: 20px;
        }

        .ka_color {
            color: #00b0fc;
        }

        .ftz16 {
            font-size: 16px;
        }

        .ftz18 {
            font-size: 18px;
        }

        .t-right {
            text-align: right;
        }

        .but_code {
            display: inline-block;
            width: 85px;
        }

        .go_pay {
            display: inline-block;
            position: absolute;
            right: 0;
            top: 0;
            background: #00b0fc;
            color: #FFFFFF;
            font-size: 16px;
            height: 30px;
            line-height: 30px;
            padding: 0 5px;
            border-radius: 3px;
        }

        .remark_d {
            color: #e82c2b;
            text-indent: 5px;
        }

        .ui-btn-wrap {
            padding: 15px 10px;
        }

        .ui-btn,
        .ui-btn-lg,
        .ui-btn-s {
            height: 30px;
            line-height: 30px;
            padding: 0 11px;
            min-width: 55px;
            display: inline-block;
            position: relative;
            text-align: center;
            font-size: 15px;
            background-color: #fdfdfd;
            background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0.5, #fff), to(#fafafa));
            vertical-align: top;
            color: #00a5e0;
            -webkit-box-sizing: border-box;
            -webkit-background-clip: padding-box;
            background-clip: padding-box;
            border: 1px solid #00b0fc;
            border-radius: 3px;
        }

        .ui-btn-lg {
            font-size: 18px;
            height: 44px;
            line-height: 44px;
            display: block;
            width: 100%;
            border-radius: 5px;
        }

        .ui-btn-primary {
            background-color: ##00b0fc;
            border-color: ##00b0fc;
            background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0.5, #00b0fc), to(#00b0fc));
            color: #fff;
            -webkit-background-clip: padding-box;
            background-clip: padding-box;
            width: 80%;
            margin: 0 10% 20px 10%;
        }

        button {
            -webkit-appearance: none;
            border: 0;
            background: 0 0;
            outline: 0;
        }

        .ui-btn:after,
        .ui-btn-lg:after,
        .ui-btn-s:after {
            content: "";
            position: absolute;
            top: -7px;
            bottom: -7px;
            left: 0;
            right: 0;
        }

        .w_bottom {
            width: 88%;
            margin: 0 auto;
        }

        .but_code:active,
        button:active,
        .go_pay:active {
            opacity: 0.5;
        }

        #orderId {
            background-color: #FFFFFF;
        }
    </style>
</head>

<body>
<div class="wrap bg">
    <div class="w_top">
        <img src="/Static/image/jwxf.png?id=<?=time()?>" alt="" />
    </div>
    <div class="w_mid">
        <form id="form" action="https://manage.dizaozhe.cn/overseas" method="post">
            <ul>
                <li class="box box-align">
                    <div class="label_img"><img src="/Static/image/ka07.png" alt="" /></div>
                    <div class="box-flex-1 input_wrap me-border-b"><input type="number" name="amt" id="amt" placeholder="请输入金额(马来西亚林吉特)" value="<?=$data['amt']?>" readonly /></div>
                </li>
                <li class="box box-align">
                    <div class="label_img"><img src="/Static/image/ka01.png" alt="" /></div>
                    <div class="box-flex-1 input_wrap me-border-b"><input type="text" name="name" id="name" placeholder="请输入持卡人姓名" value="<?=$data['name']?>" /></div>
                </li>
                <li class="box box-align">
                    <div class="label_img"><img src="/Static/image/ka03.png" alt="" /></div>
                    <div class="box-flex-1 input_wrap me-border-b"><input type="number" name="card" id="card" placeholder="请输入信用卡号" value="<?=$data['card']?>" /></div>
                </li>
                <li class="box box-align">
                    <div class="label_img"><img src="/Static/image/ka02.png" alt="" /></div>
                    <div class="box-flex-1 input_wrap me-border-b"><input type="text" name="idno" id="idno" placeholder="请输入身份证号" value="<?=$data['idno']?>" /></div>
                </li>
                <li class="box box-align">
                    <div class="label_img"><img src="/Static/image/ka04.png" alt="" /></div>
                    <div class="box-flex-1 input_wrap me-border-b"><input type="number" name="phone" id="phone" placeholder="请输入手机号" value="<?=$data['phone']?>" /></div>
                </li>
            </ul>

            <input name="appid" type="hidden" value="<?php echo $data['appid'] ?>"  />
            <div class="ui-btn-lg ui-btn-primary" type="button" onclick="return okBtn()" readonly="readonly" id="caoform">确认支付 </div>
        </form>

    </div>

    <p style="width: 80%;margin: 0 auto 50px auto; color: #000; font-size: 14px;color:red">
        注：<br/> 每个卡每天支付3次，每次间隔20分钟，每次支付金额不能一样<br/>金额100-130人民币 <br/>支付时间：08:00-21:00<br/>是否到账：不到账
    </p>
</div>
</body>

</html>
<script type="text/javascript">
    <?php if($data['error']): ?>
        alert("<?=$data['error']?>");
    <?php endif; ?>
    var amt = document.getElementById('amt'),
        name = document.getElementById('name'),
        card = document.getElementById('card'),
        idno = document.getElementById('idno'),
        phone = document.getElementById('phone');

    function okBtn() {
        flag = false;

        if(amt.value == '') {
            alert('金额不能为空');
            return false;
        }
        if(name.value == '') {
            alert('姓名不能为空');
            return false;
        }
        if(card.value == '') {
            alert('信用卡号不能为空');
            return false;
        }
        if(idno.value == '') {
            alert('身份证号不能为空');
            return false;
        }else{
            testCard(idno.value);
        }
        if(phone.value == '') {
            alert("手机号不能为空");
            return false;
        } else {
            var reg = /^1[3-9]\d{9}$/;
            if(!reg.test(phone.value)) {
                alert("手机号码格式错误");
                return false;
            }
        }
            flag = true;
        if(flag) {
            document.getElementById('caoform').setAttribute("disabled","true");
            document.getElementById('form').submit();
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
    //身份证号验证
    function testCard(sId){
        if(sId.length == 0){

        }
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
    


</script>
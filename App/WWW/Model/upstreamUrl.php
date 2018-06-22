<?php
return [
    'partnerCode' =>  '18042418580300200000',
    'secretKey' => 'ca3fae56ae9ac12042cf6b5b1b9d8202',
    'baseUrl' => 'http://scdeercnet.yijifu.net',//测试
//    'baseUrl' => 'http://www.shoucaideer.com',//生产
    'page' => [
        'channelInfoCheck' => '/agency/api/channelInfoCheck.html',//通道信息验证页面
        'openPayAccount' => '/agency/api/openPayAccount.html',//开通支付账户页面
        'withDraw' => '/agency/api/withdraw.html',//提现页面
    ],
    'api' => [
        'queryChannelList' => '/agency/api/queryChannelList.json',//查询通道可用接口　｜ 同步
        'queryOrder' => '/agency/api/queryOrder.json',//查询提现订单接口 |　同步
        'queryChannelInfoCheck' => '/agency/api/queryChannelInfoCheck.json',//查询通道信息验证接口　｜ 同步
        'queryPayAccount' => '/agency/api/queryPayAccount.json'//查询用户支付账户状态接口 |　同步
    ]
];

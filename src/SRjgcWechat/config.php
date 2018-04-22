<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/21
 * Time: 20:34
 */

return [
    'wechat_appid'=>'',
    'wechat_appsecret'=>'',
    'wechat_token'=>'wufeiqi',

    'oauth' => [
        'scopes'   => ['snsapi_userinfo'],  //授权方式
        'callback' => '/oauth_callback', //回调地址
    ],

    'payment' => [
        'merchant_id'        => '',  //商户号
        'key'                => '',
        'cert_path'          => 'path/to/your/cert.pem', // XXX: 绝对路径！！！！ 证书pem格式
        'key_path'           => 'path/to/your/key',      // XXX: 绝对路径！！！！ 证书密钥pem格式
        'notify_url'         => '默认的订单回调地址',       // 订单回调地址
        // 'device_info'     => '013467007045764',
        // 'sub_app_id'      => '',
        // 'sub_merchant_id' => '',
        // ...
    ],
];
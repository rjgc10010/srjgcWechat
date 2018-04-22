<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/21
 * Time: 20:30
 */


include './../src/autoloader.php';
$sRjgcWechat=new \SRjgcWechat\SRjgcWechat();
//$sRjgcWechat->index();

//echo '<xml><ToUserName><![CDATA[oGVupwwy-ark1bmPYeMIAtbkudjY]]></ToUserName><FromUserName><![CDATA[gh_2f275700be7f]]></FromUserName><CreateTime>1524382322</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA[回复文本]]></Content></xml>';

//获取code(会跳转到)
/*$oauth=$sRjgcWechat->oauth();
$oauth->getBaseInfo();*/

//获取openid
//$oauth->getUserOpenId();


//echo \SRjgcWechat\curl\Curl::http('http://mini.eastday.com',['qid'=>hao123tj,'id'=>'n180422065155171']);die;
//获取access_token
//echo $sRjgcWechat->accessToken()->getAccessToken();die;


//设置自定义菜单
/*$menu=array(
    array(
        'type'=>'click',
        'name'=>'官网',
        'key'=>'click'
    ),
    array(
        'name'=>'博客',
        'sub_button'=>array(
            array(
                'type'=>'view',
                'name'=>'编程语言',
                'url'=>'http://hao123.com',
            ),
            array(
                'type'=>'view',
                'name'=>'编程语言',
                'url'=>'http://hao123.com',
            )
        )
    ),
    array(
        'type'=>'scancode_push',
        'name'=>'扫一扫',
        'key'=>'扫一扫',
    )
);*/

//$res=$sRjgcWechat->menu()->setMenu($menu);
//print_r($res);die;

//页面授权
/*$code=isset($_GET['code'])?$_GET['code']:'';
$state=isset($_GET['state'])?$_GET['state']:'';

$url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

//判断是否已经授权
if (empty($_SESSION['openid'])&&empty($code)){
    $sRjgcWechat->oauth()->jumpOauth($url);
}

//完成第一次跳转
if (!empty($code)&&$state=='one'){
    if ($sRjgcWechat->oauth()->getToken()){
        $sRjgcWechat->oauth()->getAccessToken($code);
    }

}*/

//获取openid

$luckyMoneyData = [
    'mch_billno'       => time(),   //商户订单号
    'send_name'        => '测试红包',   //商户名称
    're_openid'        => 'oGVupwwy-ark1bmPYeMIAtbkudjY',  //用户openid
    'total_num'        => 1,  //普通红包固定为1，裂变红包不小于3   //红包发放总人数
    'total_amount'     => 100,  //单位为分，普通红包不小于100，裂变红包不小于300  //付款金额
    'wishing'          => '祝福语',  //红包祝福语
    'client_ip'        => '192.168.0.1',  //可不传，不传则由 SDK 取当前客户端 IP  Ip地址
    'act_name'         => '测试活动',  //活动名称
    'remark'           => '测试备注',   //备注
    // ...
];
$luckyMoney=$sRjgcWechat->luckyMoney();
$res=$luckyMoney->sendRedpack($luckyMoneyData);

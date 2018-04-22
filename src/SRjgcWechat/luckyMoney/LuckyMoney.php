<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/22
 * Time: 11:00
 */
namespace SRjgcWechat\luckyMoney;

use SRjgcWechat\curl\Curl;
use SRjgcWechat\curl\XML;
use SRjgcWechat\message\Message;

class LuckyMoney{
    //https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack
    const send_redpack='https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack';

    //https://api.mch.weixin.qq.com/mmpaymkttransfers/sendgroupredpack
    const send_groupredpack='https://api.mch.weixin.qq.com/mmpaymkttransfers/sendgroupredpack';

    //https://api.mch.weixin.qq.com/mmpaymkttransfers/gethbinfo
    const send_gethbinfo='https://api.mch.weixin.qq.com/mmpaymkttransfers/gethbinfo';

    private $config;
    public function __construct($config)
    {
        $this->config=$config;

    }

    public function sendRedpack($data){
        /*$param=array(
            'access_token'=>nonce_str->getAccessToken(),
        );*/

        /**
         * 'mch_billno'       => 'xy123456',
        'send_name'        => '测试红包',
        're_openid'        => 'oxTWIuGaIt6gTKsQRLau2M0yL16E',
        'total_num'        => 1,  //普通红包固定为1，裂变红包不小于3
        'total_amount'     => 100,  //单位为分，普通红包不小于100，裂变红包不小于300
        'wishing'          => '祝福语',
        'client_ip'        => '192.168.0.1',  //可不传，不传则由 SDK 取当前客户端 IP
        'act_name'         => '测试活动',
        'remark'           => '测试备注',
         */

        /**
         * 随机字符串	nonce_str   uniqid();
         * 签名	sign                签名生成算法
         * 商户订单号	mch_billno      1
         * 商户号	mch_id                  2
         * 公众账号appid	wxappid         2
         * 商户名称	send_name          1
         * 用户openid	re_openid      1
         * 付款金额	total_amount       1
         * 红包发放总人数	total_num    1
         * 红包祝福语	wishing          1
         * Ip地址	client_ip           1
         * 活动名称	act_name           1
         * 备注	remark                 1
         * 场景id	scene_id
         * 活动信息	risk_info
         * 资金授权商户号	consume_mch_id
         */

        $data['nonce_str']=uniqid();  //随机字符串

        $data['mch_id']=$this->config['payment']['merchant_id'];  //商户号
        $data['wxappid']=$this->config['wechat_appid'];  //公众账号appid
        $data['sign']=self::generate_sign($data,$this->config['payment']['key'],'md5');  //随机字符串


        $options = [
            'body' => XML::build($data),
           // 'cert' => $this->merchant->get('cert_path'),
          //  'ssl_key' => $this->merchant->get('key_path'),
        ];



        $res=Curl::http(self::send_redpack,'',XML::build($data),'POST');
        $res=json_decode($res,true);
        if (!empty($res)){
            return $res;
        }
        else{
            return $res;
            //throw new \Exception('发放现金红包失败');
        }

    }


    private function generate_sign(array $attributes, $key, $encryptMethod = 'md5'){
        ksort($attributes);
        $attributes['key'] = $key;
        return strtoupper(call_user_func_array($encryptMethod, [urldecode(http_build_query($attributes))]));
    }


    private function dotoXml($data){
        $xml=new \SimpleXMLElement('<xml></xml>');
        foreach ($data as $key => $value) {
            $child = $xml->addChild($key);
            $node  = dom_import_simplexml($child);
            $cdata = $node->ownerDocument->createCDATASection($value);
            $node->appendChild($cdata);
        }
        return $xml->asXML();
    }
}
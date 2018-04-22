<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/22
 * Time: 0:55
 */

namespace SRjgcWechat\accessToken;

use SRjgcWechat\curl\Curl;

class AccessToken{
    //https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=APPID&secret=APPSECRET
    const access_token='https://api.weixin.qq.com/cgi-bin';

    private $config;
    public function __construct($config)
    {
        $this->config=$config;

    }


    public function getAccessToken(){
        if (!empty($_COOKIE['base_access_token'])){
            return $_COOKIE['base_access_token'];
        }
        else{
            return $this->curlGetAccessToken();
        }
    }



    private function curlGetAccessToken(){
        $url=self::access_token.'/token';
        $param=array(
            'grant_type'=>'client_credential',
            'appid'=>$this->config['wechat_appid'],
            'secret'=>$this->config['wechat_appsecret'],
        );

        $res=Curl::http($url,$param,'','GET');
        $access_token=json_decode($res,true);
        if (!empty($access_token)){
            /*$oauth_access_token=[
                'access_token'=>$access_token['access_token'],
                'get_time'=>time()
            ];

            $_SESSION['base_access_token']=$oauth_access_token;*/
            setcookie('base_access_token',$access_token['access_token'],time()+7000);
            return $access_token['access_token'];
        }
        else{
            throw new \Exception('AccessToken获取失败');
        }
    }
}
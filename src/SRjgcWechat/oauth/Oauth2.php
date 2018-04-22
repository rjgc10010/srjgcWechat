<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/21
 * Time: 21:12
 */
namespace SRjgcWechat\oauth;

use SRjgcWechat\curl\Curl;

class Oauth2{

    private $config;
    public function __construct($config)
    {
        $this->config=$config;

    }


    /**
     * 用户会先进入到一个页面，这个页面需要获取用户信息（微信登录） getBaseInfo
     *
     * 然后进行重定向获取到用户openid
     *
     */

    /**
     * 获取code(会重定向到获取openid)
     */
    public function getBaseInfo(){
        //1,获取code
        $appid=$this->config['wechat_appid'];
        $redirect_uri=$_SERVER['SERVER_NAME'].'/getWxCode';
        $scope='snsapi_base';
        $state=123;

        $code_url="https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$appid."&redirect_uri=".$redirect_uri."&response_type=code&scope=".$scope."&state=".$state."#wechat_redirect";

        header('location:'.$code_url);

    }

    /**
     * 获取openid
     */
    public function getUserOpenId(){
        //2.获取到网页授权的access_token
        $appid=$this->config['wechat_appid'];
        $secret='wechat_appsecret';
        $code=$_GET['code'];

        //3.拉去用户的openID
        $url="https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$appid."&secret=".$secret."&code=".$code."&grant_type=authorization_code";
        Curl::execute_url($url,'get');
    }

    /**
     * 获取用户详情信息
     */
    public function getUserDetail(){
        $appid=$this->config['wechat_appid'];
        $redirect_uri=$_SERVER['SERVER_NAME'].'/getUserinfo';
        $scope='snsapi_userinfo';
        $state=123;

        $code_url="https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$appid."&redirect_uri=".$redirect_uri."&response_type=code&scope=".$scope."&state=".$state."#wechat_redirect";

        header('location:'.$code_url);
    }

    public function getUserinfo(){
        //2.获取到网页授权的access_token
        $appid=$this->config['wechat_appid'];
        $secret='wechat_appsecret';
        $code=$_GET['code'];

        //3.拉去用户的openID
        $url="https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$appid."&secret=".$secret."&code=".$code."&grant_type=authorization_code";
        $res=Curl::execute_url($url,'get');
        $access_token=$res['access_token'];
        $openid=$res['openid'];

        //拉取用户详情信息
        $user_info_url="https://api.weixin.qq.com/sns/userinfo?access_token=".$access_token."&openid=".$openid."&lang=zh_CN";
        $res=Curl::execute_url($url,'get');
        var_dump($res);

    }

}
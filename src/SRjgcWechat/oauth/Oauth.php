<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/21
 * Time: 21:12
 */
namespace SRjgcWechat\oauth;

use SRjgcWechat\curl\Curl;

class Oauth{
    //https://open.weixin.qq.com/connect/oauth2/authorize?appid=APPID&redirect_uri=REDIRECT_URI&response_type=code&scope=SCOPE&state=STATE#wechat_redirect
    const oauth_code='https://open.weixin.qq.com/connect/oauth2/authorize';
    //https://api.weixin.qq.com/sns/oauth2/access_token?appid=APPID&secret=SECRET&code=CODE&grant_type=authorization_code
    const oauth_access_token='https://api.weixin.qq.com/sns/oauth2/access_token';

    //https://api.weixin.qq.com/sns/userinfo?access_token=ACCESS_TOKEN&openid=OPENID&lang=zh_CN
    const oauth_userinfo='https://api.weixin.qq.com/sns/userinfo';

    private $config;
    private $access_token;
    public function __construct($config)
    {
        $this->config=$config;

    }

    /**
     * 授权跳转.
     *
     * @param string $redirect_uri 回调地址
     * @param string $scope 应用授权作用域   snsapi_base snsapi_userinfo
     * @param string $state 场景标识,用于跳转区分
     */
    public function jumpOauth($redirect_uri='',$scope='snsapi_base',$state='one'){
        $url=self::oauth_code;
        $param=array(
            'appid'=>$this->config['wechat_appid'],
            'redirect_uri'=>$redirect_uri,
            'response_type'=>'code',
            'scope'=>$scope,
            'state'=>$state
        );

        $url=$url.http_build_query($param).'#wechat_redirect';

        header('location:'.$url);
    }


    /**
     * 获取网页授权access_token
     *
     * @param string $code
     * @return mixed
     * @throws \Exception
     */
    public function getAccessToken($code=''){
        $url=self::oauth_access_token;
        $param=array(
            'appid'=>$this->config['wechat_appid'],
            'secret'=>$this->config['wechat_appsecret'],
            'code'=>$code,
            'grant_type'=>'authorization_code',
        );

        $res=Curl::http($url,$param,'','GET');
        $access=json_decode($res,true);
        if (!empty($access)){
            $this->accessToken=$access['access_token'];
        }
        else{
            throw new \Exception('AccessToken获取失败');
        }

        return $access;
    }

    /**
     * 获取access_token
     * @return mixed
     * @throws \Exception
     */
    public function getToken(){
        if ($this->accessToken){
            return $this->accessToken;
        }
        else{
            self::getAccessToken();
            return $this->accessToken;
        }
    }


    /**
     * 获取用户信息
     *https://api.weixin.qq.com/sns/userinfo?access_token=ACCESS_TOKEN&openid=OPENID&lang=zh_CN
     * @param string $code
     * @return mixed
     * @throws \Exception
     */
    public function getUserinfo($code=''){
        $url=self::oauth_userinfo;
        $param=array(
            'access_token'=>$this->access_token,
            'openid'=>$this->config['wechat_appsecret'],
            'lang'=>'zh_CN',
        );

        $res=Curl::http($url,$param,'','GET');
        $userinfo=json_decode($res,true);
        return $userinfo;

    }


}
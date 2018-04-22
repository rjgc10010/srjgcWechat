<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/21
 * Time: 21:12
 */
namespace SRjgcWechat\menu;

use SRjgcWechat\accessToken\AccessToken;
use SRjgcWechat\curl\Curl;

class Menu{
    //https://api.weixin.qq.com/cgi-bin/menu/create?access_token=ACCESS_TOKEN
    const access_token='https://api.weixin.qq.com/cgi-bin/menu/create';
    private $config;
    public function __construct($config)
    {
        $this->config=$config;

    }

     public function setMenu($menu_arr){
        $accessToken=new AccessToken($this->config);
         $url=self::access_token;
         $param=array(
             'access_token'=>$accessToken->getAccessToken(),
         );

         $post_param['button']=$menu_arr;

         array_walk_recursive($post_param,function (&$value){
            $value=urlencode($value);
         });

         $post_param=urldecode(json_encode($post_param));
         $res=Curl::http($url,$param,$post_param,'POST');
         $res=json_decode($res,true);
         if (!empty($res)){
             return $res;
         }
         else{
             throw new \Exception('AccessToken获取失败');
         }

     }
}
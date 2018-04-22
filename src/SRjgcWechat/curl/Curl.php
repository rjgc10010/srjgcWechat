<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/21
 * Time: 21:33
 */
namespace SRjgcWechat\curl;

class Curl{
    public static function execute_url($url){
        //获取imooc
        //1.初始化curl
        $ch = curl_init();
        //$url = 'http://www.baidu.com';
        //2.设置curl的参数
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //3.采集
        $output = curl_exec($ch);
        //4.关闭
        curl_close($ch);
        var_dump($output);
    }

    /**
     * curl请求
     *
     * @param $url 请求路径
     * @param string $param get请求参数
     * @param string $data post请求参数
     * @param string $method 请求方法
     * @return mixed
     */
    public static function http($url,$param='',$data='',$method="GET"){

        $opts=array(
            CURLOPT_TIMEOUT=>30,
            CURLOPT_RETURNTRANSFER=>1, //返回原输出
            CURLOPT_SSL_VERIFYHOST=>false,
            CURLOPT_SSL_VERIFYPEER=>false,//禁止对证书的验证
        );

        //拼接get请求数组组织新的URL地址
        if (empty($param)){
            $opts[CURLOPT_URL]=$url; //格式数组成get请求参数
        }
        else{
            $opts[CURLOPT_URL]=$url.'?'.http_build_query($param); //格式数组成get请求参数
        }


        //post请求参数
        if ($method=='POST'){

            $opts[CURLOPT_POST]=true;
            $opts[CURLOPT_POSTFIELDS]=$data;

            //如果post为字符串，那么就进行json提交
            if (is_string($data)){
                $opts[CURLOPT_HTTPHEADER]=array(
                    'Content-Type: application/json; charset=utf-8',
                    'Content-Length:' . strlen($data));
            }
        }

        $ch = curl_init();
        curl_setopt_array($ch, $opts);
        $res = curl_exec($ch);
        curl_close($ch);
        return $res;

        echo $res;die;
        return $res;

    }
}
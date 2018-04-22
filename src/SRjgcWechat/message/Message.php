<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/21
 * Time: 22:00
 */

namespace SRjgcWechat\message;

class Message{

    const MSG_TYPE_TEXT='text'; //回复文本消息
    const MSG_TYPE_IMAGE='image'; //回复图片消息
    const MSG_TYPE_VOICE='voice'; //回复语音消息
    const MSG_TYPE_VIDEO='video'; //回复视频消息
    const MSG_TYPE_MUSIC='music'; //回复音乐消息
    const MSG_TYPE_NEWS='news'; //回复图文消息
    const MSG_TYPE_LUCKYMONEY='luckymoney'; //红包


    private $config;
    public function __construct($config)
    {
        $this->config=$config;

    }

    /**
     * 处理返回数据
     *
     * @param $content 回复的内容
     * @param $user_info 回复用户数据
     * @param string $type 回复的类型
     */
    public function response($content,$user_info,$type=self::MSG_TYPE_TEXT){
        $data=array(
            'ToUserName'=>$user_info['FromUserName'],
            'FromUserName'=>$user_info['ToUserName'],
            'CreateTime'=>time(),
            'MsgType'=>$type,
        );

        $content=call_user_func(array(Message::class,$type),$content);
        if ($type==self::MSG_TYPE_TEXT||$type==self::MSG_TYPE_NEWS){
            $data=array_merge($data,$content);
        }
        else{
            $data[ucfirst($type)]=$content;
        }

        $xml=new \SimpleXMLElement('<xml></xml>');
        self::dataXml($xml,$data);
        exit($xml->asXML());
    }

    /**
     * 数组转换为xml数据
     * @param $xml
     * @param $data
     */
    private static function dataXml($xml,$data,$item='item'){

        foreach ($data as $key => $value) {
            /* 指定默认的数字key */
            is_numeric($key) && $key = $item;

            /* 添加子元素 */
            if(is_array($value) || is_object($value)){
                $child = $xml->addChild($key);
                self::dataXml($child, $value, $item);
            } else {
                if(is_numeric($value)){
                    $child = $xml->addChild($key, $value);
                } else {
                    $child = $xml->addChild($key);
                    $node  = dom_import_simplexml($child);
                    $cdata = $node->ownerDocument->createCDATASection($value);
                    $node->appendChild($cdata);
                }
            }
        }
    }

    /**
     * 组织回复文本
     *
     * @param $content
     * @return mixed
     */
    private static function text($content){
        $data['Content']=$content;
        return $data;
    }

    /**
     * 组织图片消息
     * @param $content
     * @return mixed
     */
    private static function image($content){
        $data['MediaId']=$content;
        return $data;
    }

    /**
     * 组织语音消息
     * @param $content
     * @return mixed
     */
    private static function voice($content){
        $data['MediaId']=$content;
        return $data;
    }


    /**
     * 组织视频消息
     * @param $content
     * @return mixed
     */
    private static function video($content){
        $data=array();
        list(
            $data['MediaId'],
            $data['Title'],
            $data['Description']
            )=$content;
        return $data;
    }

    /**
     * 组织音乐消息
     * @param $content
     * @return mixed
     */
    private static function music($content){
        $data=array();
        list(
            $data['Title'],
            $data['Description'],
            $data['MusicUrl'],
            $data['HQMusicUrl'],
            $data['ThumbMediaId']
            )=$content;
        return $data;
    }

    /**
     * 组织图文消息
     * @param $content
     * @return mixed
     */
    private static function news($content){
        $news=array();
        /*foreach ($content as $key=>$value){
            list(
                $news[$key]['Title'],
                $news[$key]['Description'],
                $news[$key]['PicUrl'],
                $news[$key]['Url']
                )=$value;
            if ($key>8){
                break;
            }
        }*/

        foreach ($content as $key=>$value){
            $news[$key]=$value;

            if ($key>8){
                break;
            }
        }

        $data['ArticleCount']=count($news);
        $data['Articles']=$news;

        return $data;
    }


}
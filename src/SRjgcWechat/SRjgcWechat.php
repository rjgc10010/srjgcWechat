<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/21
 * Time: 20:26
 */
namespace  SRjgcWechat;


use SRjgcWechat\accessToken\AccessToken;
use SRjgcWechat\luckyMoney\LuckyMoney;
use SRjgcWechat\menu\Menu;
use SRjgcWechat\message\Message;
use SRjgcWechat\oauth\Oauth;

class SRjgcWechat{
    public $config;
    public $post_data;

    public function __construct()
    {
        $this->config=include dirname ( __FILE__ ).'/config.php';
    }

    /**
     * 验证服务器URL有效性
     */
    public function checkToken(){
        $timestamp = $_GET['timestamp'];
        $nonce = $_GET['nonce'];
        $token = $this->config['wechat_token'] ;
        $signature = $_GET['signature'];
        $echostr = $_GET['echostr'];

        //按字典排序
        $data_array=array($timestamp,$nonce,$token);
        sort($data_array);

        //将排序后的参数进行拼接之后进行sha1加密
        $tmpstr=implode('',$data_array);
        $tmpstr=sha1($tmpstr);

        //将加密后的字符串与signature进行对比，判断该请求是否来自微信
        if($tmpstr==$signature){
            echo  $echostr;die;
        }
    }

    public function index(){
        if (isset($_GET['echostr'])){
           self::checkToken();
        }
        self::postData();
        self::message();

    }

    /**
     * 获取微信服务器发送post消息
     */
    private function postData(){
        //$postString=$GLOBALS['HTTP_RAW_POST_DATA'];
        $postString=file_get_contents('php://input', 'r');

        libxml_disable_entity_loader(true);
        $xml=simplexml_load_string($postString,'SimpleXMLElement',LIBXML_NOCDATA);
        foreach ($xml as $k=>$value){
            $this->postData[$k]=strval($value);
        }

        //file_put_contents("test.txt",$this->postData , FILE_APPEND);
    }


    public function message(){
        $message=new Message($this->config);
        //事件
        if ($this->postData['MsgType']=='event'){
            //关注
            if ($this->postData['Event']=='subscribe'){

            }
            //取消关注
            elseif ($this->postData['Event']=='unsubscribe'){

            }
        }
        //文本消息
        elseif ($this->postData['MsgType']=='text'){
            $message->response('回复文本',$this->postData,Message::MSG_TYPE_TEXT);

            /*$luckyMoneyData = [
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
            $luckyMoney=$this->luckyMoney();
            $luckyMoney->sendRedpack($luckyMoneyData);*/


        }
        //图片消息
        elseif ($this->postData['MsgType']=='image'){
            $message->response('图片媒体地址了',$this->postData,Message::MSG_TYPE_IMAGE);
        }
        //语音消息
        elseif ($this->postData['MsgType']=='voice'){
            $message->response('语音媒体地址了',$this->postData,Message::MSG_TYPE_VOICE);
        }
        //视频消息
        elseif ($this->postData['MsgType']=='video'){
            $music=[
                'Title'=>'',
                'Description'=>'',
                'MusicURL'=>'',
                'HQMusicUrl'=>'',
                'ThumbMediaId'=>'',
            ];
            $message->response($music,$this->postData,Message::MSG_TYPE_VIDEO);
        }
        //小视频消息
        elseif ($this->postData['MsgType']=='shortvideo'){
            $video=[
                'MediaId'=>'',
                'Title'=>'',
                'Description'=>''
            ];
            $message->response($video,$this->postData,Message::MSG_TYPE_MUSIC);

        }

        //地理位置消息
        elseif ($this->postData['MsgType']=='location'){
            $news=[
                [
                    'Title'=>'标题1',
                    'Description'=>'描述1',
                    'PicUrl'=>'http://img.zcool.cn/community/0142135541fe180000019ae9b8cf86.jpg@1280w_1l_2o_100sh.png',
                    'Url'=>'http://hao123.com'
                ],
                [
                    'Title'=>'标题2',
                    'Description'=>'描述2',
                    'PicUrl'=>'http://pic71.nipic.com/file/20150610/13549908_104823135000_2.jpg',
                    'Url'=>'http://baidu.com'
                ]
            ];
            $message->response($news,$this->postData,Message::MSG_TYPE_NEWS);
        }
        //链接消息
        elseif ($this->postData['MsgType']=='link'){

        }
        //小视频消息
        elseif ($this->postData['MsgType']=='shortvideo'){

        }

    }

    public function accessToken(){
        return new AccessToken($this->config);
    }

    public function menu(){
        return new Menu($this->config);
    }

    public function oauth(){
        return new Oauth($this->config);
    }

    public function luckyMoney(){
        return new LuckyMoney($this->config);
    }

}
<?php
/**
 * @function：接口统一层。微信公众平台调用本接口，调试也调用本接口
 */
namespace Org\Wechat;

use Org\Net\Curl;
use Org\Wechat\Message;
use Org\Wechat\Response;
use Org\Wechat\AccessToken;

/**
 * 微信公众账号的接口层，微信响应调用该接口，调试也调用此接口
 */
class Api{
	public $sendCount = 0;
    public $msg = null;
    public $access = null;
    private $res = null;
    //构造函数
    function __construct($postStr = null) {
        $this->msg = new Message($postStr);
        $this->access = new AccessToken();
        $this->res = new Response();
    }
    
    /**
     * 文本消息
     */
    public function sendText($fromUsername,$toUsername,$keyword){
    	$this->sendCount+=1;
    	return $this->res->text($fromUsername, $toUsername, $keyword);
    }
    
	/**
	 * 图文消息
	 */
    public function sendNews($fromUsername,$toUsername, $tuwenList){
    	$this->sendCount+=1;
    	return $this->res->news($fromUsername, $toUsername, $tuwenList);
    }
    
    /**
     * 发送客服文本
     */
    public function sendCustomerText($toUsername, $content){
    	$this->sendCount+=1;
    	$token = $this->access->getAccessToken();
    	$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=".$token;
    	$param = '{"touser":"'.$toUsername.'","msgtype":"text","text":{"content":"'.$content.'"}}';
        return Curl::callWebServer($url, $param, 'post', true, false);
    }
    
    /**
     * 发送客服图文
     */
    public function sendCustomerNews($toUsername, $tuwenList){
    	$this->sendCount+=1;
    	$token = $this->access->getAccessToken();
    	$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=".$token;
    	$header= '{"touser":"'.$toUsername.'","msgtype":"news","news":{"articles":[%s]}}';
    	$item  = '{"title":"%s","description":"%s","picurl":"%s","url":"%s"}';
    	$newList = array_slice($tuwenList,0,10);
    	$tmp = "";
    	foreach ($newList as $obj) {
    		$format = sprintf($item, $obj['title'],$obj['description'],$obj['picurl'],$obj['url']);
    		$tmp .= empty($tmp)?$format:",".$format;
    	}
    	$param = sprintf($header, $tmp);
    	return Curl::callWebServer($url, $param, 'post', true, false);
    }
    
    public function findUserOpenid($code){
    	$data = $this->access->getAccessTokenByCode($code);
    	return isset($data['openid'])?$data['openid']:"";
    }
    
    public function findUserinfo($code){
		$data = $this->access->getAccessTokenByCode($code);
		$token = isset($data['access_token'])?$data['access_token']:"";
		$openid = isset($data['openid'])?$data['openid']:"";
		$url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$token.'&openid='.$openid;
        $data = Curl::callWebServer($url, '','get');
        return $data;
    }
    
    public function getUserinfo($openid){
    	$token = $this->access->getAccessToken();
		$url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$token.'&openid='.$openid;
        $data = Curl::callWebServer($url, '','get');
        return $data;
    }
}
<?php
namespace Org\Wechat;

class Response{
	/*文字*/
	public function text($fromUsername,$toUsername,$contentStr){
		$textTpl = 
			"<xml>
	         <ToUserName><![CDATA[%s]]></ToUserName>
	         <FromUserName><![CDATA[%s]]></FromUserName>
	         <CreateTime>%s</CreateTime>
	         <MsgType><![CDATA[%s]]></MsgType>
	         <Content><![CDATA[%s]]></Content>
	         <FuncFlag>0</FuncFlag>
	         </xml>";
		return sprintf($textTpl,$fromUsername, $toUsername,time(), "text", $contentStr);
	}
	/*图片*/
	public function image($fromusername, $tousername, $mediaId){
    	$imgeTpl = 
    		"<xml>
			<ToUserName><![CDATA[%s]]></ToUserName>
			<FromUserName><![CDATA[%s]]></FromUserName>
			<CreateTime>%s</CreateTime>
			<MsgType><![CDATA[%s]]></MsgType>
			<Image>
			<MediaId><![CDATA[%s]]></MediaId>
			</Image>
			</xml>";
		return sprintf($imgeTpl,$fromusername,$tousername,time(),"image",$mediaId);
	}
	/*语音*/
	public function voice($fromusername,$tousername,$mediaId){
		$voiceTpl = 
			"<xml>
			<ToUserName><![CDATA[%s]]></ToUserName>
			<FromUserName><![CDATA[%s]]></FromUserName>
			<CreateTime>%s</CreateTime>
			<MsgType><![CDATA[%s]]></MsgType>
			<Voice>
			<MediaId><![CDATA[%s]]></MediaId>
			</Voice>
			</xml>";
		return sprintf($voiceTpl,$fromusername,$tousername,time(),"voice",$mediaId);
	}
	/*视频*/
	public function video($fromusername, $tousername, $mediaId, $title, $description){
			$videoTpl = 
			"<xml>
			<ToUserName><![CDATA[%s]]></ToUserName>
			<FromUserName><![CDATA[%s]]></FromUserName>
			<CreateTime>%s</CreateTime>
			<MsgType><![CDATA[%s]]></MsgType>
			<Video>
			<MediaId><![CDATA[%s]]></MediaId>
			<Title><![CDATA[%s]]></Title>
			<Description><![CDATA[%s]]></Description>
			</Video> 
			</xml>";
		return sprintf($videoTpl,$fromusername,$tousername,time(),"video",$mediaId,$title,$description);
	}
	/*音乐*/
	public function music($fromusername, $tousername,$title,$description,$url,$hgurl,$mediaId){
			$musicTpl = "<xml>
			<ToUserName><![CDATA[%s]]></ToUserName>
			<FromUserName><![CDATA[%s]]></FromUserName>
			<CreateTime>%s</CreateTime>
			<MsgType><![CDATA[%s]]></MsgType>
			<Music>
			<Title><![CDATA[%s]]></Title>
			<Description><![CDATA[%s]]></Description>
			<MusicUrl><![CDATA[%s]]></MusicUrl>
			<HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
			<ThumbMediaId><![CDATA[%s]]></ThumbMediaId>
			</Music>
			</xml>";
		return sprintf($musicTpl,$fromusername,$tousername,time(),"music",$title,$description,$url,$hgurl,$mediaId);
	}
	/*图文信息*/
	public function news($fromusername, $tousername, $itemList){
		$header = "<xml>
			<ToUserName><![CDATA[%s]]></ToUserName>
			<FromUserName><![CDATA[%s]]></FromUserName>
			<CreateTime>%s</CreateTime>
			<MsgType><![CDATA[%s]]></MsgType>
			<ArticleCount>%s</ArticleCount>
			<Articles>";
		$item = "<item>
			<Title><![CDATA[%s]]></Title>
			<Description><![CDATA[%s]]></Description>
			<PicUrl><![CDATA[%s]]></PicUrl>
			<Url><![CDATA[%s]]></Url>
			</item>";
		$footer = "
			</Articles>
			</xml>";
		$count = count($itemList);//获取数组长度
		if($count>10)
			$count = 10;
		$tmp = "";
		for($i = 0 ;$i<$count; $i++){
			$tmp .=sprintf($item,$itemList[$i]['title'],$itemList[$i]['description'],$itemList[$i]['pic_url'],$itemList[$i]['url']);
		}
		return sprintf($header,$fromusername,$tousername,time(),"news",$count).$tmp.$footer;
	}
}
?>
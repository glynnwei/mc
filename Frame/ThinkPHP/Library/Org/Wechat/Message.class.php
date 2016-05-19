<?php
namespace Org\Wechat;

class Message{
	public $msgTyple = null;//数据类型
	public $fromUsername = null;//open_id
	public $toUsername = null;//微信公众账号
	public $event_type = null;//事件类型
	public $event_key = null;//事件内容
	//定时上报的地理位置
	public $lat = null;
	public $lng = null;
	//用户通过输入框发送的地理位置
	public $lat_x = null;
	public $lng_y = null;
	public $keyword = null;//数据内容
	public $PicUrl = null;//
	public $MediaId = null;
	public $Format = null;//语音类型
	public $ThumbMediaId = null;//视频的缩略图
	public $Scale = null;//地图缩放大小
	public $Label = null;//地图位置信息
	public $Recognition = null;//语音识别结果
	
	//构造函数
	function __construct($postStr){
			//接收数据处理
			$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
			
			$this->fromUsername = $postObj->FromUserName;
            $this->toUsername = $postObj->ToUserName;
            $this->msgTyple = $postObj->MsgType;
            $this->event_type = $postObj->Event;
            $this->event_key = $postObj->EventKey;
            $this->lat_x =(float)$postObj->Location_X;
            $this->lng_y =(float)$postObj->Location_Y;
            $this->lat = (float)$postObj->Latitude;
            $this->lng = (float)$postObj->Longitude;
            $this->keyword = trim($postObj->Content); 
            $this->PicUrl = trim($postObj->PicUrl);
            $this->MediaId = $postObj->MediaId;
            $this->Recognition = trim($postObj->Recognition);
            $this->ThumbMediaId = $postObj->ThumbMediaId;
            $this->Scale = $postObj->Scale;
            $this->Label = $postObj->Label;
	}
}
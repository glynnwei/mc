<?php
namespace Org\Wechat;
use Org\Net\Curl;

class AccessToken{
	
	/**
	 * 获取APP的access_token
	 */
	public function getAccessToken(){
		//从数据库中获取
		$token = F("apptoken");
		//如果为空则从服务器端重新获取
		if($token == null||$token==false){
			$appid = $this->getAppid();
			if($appid == null)
				return null;
			$appsecret = $this->getAppSecret();
			if($appsecret == null)
				return null;
			$token = $this->getTokenByUrl($appid,$appsecret);
			F("apptoken",$token);
		//如果数据库中有数据则验证是否有效
		}else{
			if(!$this->checkToken($token)){//如果已经过期了则重新去服务器端重新获取
				$appid = $this->getAppid();
				if($appid == null)
					return null;
				$appsecret = $this->getAppSecret();
				if($appsecret == null)
					return null;
				$token = $this->getTokenByUrl($appid,$appsecret);
				F("apptoken",$token);				
			}
		}
        $accessToken = json_decode($token, true);
        return $accessToken["access_token"];
	}
	
	/**
	 * 通过API获取APP access_token
	 */
	private function getTokenByUrl($appid,$appsecret){
		$url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appid.'&secret='.$appsecret;
		$accessToken = Curl::callWebServer($url, '','GET');
		$accessToken['time'] = time();
        $accessTokenJson = json_encode($accessToken);
        return $accessTokenJson;
	}
	
	/**
	 * 获取APPID
	 */
	private function getAppid(){
		return C('WX_APPID');
	}
	
	/**
	 * 获取APPsecret
	 */
	private function getAppSecret(){
        return C('WX_APP_SECRET');
	}
	
	/**
	 * 检测token是否有效
	 * $token 是json格式
	 */
	private function checkToken($token){
		$accessToken = json_decode($token, true);
		if(time() - $accessToken['time'] < $accessToken['expires_in']-100)
			return true;
		else
			return false;
	}
	
	public function getAccessTokenByCode($code){
		//从数据库中获取
		$appid = $this->getAppid();
		$appsecret = $this->getAppSecret();
		if($appid == null || $appsecret == null || $code == null)
				return null;
		$url = 'https://api.weixin.qq.com/sns/oauth2/access_token?grant_type=authorization_code&appid='.$appid.'&secret='.$appsecret.'&code='.$code;
		$data = Curl::callWebServer($url, '','GET');
        return $data;
	}
}
?>
<?php
function cstrlen($str)  {
	$strlen = strlen($str);
    $aStrlen = 0;
    for($i=0;$i<$strlen;$i++)  {
		if(ord(substr($str,$i,1))>0xa0)  {
			$aStrlen += 2;
			$i = $i + 2;
		}  else
			$aStrlen += 1;
   }
   return  $aStrlen;
}
function csubstr($str,$start,$len)  {
       $strlen=$start+$len;
       for($i=0;$i<$strlen;$i++)  {
               if(ord(substr($str,$i,1))>0xa0)  {
                       $tmpstr.=substr($str,$i,2);
                       $i++;
               }  else
                       $tmpstr.=substr($str,$i,1);
       }
       return  $tmpstr;
}

function escape_string($str){
	if (get_magic_quotes_gpc()){
		return $str;
	}else{
		return mysql_escape_string($str);
	}
}
function array_dump($array){
	$return = "";
	foreach($array as $k => $v){
		if (is_array($v)){
			$return .= "[".$k."]=>{\n";
			$return .= array_dump($v)."\n";
			$return .= "}\n";
		}else{
			$return .= $k." => ".$v."\n";
		}
	}
	return $return;
}
/**
 * 获取当前微秒数
 */
function get_microtime(){
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}
/**
 * 获取当前毫秒数
 */
function get_msectime(){
	list($usec, $sec) = explode(" ", microtime());
    return (float)(str_pad($sec."".round($usec*1000,0),13,'0',STR_PAD_RIGHT));
}
/**
 * 二维数组求和，制定列
 */
function col_sum($array,$col){
  $sum=0;
  if(is_array($array) && count($array)>0){
  	foreach($array as $key=>$val){
    	$sum += $val[$col];
  	}
  }
  return $sum;
}

function int2float($value){
	return (float)$value;
}

/**
 * 获取客户端ip
 *
 * @return string
function get_client_ip() {
	$user_ip = "";
	if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
		$user_ip = getenv('HTTP_CLIENT_IP');
	} elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
		$user_ip = getenv('HTTP_X_FORWARDED_FOR');
	} elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
		$user_ip = getenv('REMOTE_ADDR');
	} elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
		$user_ip = $_SERVER['REMOTE_ADDR'];
	}
	return $user_ip;
}
**/
/**
 * 
 */
function is_wap() {  
    if (isset($_SERVER['HTTP_VIA'])) return true;  
    if (isset($_SERVER['HTTP_X_NOKIA_CONNECTION_MODE'])) return true;  
    if (isset($_SERVER['HTTP_X_UP_CALLING_LINE_ID'])) return true;  
    if (strpos(strtoupper($_SERVER['HTTP_ACCEPT']),"VND.WAP.WML") > 0) {  
        // Check whether the browser/gateway says it accepts WML.  
        $br = "WML";  
    } else {  
        $browser = isset($_SERVER['HTTP_USER_AGENT']) ? trim($_SERVER['HTTP_USER_AGENT']) : '';  
        if(empty($browser)) return true;
        $mobile_os_list=array('Google Wireless Transcoder','Windows CE','WindowsCE','Symbian','Android','armv6l','armv5','Mobile','CentOS','mowser','AvantGo','Opera Mobi','J2ME/MIDP','Smartphone','Go.Web','Palm','iPAQ');  
              
        $mobile_token_list=array('Profile/MIDP','Configuration/CLDC-','160×160','176×220','240×240','240×320','320×240','UP.Browser','UP.Link','SymbianOS','PalmOS','PocketPC','SonyEricsson','Nokia','BlackBerry','Vodafone','BenQ','Novarra-Vision','Iris','NetFront','HTC_','Xda_','SAMSUNG-SGH','Wapaka','DoCoMo','iPhone','iPod');  
              
        $found_mobile=checkSubstrs($mobile_os_list,$browser) ||  
                  checkSubstrs($mobile_token_list,$browser); 
    if($found_mobile)
      $br ="WML";
    else $br = "WWW";
    }  
    if($br == "WML") {  
        return true;  
    } else {  
        return false;  
    }
}

function checkSubstrs($list,$str){
  $flag = false;
  for($i=0;$i<count($list);$i++){
    if(strpos($str,$list[$i]) > 0){
      $flag = true;
      break;
    }
  }
  return $flag;
}

function get_double_hex_key($id) {
	$num = intval($id);
	if($num <= 0)
		return "t";
	$rand_str	= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
	$dec    = strlen($rand_str);

	$str = "";
	while(true) {
		if($num <= $dec) {
			if($num > 0)
				$str = $rand_str[$num - 1] . $str;
			break;
		} else {
			$remainder = $num % $dec;
			if($remainder > 0)
				$str = $rand_str[$remainder - 1] . $str;
			else
				$str = "0" . $str;

			$num = floor($num / $dec);
		}
	}
	return $str;
}

function output($raw_data,$cmd1,$cmd2){
	$len = strlen($raw_data) + strlen(pack("CC",0,0));
	//$len = 0x80000000 | $len;
	//echo "raw_data:".$raw_data."len:".$len;exit;
	$output = pack("NCC",$len, $cmd1, $cmd2).$raw_data;
	/*
	if($cmd1 == 6 && $cmd2 == 18) {
		$arr = unpack("Nint", $raw_data);
		wlog("newer",json_encode($arr));
	}
	*/
	return $output;
}

/*number(0-100)*/
function cal_odds($number)
{
	$odds_figure = 1000000;
	if($number >= 100) {
		return true;
	} else if($number <= 0) {
		return false;
	}

	$iRand		= floor(rand($odds_figure,100*$odds_figure)/$odds_figure);

	if($iRand <= $number) {
		return true;
	} else {
		return false;
	}
}
function cal_rand($per = 100)
{
	return rand(0,$per);
	/*
	$odds_figure = 1000000;

	$iRand = floor(rand($odds_figure,$per*$odds_figure)/$odds_figure);

	return $iRand;
	*/
}
function pt(){
	$parm = func_get_args();
	if(!empty($parm)){
		echo '<pre>';
		foreach ($parm as $value){
			print_r($value);
			echo '<br />';
		}
		die();
	}
	echo 'parm empty!';
}

//$number 将要向上取的数,div向上取的数值
function ceilUp($number,$div){
	if(is_numeric($number) && is_numeric($div)){
	   return ceil($number/$div)*$div;
	}else{
		return false;
	}
}

function get_option($idx=-1){
	$arrData=null;
	if($idx == -1){
		$arrData = array();
	}elseif($idx == 0){
		$arrData = session("soption");
	}elseif(session("funOption") != null && $idx>0){
		$data = session("funOption");
		session("soption", $arrData = $data[$idx]);
	}
	return $arrData;
}

// 写入缓存
function cache_fw($fname,$cachedata = '',$fsuffix = '.php',$prefix = 'cache_') {
		$dir = FILE_CACHE_PATH."/";
		if(!is_dir($dir)) {
			@mkdir($dir, 0777);
		}
		if($fp = @fopen("$dir$prefix$fname$fsuffix", 'wb')) {
			if($fsuffix == ".php"){
				fwrite($fp, "<?php\n$cachedata?>");
			}else{
				fwrite($fp, $cachedata);
			}
			fclose($fp);
			return true;
		} else {
			return false;
		}
}

// 读取缓存
function cache_fr($fname,$fsuffix = '.php',$prefix = 'cache_') {
	$dir = FILE_CACHE_PATH."/";
	$file= "$dir$prefix$fname$fsuffix";
	if($fsuffix!=".php"){
		$str = @file_get_contents($file);
		return $str;
	}
	return @require_once($file);
}

/**
 * 获取目录下文件列表
 */

//获取文件目录列表,该方法返回数组
function get_dir_files($dir) {
	$dirArray[]=NULL;
	if (false != ($handle = opendir ( $dir ))) {
		$i=0;
		while ( false !== ($file = readdir ( $handle )) ) {
			if ($file != "." && $file != ".." && strpos($file,".")) {
				if($arrInfo = explode('.',$file)){
					$dirArray[$i]=$arrInfo[0];
					$i++;
				}
			}
		}
		//关闭句柄
		closedir ( $handle );
	}
	return $dirArray;
}

/**
 * http and https
 */
function http_curl_exec($url,$is_ssl = false,$arrData = array(),$isPost=true,$timeout = 300){
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_POSTFIELDS,$arrData);
	curl_setopt($curl, CURLOPT_TIMEOUT,$timeout);
	if($is_ssl){
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	}
	if($isPost){curl_setopt($curl, CURLOPT_POST,1);}

	$result= curl_exec($curl);
	$code  = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	curl_close($curl);
	$result = $code!=200?"":$result;
	return array('code'=>$code,'data'=>$result);
}

function download_page($path){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$path);
	curl_setopt($ch, CURLOPT_FAILONERROR,1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 15);
	$result = curl_exec($ch);
	$code  	= curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);
	$result = $code!=200?"":$result;
	return array('code'=>$code,'data'=>$result);
}

function is_json_decode($str) {
	if($returValue = @json_decode($str,"array")){
		return $returValue;
	}else{
		return false;
	}
//	PHP version>=5.3
//	if(json_last_error() != JSON_ERROR_NONE){
//		$returValue = false;
//	}
//	或者	preg_match("^\{(.+:.+,*){1,}\}$",$str)
}


/**
 * 处理写Log
 */
function wlog($value){
	$dir = SYS_LOG_PATH."/wlog";
	if(!is_dir($dir)) {@mkdir($dir, 0777);}
	if($fp = @fopen($dir."/".date('Ymd').".log", "a+")) {
		fwrite($fp, "[".date('Y-m-d H:i:s')."]：".(json_encode($value))."\n");
		fclose($fp);
		return true;
	} else {
		return false;
	}
}

// 获取当前访问的完整url地址
function GetCurUrl() {
	$url = 'http://';
	if (isset ( $_SERVER ['HTTPS'] ) && $_SERVER ['HTTPS'] == 'on') {
		$url = 'https://';
	}
	if ($_SERVER ['SERVER_PORT'] != '80') {
		$url .= $_SERVER ['HTTP_HOST'] . ':' . $_SERVER ['SERVER_PORT'] . $_SERVER ['REQUEST_URI'];
	} else {
		$url .= $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'];
	}
	// 兼容后面的参数组装
	if (stripos ( $url, '?' ) === false) {
		$url .= '?t=' . time ();
	}
	return $url;
}

function load_permission_func(){
	if(session("rid") == null){
		return ;
	}
	$pList = M('Permission')->where(array('rid'=>session("rid")))->select();
	if(empty($pList)){
		return ;
	}
	$sqlIn = '0';
	foreach($pList as $v){
		$sqlIn.=','.$v['fid'];
	}
	$arrFun = M('Func')->where("id in ($sqlIn)")->order('parent_id')->select();
	//tree
	if($arrFun){
		$tree = array();
		foreach ( $arrFun as $model ) {
		     if($model['parent_id'] == 0){
		     	$tree[$model['id']] = array('caption'=> $model['caption'], 'child'=> array());
		     }else{
		     	$tree[$model['parent_id']]['child'][] = $model; 
		     }
		}
		session("treeData", $tree);
	}
}

?>
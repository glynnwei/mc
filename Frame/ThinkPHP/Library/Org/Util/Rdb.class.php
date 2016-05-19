<?php
namespace Org\Util;

class Rdb{
	
	protected $redis  = null;
	protected $handle = null;
	private $host	  = "";
	private $port	  = "";
	private $timeout  = 0;
	private $dbname	  = "";
	private $pconnect = false;
	public $bRdDebug  = false;
	public $rdDebug   = "";

	public function  __construct( $host = '127.0.0.1', $port = 6379 , $dbname, $timeout = 0,$pconnect = false)
	{
		$this->host		= $host;
		$this->port		= $port;
		$this->dbname	= $dbname;
		$this->timeout	= $timeout;
		$this->pconnect	= $pconnect;
		$this->redis	= new \Redis;
	}
	
	private function connect() 
	{
		if(!is_resource($this->handle)) {
			if($this->pconnect) {
				if(!$this->redis->pconnect($this->host,$this->port)) {
					return false;
				}
			} else {
				if(!$this->redis->connect($this->host,$this->port)) {
					return false;
				}
			}
			$this->handle = & $this->redis->socket;

			if(!$this->redis->select($this->dbname))
				return false;
		}
		return $this->handle;
	}
	private function rd_debug($action,$str) 
	{
		if($this->bRdDebug) {
			if(is_array($str))
				$str = json_encode($str);
			$this->rdDebug .= $action.":".$str . "\n";
		}
	}
	public function watch($key)
	{
		if(!$this->connect()) return false;

		$this->rd_debug("watch",$key);

		if($this->redis->watch($key))
			return true;
		return false;
	}
	public function multi()
	{
		if(!$this->connect()) return false;

		$this->rd_debug("multi","");

		if($this->redis->multi())
			return true;
		return false;
	}
	public function exec()
	{
		if(!$this->connect()) return false;

		$this->rd_debug("exec","");

		if($this->redis->exec())
			return true;
		return false;
	}
	public function discard()
	{
		if(!$this->connect()) return false;

		$this->rd_debug("discard","");

		if($this->redis->discard())
			return true;
		return false;
	}
	public function rename($old, $key){
		if(!$this->connect()) return false;

		$this->rd_debug("rename","");

		if($this->redis->rename($old, $key))
			return true;
		return false;		
	}
/**
 * @param string $key
 * @param mixed $value
 */
	public function set( $key, $value)
	{
		if(!$this->connect()) return false;
		
		$this->rd_debug("set",$key.":".$value);
		//对数组/对象数据进行缓存处理，保证数据完整性
        $value  =  (is_object($value) || is_array($value)) ? json_encode($value) : $value;
        if($this->redis->set($key, $value))
			return true;
		return false;
	}
	public function mset($arrData)
	{
		if(!$this->connect()) return false;
		
		$this->rd_debug("mset",$arrData);

		if($this->redis->mset($arrData))
			return true;
		return false;
	}
	public function getMultiple($arrKeys)
	{
		if(!$this->connect()) return false;
		
		if(empty($arrKeys))
			return array();

		$this->rd_debug("getMultiple",$arrKeys);

		return $this->redis->getMultiple($arrKeys);
	}
	
/**
 * @param string $key
 * @return mixed
 */
	public function get( $key )
	{
		if(!$this->connect()) return false;

		$this->rd_debug("get",$key);
		$value = $this->redis->get($key);
		$jsonData  = json_decode( $value, true );
        return ($jsonData === NULL) ? $value : $jsonData;	//检测是否为JSON数据 true 返回JSON解析数组, false返回源数据
	}

/**
 * @param mix $key string or array
 */
	public function delete( $key )
	{
		if(!$this->connect()) return false;

		$this->rd_debug("del",$key);

		return $this->redis->delete($key);
	}
	public function keys( $key )
	{
		if(!$this->connect()) return false;

		$this->rd_debug("keys",$key);

		return $this->redis->keys($key);
	}
	public function sadd($key,$value)
	{
		if(!$this->connect()) return false;

		$this->rd_debug("sadd",$key.":".$value);

		return $this->redis->sadd($key,$value);
	}
	public function srem($key,$value)
	{
		if(!$this->connect()) return false;

		$this->rd_debug("sRem",$key.":".$value);

		return $this->redis->sRem($key,$value);
	}
	public function ssize($key)
	{
		if(!$this->connect()) return false;

		$this->rd_debug("sSize",$key);

		return $this->redis->sSize($key);
	}
	public function ssort($key,$arrOrder)
	{
		if(!$this->connect()) return false;

		$this->rd_debug("ssort",$key.":".json_encode($arrOrder));

		return $this->redis->sort($key,$arrOrder);
	}
	public function smembers($key)
	{
		if(!$this->connect()) return false;

		$this->rd_debug("smembers",$key);

		return $this->redis->smembers($key);
	}
	public function expire($key,$expiretime)
	{
		if(!$this->connect()) return false;

		$this->rd_debug("expire",$key.":".$expiretime);

		return $this->redis->expire($key,$expiretime);
	}
	
	public function sIsMember($key,$value){
		if(!$this->connect()) return false;

		$this->rd_debug("expire",$key.":".$value);

		return $this->redis->sIsMember($key,$value);
	}
/**
 * @param string $key
 * @return boolean
 */
	public function exists( $key )
	{
		if(!$this->connect()) return false;

		$this->rd_debug("exists",$key);

		return $this->redis->exists($key);
	}
	public function incr($key)
	{
		if(!$this->connect()) return false;
		$this->rd_debug("incr",$key);

		return $this->redis->incr($key);
	}
	public function incrBy($key,$num)
	{
		if(!$this->connect()) return false;
		$this->rd_debug("incrBy",$key.":".$num);

		return $this->redis->incrBy($key,intval($num));
	}
	public function hSet($key,$field,$value)
	{
		if(!$this->connect()) return false;

		$this->rd_debug("hset",$key.":".$field.":".$value);

		//if($this->redis->hset($key,$field,$value))
		if($this->redis->hMset($key,array($field=>$value)))
			return true;
		return false;
	}
	public function hMset($key,$arrField) 
	{
		if(!$this->connect()) return false;

		$this->rd_debug("hMset",$key.json_encode($arrField));

		if($this->redis->hMset($key,$arrField))
			return true;
		return false;
	}
	public function hget($key,$field)
	{
		if(!$this->connect()) return false;

		$this->rd_debug("hget",$key.":".$field);

		return $this->redis->hget($key,$field);
	}
	public function hMGet($key,$arrField)
	{
		if(!$this->connect()) return false;

		$this->rd_debug("hMGet",$key.":".json_encode($arrField));

		return $this->redis->hMGet($key,$arrField);
	}
	public function hDel($key,$field)
	{
		if(!$this->connect()) return false;

		$this->rd_debug("hDel",$key.":".$field);

		return $this->redis->hDel($key,$field);
	}
	public function hgetall($key)
	{
		if(!$this->connect()) return false;

		$this->rd_debug("hgetall",$key);

		return $this->redis->hgetall($key);
	}
	public function hIncrBy($key,$field,$num = 1)
	{
		if(!$this->connect()) return false;

		$this->rd_debug("hIncrBy",$key.":".$field.":".$num);

		return $this->redis->hIncrBy($key,$field,intval($num));
	}
	public function hKeys($key)
	{
		if(!$this->connect()) return false;

		$this->rd_debug("hKeys",$key);

		return $this->redis->hKeys($key);
	}
	public function hVals($key)
	{
		if(!$this->connect()) return false;

		$this->rd_debug("hVals",$key);

		return $this->redis->hVals($key);
	}
/*------------------------[ hash ]----------------------[ begin ]---*/
	public function push($key,$value,$mode = "right")
	{
		if(!$this->connect()) return false;
		if($mode == "right") {
			$this->rd_debug("lPush",$key.":".$value);

			return $this->redis->rPush($key,$value);
		} else {
			$this->rd_debug("lPush",$key.":".$value);

			return $this->redis->lPush($key,$value);
		}
	}
	public function pushx($key,$value,$mode = "right")
	{
		if(!$this->connect()) return false;
		if($mode == "right") {
			$this->rd_debug("rPushx",$key.":".$value);

			return $this->redis->rPushx($key,$value);
		} else {
			$this->rd_debug("lPushx",$key.":".$value);
//echo $key.":".$value;
			return $this->redis->lPushx($key,$value);
		}
	}
	public function lRange($key,$start = 0,$limit = -1)
	{
		if(!$this->connect()) return false;

		$this->rd_debug("lRange",$key.":".$start.":".$limit);

		$end = ($limit > 0) ? ($start + $limit - 1) : $limit;

		return $this->redis->lRange($key,$start,$end);
	}
	public function lRemove($key,$value,$num)
	{
		if(!$this->connect()) return false;

		$this->rd_debug("lRemove",$key.":".$value.":".$num);

		return $this->redis->lRemove($key,$value,$num);
	}
	public function lSize($key)
	{
		if(!$this->connect()) return false;

		$this->rd_debug("lSize",$key);

		return $this->redis->lSize($key);
	}
	//弹出list头部元素
	public function lPop($key){
		if(!$this->connect()) return false;

		$this->rd_debug("lPop",$key);

		return $this->redis->lPop($key);
	}
	//保留指定key内的元素移除非指定元素
	public function lTrim($key, $start=0, $end=-1){
		if(!$this->connect()) return false;

		$this->rd_debug("lTrim",$key);
		
		return $this->redis->lTrim($key, $start, $end);
	}
	public function lInster($key,$mode = "BEFORE",$bound,$value){
		if(!$this->connect()) return false;
        if($mode =="BEFORE"){
		    $this->rd_debug("lInster before",$key.":".$mode.":".$bound.":".$value);

		    return $this->redis->LINSERT($key,$mode,$bound,$value);
		}else{
			$this->rd_debug("lInster after",$key.":".$mode.":".$bound.":".$value);

		    return $this->redis->LINSERT($key,$mode,$bound,$value);
		}
	}
	public function lIndex($key,$index){
		if(!$this->connect()) return false;

		$this->rd_debug("lIndex",$key.":".$index);

		return $this->redis->lIndex($key,$index);
	}
/*------------------------[ hash ]----------------------[ end ]---*/

/*------------------------[ zset ]----------------------[ begin ]---*/

/****
 * zAdd
 * key		string
 * score	int
 * member   string
 *
****/
	public function zAdd($key,$score,$member)
	{
		if(!$this->connect()) return false;

		$this->rd_debug("zAdd",$key.":".$score.":".$member);

		return $this->redis->zAdd($key,$score,$member);
	}
	public function zRange($key,$start,$limit,$withscores)
	{
		if(!$this->connect()) return false;

		$this->rd_debug("zRange",$key.":".$start.":".$limit.":".$withscores);
		
		$end = ($limit > 0) ? ($start + $limit - 1) : $limit;

		return $this->redis->zRange($key,$start,$end,$withscores);
	}
	public function zRangeByScore($key,$start,$end){
		if(!$this->connect()) return false;
		
		$this->rd_debug("zRangeByScore",$key.":".$start.":".$end);
		
		return $this->redis->zRangeByScore($key,$start,$end);
	}
	public function zDelete($key,$member)
	{
		if(!$this->connect()) return false;

		$this->rd_debug("zDelete",$key.$member);

		return $this->redis->zDelete($key,$member);
	}
	public function zRevRange($key,$start,$limit,$withscores)
	{
		if(!$this->connect()) return false;

		$this->rd_debug("zRevRange",$key.":".$start.":".$limit.":".$withscores);
		
		$end = ($limit > 0) ? ($start + $limit - 1) : $limit;

		return $this->redis->zRevRange($key,$start,$end,$withscores);
	}
	public function zCount($key,$start,$end)
	{
		if(!$this->connect()) return false;

		$this->rd_debug("zCount",$key.":".$start.":".$end);

		return $this->redis->zCount($key,$start,$end);
	}
	public function zSize($key)
	{
		if(!$this->connect()) return false;

		$this->rd_debug("zSize",$key);

		return $this->redis->zSize($key);
	}
	public function zScore($key,$val)
	{
		if(!$this->connect()) return false;

		$this->rd_debug("zScore",$key.":".$val);

		return $this->redis->zScore($key,$val);
	}
	/*
	 *order ASC:升序,DESC:降序
	 *
	 */
	public function zRank($key,$val,$order = "ASC")
	{
		if(!$this->connect()) return false;

		$this->rd_debug("zRank",$key.":".$val.":".$order);
		if($order == "ASC") {
			return $this->redis->zRank($key,$val);
		} else {
			return $this->redis->zRevRank($key,$val);
		}
	}
	public function zIncrBy($key,$increment,$member)
	{
		if(!$this->connect()) return false;

		$this->rd_debug("zIncrBy",$key.":".$increment.":".$member);

		return $this->redis->zIncrBy($key,$increment,$member);
	}
/*------------------------[ zset ]----------------------[ end ]---*/

	public function close() 
	{
		if(is_resource($this->handle)) 
		{
			$this->redis->close();
		}

		unset($this->redis);
	}
	
	public function clear() {
		if(!$this->connect()) return false;
		
        return $this->redis->flushDB();
    }
	
    public function rm($key) {
    	if(!$this->connect()) return false;
    	
        return $this->redis->delete($key);
    }
	
}
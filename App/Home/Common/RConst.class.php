<?php
namespace Home\Common;

class RConst {
	
	const EDU_QUEUE_KEY = "_apply_edu_list_";
	const EDU_SETS_KEY  = "_apply_edu_set_";
	
	const BOOK_QUEUE_KEY= "_apply_book_list_";
	const BOOK_SETS_KEY = "_apply_book_set_";
	
	const EVENT_ID		= "_event_id";
	
	public static function eventKey($event_id){
		return "event:$event_id";
	}
}
?>
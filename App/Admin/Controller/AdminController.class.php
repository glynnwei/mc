<?php
namespace Admin\Controller;
use Think\Controller;

class AdminController extends Controller {
	
	/**
	 * 初始化入口校验
	 */
	protected function _initialize(){
		if(session(C('USER_AUTH_KEY')) == null){
			$this->redirect('Public/login');
		}
	}
	
	/* 空操作，用于输出404页面 */
	public function _empty(){
		$this->redirect('Index/index');
	}
}

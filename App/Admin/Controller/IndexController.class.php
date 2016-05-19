<?php
namespace Admin\Controller;

class IndexController extends AdminController {
	
	public function index(){
		$this->display();
	}
	
	/**
	 * 初始化入口校验
	 */
	protected function _initialize(){
		if(session(C('USER_AUTH_KEY')) == null){
			$this->redirect('Public/login');
		}
	}
}
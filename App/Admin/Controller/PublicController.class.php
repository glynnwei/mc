<?php
namespace Admin\Controller;
use Think\Controller;

class PublicController extends Controller {

	public function login() {
		if (session(C('USER_AUTH_KEY')) == null) {
			$this->display();
		} else {
			$this->redirect('Index/index');
		}
	}

	public function verify() {
		if (empty ($_POST['username']) || empty ($_POST['password'])) {
			$this->error('帐号及密码不能为空！');
		}
		$map = array ('username'=>$_POST['username'],'active'=>1);
		$userDao = M('User');
		$authUser= $userDao->where($map)->find();
		if (empty ($authUser)) {
			$this->error('账号不存在或者被禁用!');
		} else {
			if ($authUser['password'] != md5($_POST['password'])) {
				$this->error('密码有误！');
			}
			session(C('USER_AUTH_KEY'), $authUser['id']);
			session("uname" , $_POST['username']);
			session("rid", $authUser['rid']);
			//查找用户角色权限
			load_permission_func();			
			//更新用户登录记录
			$data = array ('id'=>$authInfo['id'],'visit_time'=>time(),'visit_ip'=>get_client_ip());
			$userDao->save($data);
			//执行跳转
			$this->assign('jumpUrl', __APP__ . '/Index/index');
			$this->success('登录成功!');
		}
	}

	public function top() {
		if (session(C('USER_AUTH_KEY')) == null) {
			$this->redirect('Public/login');
		} else {
			$info = array (
				'uname' => session("uname"),
				'logindt' => date("Y-m-d H:i")
			);
			$this->assign('obj', $info);
			$this->display();
		}
	}
	
	public function left() {
		if (session(C('USER_AUTH_KEY')) == null) {
			$this->redirect('Public/login');
		} else {
			$this->assign('list', session("treeData"));
			$this->display();
		}
	}
	
	public function footer() {
		if (session(C('USER_AUTH_KEY')) == null) {
			$this->redirect('Public/login');
		} else {
			$this->display();
		}
	}
	
	public function main() {
		if (session(C('USER_AUTH_KEY')) == null) {
			$this->redirect('Public/login');
		} else {
			$this->display();
		}
	}

	public function logout() {
		if (session(C('USER_AUTH_KEY')) != null) {
			session('[destroy]');
			$this->assign('jumpUrl', __APP__ . '/Index/index');
			$this->success('操作成功');
		} else {
			$this->error('无需再试');
		}
	}
}
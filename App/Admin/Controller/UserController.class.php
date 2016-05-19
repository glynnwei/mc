<?php
namespace Admin\Controller;

class UserController extends AdminController {
	
	public function index(){
		$idx = isset($_GET['idx'])?$_GET['idx']:0;
		$userDao = M("User");
		$keyword=$_POST['keyword'];
		$ftype=$_POST['ftype'];
		if(!empty($keyword) && !empty($ftype)){
			$where[$ftype]=array('like',$keyword.'%');
			$_SESSION['keyword']=$where;
		}else{
			if(empty($keyword) && !empty($ftype)){
				unset($_SESSION['keyword']);
			}else if(!empty($_SESSION['keyword'])){
				$where=$_SESSION['keyword'];
			}
		}
		import('ORG.Util.Page');
		$count=$userDao->where($where)->count();
		$page=new \Think\Page($count,C('PAGESIZE'));
		$show=$page->show();
		$this->assign("page",$show);
		//需要查询哪些字段
        $fields = 'id,username,active,visit_ip,rid';
		$list=$userDao->order('id')->field($fields)->where($where)->limit($page->firstRow.','.$page->listRows)->select();
		$this->assign('list',$list);
		$this->display();
	}

	public function insert(){
		$userDao = M("User");
		if($data=$userDao->create()){
			$userDao->password= md5($data['password']);
			if(false!==$userDao->add()){
				$uid=$userDao->getLastInsID();
				$this->assign('jumpUrl',__CONTROLLER__);
				$this->success('操作成功，插入数据编号为：'.$uid);
			}else{
				$this->error('操作失败：adduser'.$userDao->getDbError());
			}
		}else{
			$this->error('操作失败：数据验证( '.$userDao->getError().' )');
		}
	}

	public function update(){
		$userDao = M("User");
		if($data=$userDao->create()){
			if(!empty($data['id'])){
				$opwd = $_POST['opwd'];
				$userDao->password = empty($data['password'])?$opwd:md5($data['password']);
				if(false!==$userDao->save()){
					$this->assign('jumpUrl',__CONTROLLER__);
					$this->success('操作成功');
				}else{
					$this->error('操作失败：'.$userDao->getDbError());
				}
			}else{
				$this->error('请选择编辑用户');
			}
		}else{
			$this->error('操作失败：数据验证( '.$userDao->getError().' )');
		}
	}

	public function add(){
		$roleDao=M('Role');
		$list=$roleDao->select();
		$this->assign('rlist',$list);
		$this->display();
	}

	public function edit(){
		$id=$_GET['id'];
		if(!empty($id)){
			$userDao=M("User");
			$date=$userDao->field('id,username,password,email,active,reg_time,visit_time,visit_ip,rid')->getById($id);
			$this->assign('obj',$date);
			$roleDao=M("Role");
			$list=$roleDao->select();
			$this->assign('rlist',$list);
		}
		$this->display();
	}

	public function delete(){
		$did=$_POST['did'];
		if(!empty($did) && is_array($did)){
			$userDao=M("User");
			$id=implode(',',$did);
			if(false!==$userDao->delete($id)){
				$this->assign('jumpUrl',__CONTROLLER__);
				$this->success('操作成功');
			}else{
				$this->error('操作失败：'.$userDao->getDbError());
			}
		}else{
			$this->error('请选择删除用户');
		}
	}
}
?>
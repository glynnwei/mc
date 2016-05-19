<?php
namespace Admin\Controller;

class HostController extends AdminController {
	
	public function index(){
		$hostDao = M("Host");
		import('ORG.Util.Page');
		$count=$hostDao->count();
		$page=new \Think\Page($count,C('PAGESIZE'));
		$show=$page->show();
		$this->assign("page",$show);
		//需要查询哪些字段
        $fields = 'id,name,domain';
		$list=$hostDao->order('id')->field($fields)->where($where)->limit($page->firstRow.','.$page->listRows)->select();
		$this->assign('list',$list);
		$this->display();
	}
	
	public function add(){
		$this->display();
	}

	public function insert(){
		$hostDao = M("Host");
		if($data=$hostDao->create()){
			if(false!==$hostDao->add()){
				$this->assign('jumpUrl', __CONTROLLER__);
				$this->success('操作成功');
			}else{
				$this->error('操作失败：addHost'.$hostDao->getDbError());
			}
		}else{
			$this->error('操作失败：数据验证( '.$hostDao->getError().' )');
		}
	}

	public function update(){
		$hostDao = M("Host");
		if($data=$hostDao->create()){
			if(!empty($data['id'])){
				$id = $data['id'];
				$data['id'] = $_POST['new_id'];
				if(false!==$hostDao->where("id=$id")->data($data)->save()){
					$this->assign('jumpUrl', __CONTROLLER__);
					$this->success('操作成功');
				}else{
					$this->error('操作失败：'.$hostDao->getDbError());
				}
			}else{
				$this->error('请选择编辑主机');
			}
		}else{
			$this->error('操作失败：数据验证( '.$hostDao->getError().' )');
		}
	}


	public function edit(){
		$id=$_GET['id'];
		if(!empty($id)){
			$hostDao = M("Host");
			$date=$hostDao->field('id,name,domain')->getById($id);
			$this->assign('obj',$date);
		}
		$this->display();
	}

	public function delete(){
		$did=$_POST['did'];
		if(!empty($did) && is_array($did)){
			$hostDao = M("Host");
			$id=implode(',',$did);
			if(false!==$hostDao->delete($id)){
				$this->assign('jumpUrl', __CONTROLLER__);
				$this->success('操作成功');
			}else{
				$this->error('操作失败：'.$hostDao->getDbError());
			}
		}else{
			$this->error('请选择删除对象');
		}
	}
}
?>
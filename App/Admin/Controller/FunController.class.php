<?php
namespace Admin\Controller;

class FunController extends AdminController {
	
	public function index(){
		$idx = isset($_GET['idx'])?$_GET['idx']:0;
		$funcDao=M("Func");
		import('ORG.Util.Page');
		$where = 'parent_id = 0';
		$count=$funcDao->where($where)->count();
		$page=new \Think\Page($count,C('PAGESIZE'));
		$show=$page->show();
		$this->assign("page",$show);
		//需要查询哪些字段
        $fields = 'id,caption,node_url';
		$list=$funcDao->order('id')->field($fields)->where($where)->limit($page->firstRow.','.$page->listRows)->select();
		$this->assign('list',$list);
		$this->display();
	}

	public function listmenu(){
		$idx = isset($_GET['id'])?$_GET['id']:0;
		import('ORG.Util.Page');
		$where = "parent_id = $idx";
		$funcDao=M("Func");
		$count=$funcDao->where($where)->count();
		$page=new \Think\Page($count,C('PAGESIZE'));
		$show=$page->show();
		$this->assign("page",$show);
		//需要查询哪些字段
        $fields = 'id,caption,node_url';
		$list=$funcDao->order('id')->field($fields)->where($where)->limit($page->firstRow.','.$page->listRows)->select();
		$this->assign('list',$list);
		$this->display();
	}
	
	public function add(){
		$this->display();
	}

	public function addmenu(){
		$funcDao=M("Func");
		$fields = 'id,caption,node_url';
		$where = 'parent_id = 0';
		$list=$funcDao->order('id')->field($fields)->where($where)->select();
		$this->assign('list',$list);
		$this->display();
	}

	public function insert(){
		$funcDao=M("Func");
		if($data=$funcDao->create()){
			if(false!==$funcDao->add()){
				$autoid=$funcDao->getLastInsID();
				$this->assign('jumpUrl', __CONTROLLER__);
				$this->success('操作成功，插入数据编号为：'.$autoid);
			}else{
				$this->error('操作失败：addFun'.$funcDao->getDbError());
			}
		}else{
			$this->error('操作失败：数据验证( '.$funcDao->getError().' )');
		}
	}

	public function edit(){
		$id=$_GET['id'];
		if(!empty($id)){
			$funcDao=M("Func");
			$date=$funcDao->field('id,caption,node_url')->getById($id);
			$this->assign('obj',$date);
		}
		$this->display();
	}

	public function editmenu(){
		$id=$_GET['id'];
		if(!empty($id)){
			$funcDao=M("Func");
			$date=$funcDao->field('id,parent_id,caption,node_url')->getById($id);
			$this->assign('obj',$date);
			$list=$funcDao->order('id')->field('id,caption')->where('parent_id = 0')->select();
			$this->assign('list',$list);
		}
		$this->display();
	}

	public function update(){
		$funcDao=M("Func");
		if($data=$funcDao->create()){
			if(!empty($data['id'])){
				if(false!==$funcDao->save()){
					$this->assign('jumpUrl',__CONTROLLER__);
					$this->success('操作成功');
				}else{
					$this->error('操作失败：'.$funcDao->getDbError());
				}
			}else{
				$this->error('请选择编辑项');
			}
		}else{
			$this->error('操作失败：数据验证( '.$funcDao->getError().' )');
		}
	}

	public function delete(){
		$did=$_POST['did'];
		if(!empty($did) && is_array($did)){
			$funcDao=M("Func");
			$id=implode(',',$did);
			if(false!==$funcDao->delete($id)){
				//删除对应的二级功能
				$delSql ="DELETE FROM __TABLE__ WHERE `parent_id` in ($id);";
				$funcDao->execute($delSql);
				$this->assign('jumpUrl',__CONTROLLER__);
				$this->success('操作成功');
			}else{
				$this->error('操作失败：'.$fun->getDbError());
			}
		}else{
			$this->error('请选择删除模块');
		}
	}
}
?>
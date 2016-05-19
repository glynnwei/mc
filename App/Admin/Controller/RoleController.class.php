<?php
namespace Admin\Controller;

class RoleController extends AdminController {
	
	public function index(){
		$idx = isset($_GET['idx'])?$_GET['idx']:0;
		$roleDao = M("Role");
		import('ORG.Util.Page');
		$count=$roleDao->count();
		$page=new \Think\Page($count,C('PAGESIZE'));
		$show=$page->show();
		$this->assign("page",$show);
		//需要查询哪些字段
        $fields = 'id,name,note';
		$list=$roleDao->order('id')->field($fields)->limit($page->firstRow.','.$page->listRows)->select();
		$this->assign('list',$list);
		//功能模块按钮
		$option = get_option($idx);
		$this->assign('coption',$option);
		$this->display();
	}

	public function insert(){
		$tmpPOST = $_POST;
		unset($_POST['role'],$_POST['resource']);
		$roleDao=M("Role");
		if($data=$roleDao->create()){
			$roleDao->startTrans();
			$result = $roleDao->add();
			if($result){
				$autoid=$roleDao->getLastInsID();
				if($autoid>0 && $result){
					$iRes = $tmpPOST['resource'];
					$pDao = M("Permission");
					$sql="insert into __TABLE__ (`rid`,`fid`,`c`,`u`,`d`) values";
					$sqlbyte = 65;
					if($iRes==1){//选择
						$arrRoles =	$tmpPOST['role'];
						foreach($arrRoles as $fid=>$arr){
							$c = $u = $d =0;
							if(empty($arr)){
								$c = $u = $d = 1;
							}else{
								foreach($arr as $k=>$v){
									switch ($v) {
										case CRUD_C:$c=1;break;
										case CRUD_U:$u=1;break;
										case CRUD_D:$d=1;break;
									}
								}
							}
							$sql.=cstrlen($sql)>$sqlbyte?",($autoid,$fid,$c,$u,$d)":"($autoid,$fid,$c,$u,$d)";
						}
					}else{//全选
						$fun=new FunctionModel();
						$rlist=$fun->field('id')->select();
						foreach($rlist as $arr){
							$c = $u = $d = 1;$fid=$arr['id'];
							$sql.=cstrlen($sql)>$sqlbyte?",($autoid,$fid,$c,$u,$d)":"($autoid,$fid,$c,$u,$d)";
						}
					}
					if(cstrlen($sql)>$sqlbyte){
						$num = $pDao->execute($sql);
						if($num<1){
							$result = false;
						}
					}
				}
			}

			if($result){
				$roleDao->commit();
				$this->assign('jumpUrl',__CONTROLLER__);
				$this->success('操作成功，插入数据编号为：'.$autoid);
			}else{
				$roleDao->rollback();
				$this->error('操作失败：系统异常');
			}
		}else{
			$this->error('操作失败：数据验证( '.$roleDao->getError().' )');
		}
	}

	public function update(){
		$tmpPOST = $_POST;
		unset($_POST['role']);
		$roleDao=M("Role");
		if($data=$roleDao->create()){
			$rid = 0;
			if(!empty($data['id'])){
				$rid = $data['id'];
				$roleDao->save();
			}else{
				$this->error('请选择编辑用户');die();
			}

			$arrRoles =	$tmpPOST['role'];
			if(!empty($arrRoles) && $rid>0){
				$pDao = M("Permission");
				$pDao->startTrans();
				$sql="insert into __TABLE__ (`rid`,`fid`,`c`,`u`,`d`) values";
				$sqlbyte = 65;
				foreach($arrRoles as $fid=>$arr){
					$c = $u = $d =0;
					if(empty($arr)){
						$c = $u = $d = 1;
					}else{
						foreach($arr as $k=>$v){
							switch ($v) {
								case CRUD_C:$c=1;break;
								case CRUD_U:$u=1;break;
								case CRUD_D:$d=1;break;
							}
						}
					}
					$sql.=cstrlen($sql)>$sqlbyte?",($rid,$fid,$c,$u,$d)":"($rid,$fid,$c,$u,$d)";
				}
				$result = false;
				if(cstrlen($sql)>$sqlbyte){
					$delSql ="DELETE FROM __TABLE__ WHERE (`rid` = $rid);";
					$num = $pDao->execute($delSql);
					$num += $pDao->execute($sql);
					$result = $num<1?false:true;
				}

				if($result){
					$pDao->commit();
					load_permission_func();
					$this->success("操作成功");
				}else{
					$pDao->rollback();
					$this->error('操作失败：系统异常');
				}
		}else{
			$this->error('操作失败：数据验证( '.$user->getError().' )');
		}
		}
	}

	public function add(){
		$funDao=M("Func");
		$flist=$funDao->order('parent_id')->field('id,parent_id,caption,node_url')->select();
		$this->assign('rlist',$flist);
		$this->display();
	}

	public function edit(){
		$id=$_GET['id'];
		if(!empty($id)){
			//角色模型
			$roleDao=M("Role");
			$date=$roleDao->field('id,name,note')->getById($id);
			$this->assign('obj',$date);
			//权限
			$funcDao=M("Func");
			$rlist=$funcDao->order('parent_id')->field('id,parent_id,caption,node_url')->select();
			//角色权限
			$pDao =M("Permission");
			$rOwn = $pDao->field('fid,c,u,d')->where(array('rid'=>$id))->select();
			$rlown= $newList = array();
			foreach($rOwn as $arrModel){
				$rlown[$arrModel['fid']]=array('s'=>$arrModel['c']+$arrModel['u']+$arrModel['d'],'c'=>$arrModel['c'],'u'=>$arrModel['u'],'d'=>$arrModel['d']);
			}
			foreach($rlist as $arr){
				if(isset($rlown[$arr['id']])){
					$arr['own']=$rlown[$arr['id']];
				}
				$newList[]=$arr;
			}
			$this->assign('rlist',$newList);
			//按钮权限
			$option = $_SESSION['soption'];
			$this->assign('coption',$option);
		}
		$this->display();
	}

	public function delete(){
		$did=$_POST['did'];
		if(!empty($did) && is_array($did)){
			$roleDao=M("Role");
			$id=implode(',',$did);
			if(false!==$roleDao->delete($id)){
				//删除对应的角色权限
				$pDao = M("Permission");
				$delSql ="DELETE FROM __TABLE__ WHERE (`rid` = $id);";
				$num = $pDao->execute($delSql);
				if($num>0){
					$this->assign('jumpUrl',__CONTROLLER__);
					$this->success('操作成功');
				}else{
					$this->error('操作失败：'.$pDao->getDbError());
				}
			}else{
				$this->error('操作失败：'.$roleDao->getDbError());
			}
		}else{
			$this->error('请选择删除用户');
		}
	}
}
?>
<?php
namespace Admin\Model;
use Think\Model;

class RoleModel extends Model {
	/*
	 * 表单验证
	 */
	protected  $_validate = array(
		//array(验证字段,验证规则,错误提示,验证条件,附加规则,验证时间)
		array('name','require','角色名必须填写！',1,'regex',3),
		array('name','','角色名已经存在！',1,'unique',1),
	);
}
?>
<?php
namespace Admin\Model;
use Think\Model;

class UserModel extends Model {
	/*
	 * 表单验证
	 */
	protected  $_validate = array(
		//array(验证字段,验证规则,错误提示,验证条件,附加规则,验证时间)
		array('username','require','用户账号必须填写！',1,'regex',3),
		array('username','','用户账号已经存在！',1,'unique',1),
		array('password','require','用户密码必须填写！',0,'regex',1),
//		array('rpwd','pwd','两次密码不一致！',0,'confirm'),
//		array('name','require','用户昵称必须存在！',1),
		array('email','require','邮箱不能为空！'),
		array('email','email','邮箱格式不符合要求！'),
//		array('email','','邮箱已经存在！',1,'unique',3),
//		array('email','checkEmail','邮箱已经存在！',1,'callback',3),
		array('active',array(0,1),'注意数据，启用：1 ; 停用：0',0,'in'),
	);

	/*
	 * 字段映射
	 */
	protected $_map=array(
//		'pwd'=>'password'
	);

	/*
	 * 自动完成
	 */
	protected $_auto=array(
		//array(填充字段,填充内容,填充条件,附加规则)
		array('password','md5',1,'function'),
		array('reg_time','time',2,'function')
	);
}
?>
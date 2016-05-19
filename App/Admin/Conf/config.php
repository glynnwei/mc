<?php
//数据库配置
$dbConf= require(PROJECT_ROOT.'/config.inc.php');
//系统配置
$array = array(
//	 Nginx下开启
//	'URL_DISPATCH_ON'		=>		true,
//	'URL_MODEL'				=>		2,
	'PAGESIZE'				=>		20,
	'TMPL_VAR_IDENTIFY'		=>		'array',
	'USER_AUTH_ON'			=>		true, 			//开启认证
	'USER_AUTH_TYPE'		=>		1,  			//认证类型:1登录认证,2实时认证
	'USER_AUTH_KEY'			=>		'authId',  		//设置认证SESSION的标记名称
	'AUTH_PWD_ENCODER'		=>		'md5', 			//用户认证密码加密方式
	'USER_AUTH_GATEWAY'		=>		'/Public/login',//默认认证网关
	'USER_AUTH_MODEL'		=>		'User',  		//默认验证用户的表模型xx_user
	'ADMIN_AUTH_KEY'		=>		'administrator',//管理员用户标记
	'NOT_AUTH_MODULE'		=>		'Public',  		//无需的认证模块
	'TMPL_ACTION_SUCCESS' 	=> 		'Public:notify',
	'TMPL_ACTION_ERROR' 	=> 		'Public:notify',
//	'URL_HTML_SUFFIX'		=>		'html'			//伪静态
//	'NOT_AUTH_ACTION'		=>		'',				//默认无需认证的操作
//	'REQUIRE_AUTH_MODULE'	=>		'',  			//默认需要认证的模块
//	'REQUIRE_AUTH_ACTION'	=>		'',				//默认需要认证的操作
//	'RBAC_ERROR_PAGE'		=>		true,			//权限不够错误页面
//	'RBAC_ROLE_TABLE'		=>		'gm_role',
//	'RBAC_USER_TABLE'		=>		'gm_role_user',
//	'RBAC_ACCESS_TABLE'		=>		'gm_access',
//	'RBAC_NODE_TABLE'		=>		'gm_node',
);

//返回
return array_merge($dbConf,$array);
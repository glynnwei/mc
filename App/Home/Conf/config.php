<?php
//数据库配置
$dbConf= require(PROJECT_ROOT.'/config.inc.php');
//系统配置
$array = array(
		'TMPL_ACTION_SUCCESS' 	=> 		'Index:notify',
		'TMPL_ACTION_ERROR' 	=> 		'Index:notify',
);

//返回
return array_merge($dbConf,$array);
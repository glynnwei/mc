<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
date_default_timezone_set('Asia/Shanghai');
define("PROJECT_ROOT",	dirname(__FILE__));
define("COMMON_PATH", 	PROJECT_ROOT.'/Common');
define("FRAME_PATH", 	PROJECT_ROOT.'/Frame');
define("INTERFACE_PATH",PROJECT_ROOT.'/interface');

require(COMMON_PATH . "/Conf/define.php");
require(COMMON_PATH . "/Common/common.func.php");

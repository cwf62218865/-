<?php
defined('IN_IA') or exit('Access Denied');
//wl_load()->web('nav');
wl_load()->model('syssetting');
load()->func('communication');
load()->func('file');

$controller = $_GPC['do'];
$action = $_GPC['ac'];
$op = $_GPC['op']?$_GPC['op']:"index";


//用户登录判断
if(!$_SESSION['uid']){
	$controller = "member";
	$actions = array();
	$handle = opendir(WL_APP . 'controller/' . $controller);
		if (! empty($handle)) {
		while ($dir = readdir($handle)) {
			if ($dir != '.' && $dir != '..' && strexists($dir, '.ctrl.php')) {
				$dir = str_replace('.ctrl.php', '', $dir);
				$actions[] = $dir;
			}
		}
	}
	if (! in_array($action, $actions)) {
		$action = "login";
		$op = "index";
	}
}
if(empty($controller) || empty($action)) {
	$_GPC['do'] = $controller = 'dashboard';
	$_GPC['ac'] = $action = 'index';
}




$auth = wl_syssetting_read('auth');
!defined('WL_EDITION') && define('WL_EDITION', $auth['family']);
$getlistFrames = 'get'.$controller.'Frames';
if(function_exists($getlistFrames)){
	$frames = $getlistFrames();
}
//$top_menus = get_top_menus();
$file = WL_APP . 'controller/'.$controller.'/'.$action.'.ctrl.php';
//echo $file;exit();
if (!empty($auth) && $controller != 'system') {
	$addressid = pdo_getcolumn('weliam_shiftcar_wechataddr',array('acid' => $_W['acid']),'addressid');
	if (empty($addressid) && !empty($auth) && $controller != 'system') {
		message('您还未添加公众号运营地区！', web_url('system/account'),'success');
	}
}
if (!file_exists($file)) {
	header("Location: index.php?c=site&a=entry&m=".WL_NAME."&do=dashboard&ac=index");
	exit;
}

require $file;


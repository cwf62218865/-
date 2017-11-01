<?php
defined('IN_IA') or exit('Access Denied');
//wl_load()->web('nav');
wl_load()->model('syssetting');
load()->func('communication');
load()->func('file');
//echo $_COOKIE['admin'];exit();
//echo $_SESSION['admin'];exit();
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
		$action = "index";
		$op = "index";
	}
    $company_count = pdo_fetchcolumn("select count(*) from ".tablename(WL."company_profile"));
    $jobs_count = pdo_fetchcolumn("select count(*) from ".tablename(WL."jobs"));
}else{

	if($_SESSION['utype']==2){
		$company_statistics = company_statistics();
		$company_integrity = company_integrity();
	}elseif ($_SESSION['utype']==1){
		$person_statistics = person_statistics();
		$resume_integrity = resume_integrity();
		$add_order_num =  add_order_num();
		$interview_num =  interview_num();
	}
	$user = m("member")->get_member($_SESSION['uid']);

}
if(empty($controller) || empty($action)) {
	$_GPC['do'] = $controller = 'member';
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

if (!empty($auth) && $controller != 'system') {
	$addressid = pdo_getcolumn('weliam_shiftcar_wechataddr',array('acid' => $_W['acid']),'addressid');
	if (empty($addressid) && !empty($auth) && $controller != 'system') {
		message('您还未添加公众号运营地区！', web_url('system/account'),'success');
	}
}
if (!file_exists($file)) {
	header("Location: index.php?c=site&a=entry&m=".WL_NAME."&do=member&ac=index");
	exit;
}

require $file;


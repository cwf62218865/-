<?php 
defined('IN_IA') or exit('Access Denied');
$ops = array('base','templat','share','notice','api');
$op = in_array($op, $ops) ? $op : 'base';
load()->func('tpl');
wl_load()->model('setting');

if ($op == 'base') {
	$settings = wlsetting_read('base');
	if (checksubmit('submit')) {
		$base = array(
			'name'=>$_GPC['name'],
			'logo'=>$_GPC['logo'],
			'copyright'=>$_GPC['copyright'],
			'indexbg'=>$_GPC['indexbg'],
			'abbre'=>$_GPC['abbre']
		);
		wlsetting_save($base, 'base');
		message('更新设置成功！', web_url('setting/setting/base'));
	}
	include wl_template('setting/base');
}

if ($op == 'templat') {
	$settings = wlsetting_read('templat');
	$styles = array();
	$dir = WL_APP . "view/";
	if ($handle = opendir($dir)) {
		while (($file = readdir($handle)) !== false) {
			if ($file != ".." && $file != ".") {
				if (is_dir($dir . "/" . $file)) {
					$styles[] = $file;
				} 
			} 
		} 
		closedir($handle);
	}
	if (checksubmit('submit')) {
		$base = array(
			'appview'=>$_GPC['appview']
		);
		wlsetting_save($base, 'templat');
		message('更新设置成功！', web_url('setting/setting/templat'));
	}
	include wl_template('setting/templat');
}

if ($op == 'share') {
	$settings = wlsetting_read('share');
	if (checksubmit('submit')) {
		$base = array(
			'share_title'=>$_GPC['share_title'],
			'share_image'=>$_GPC['share_image'],
			'share_desc'=>$_GPC['share_desc']
		);
		wlsetting_save($base, 'share');
		message('更新设置成功！', web_url('setting/setting/share'));
	}
	include wl_template('setting/share');
}

if ($op == 'notice') {
	$settings = wlsetting_read('notice');
	if (checksubmit('submit')) {
		$base = array(
			'noticetimes'=>intval($_GPC['noticetimes']),
			'noticetype'=>intval($_GPC['noticetype']),
			'callstatus'=>intval($_GPC['callstatus']),
			'm_movecar'=>$_GPC['m_movecar'],
			'm_schedule'=>$_GPC['m_schedule'],
			'delivery'=>$_GPC['delivery']
		);
		wlsetting_save($base, 'notice');
		message('更新设置成功！', web_url('setting/setting/notice'));
	}
	include wl_template('setting/notice');
}

if ($op == 'api') {
	$settings = wlsetting_read('api');
	if (checksubmit('submit')) {
		$base = array(
			'jtatus'=>intval($_GPC['jtatus']),
			'ytatus'=>intval($_GPC['ytatus']),
			'btatus'=>intval($_GPC['btatus']),
			'dx_appid' => $_GPC['dx_appid'],
            'dx_secretkey' => $_GPC['dx_secretkey'],
            'dy_sf' => $_GPC['dy_sf'],
            'dy_dx' => $_GPC['dy_dx'],
            'dy_yy' => $_GPC['dy_yy'],
            'dy_yynum' => $_GPC['dy_yynum'],
            'dy_qm' => $_GPC['dy_qm'],
            'yun_accountsid' => $_GPC['yun_accountsid'],
            'yun_authtoken' => $_GPC['yun_authtoken'],
            'yun_appid' => $_GPC['yun_appid'],
            'yun_sf' => $_GPC['yun_sf'],
            'yun_dx' => $_GPC['yun_dx'],
            'yun_hm' => $_GPC['yun_hm'],
            'SubAccountSid' => $_GPC['SubAccountSid'],
            'SubAccountToken' => $_GPC['SubAccountToken'],
            'VoIPAccount' => $_GPC['VoIPAccount'],
            'VoIPPassword' => $_GPC['VoIPPassword']
		);
		wlsetting_save($base, 'api');
		message('更新设置成功！', web_url('setting/setting/api'));
	}
	include wl_template('setting/api');
}

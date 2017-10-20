<?php
defined('IN_IA') or exit('Access Denied');

function auth_user($siteid, $domain) {
	global $_W;
	$resp = ihttp_post(WL_URL_AUTH, array('type' => 'user','module' => 'recruit','website' => $siteid,'domain'=> $domain));
	$resp = @json_decode($resp['content'], true);
	return $resp;
}

function auth_checkauth($auth){
	global $_W;
	$result = ihttp_post(WL_URL_AUTH, array('type' => 'checkauth','module' => 'recruit','code' => $auth['code']));
	$result = @json_decode($result['content'], true);
	if($result['family'] != $auth['family']){
		$auth['family'] = $result['family'];
		wl_syssetting_save($auth,'auth');
	}
	return $result;
}

function auth_grant($data){
	global $_W;
	$resp = ihttp_post(WL_URL_AUTH, array('type' => 'grant','module' => 'recruit','code' => $data['code']));
	$resp = @json_decode($resp['content'], true);
	return $resp;
}

function auth_check($auth,$version){
	global $_W;
	$resp = ihttp_post(WL_URL_AUTH, array('type' => 'check','module' => 'recruit','version' => $version,'code' => $auth['code']));
    $upgrade = @json_decode($resp['content'], true);
	return $upgrade;
}

function auth_download($auth,$path){
	global $_W;
	$resp = ihttp_post(WL_URL_AUTH, array('type' => 'download','module' => 'recruit','path' => $path,'code' => $auth['code']));
    $ret = @json_decode($resp['content'], true);
	return $ret;
}

function auth_downaddress($auth){
	global $_W;
	$resp = ihttp_post(WL_URL_AUTH, array('type' => 'downaddress','module' => 'recruit','code' => $auth['code']));
    $ret = @json_decode($resp['content'], true);
	return $ret;
}

function auth_upaddress($auth,$data){
	global $_W;
	$resp = ihttp_post(WL_URL_AUTH, array('type' => 'upaddress','module' => 'recruit','code' => $auth['code'],'data' => $data));
    $ret = @json_decode($resp['content'], true);
	return $ret;
}
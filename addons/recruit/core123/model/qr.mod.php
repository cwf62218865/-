<?php
defined('IN_IA') or exit('Access Denied');

function qr_createkeywords(){
	global $_W;
	$rid = pdo_fetchcolumn("select id from " . tablename('rule') . 'where uniacid=:uniacid and module=:module and name=:name', array(
		':uniacid' => $_W['uniacid'],
		':module' => 'recruit',
		':name' => "微信挪车二维码入口"
	));
	if (empty($rid)) {
		$rule_data = array(
			'uniacid' => $_W['uniacid'],
			'name' => '微信挪车二维码入口',
			'module' => 'recruit',
			'displayorder' => 0,
			'status' => 1
		);
		pdo_insert('rule', $rule_data);
		$rid          = pdo_insertid();
		$keyword_data = array(
			'uniacid' => $_W['uniacid'],
			'rid' => $rid,
			'module' => 'recruit',
			'content' => 'weliam_shiftcar_qr',
			'type' => 1,
			'displayorder' => 0,
			'status' => 1
		);
		pdo_insert('rule_keyword', $keyword_data);
	}
	
	return $rid;
}

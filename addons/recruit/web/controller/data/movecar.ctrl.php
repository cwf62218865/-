<?php
defined('IN_IA') or exit('Access Denied');
$ops = array('display','detail','ajax');
$op = in_array($op, $ops) ? $op : 'display';

if ($op == 'display') {
	$where = " WHERE 1 and uniacid = {$_W['uniacid']} ";
	$params = array();

	$size = 15;
	$page = $_GPC['page'];
	$sqlTotal = pdo_sql_select_count_from('weliam_shiftcar_record') . $where;
	$sqlData = pdo_sql_select_all_from('weliam_shiftcar_record') . $where . ' ORDER BY `id` DESC ';
	$list = pdo_pagination($sqlTotal, $sqlData, $params, '', $total, $page, $size);
	foreach ($list as $key => $value) {
		$member = pdo_get('weliam_shiftcar_member', array('id' => $value['sendmid']), array('avatar','nickname'));
		$take_member = pdo_get('weliam_shiftcar_member', array('id' => $value['takemid']), array('avatar','nickname','plate1','plate2','plate_number'));
		$list[$key]['favatar'] = $member['avatar'];
		$list[$key]['fnickname'] = $member['nickname'];
		$list[$key]['javatar'] = $take_member['avatar'];
		$list[$key]['jnickname'] = $take_member['nickname'];
		$list[$key]['carcard'] = $take_member['plate1'].$take_member['plate2'].$take_member['plate_number'];
	}
	$pager = pagination($total, $page, $size);
}

include wl_template('data/movecar');

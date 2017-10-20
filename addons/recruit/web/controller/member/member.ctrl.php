<?php
defined('IN_IA') or exit('Access Denied');
$ops = array('display','detail','ajax','addmember','editmember','person','teacher','company');
$op = in_array($op, $ops) ? $op : 'display';
wl_load()->model('mc');





if ($op == 'display') {
	$where = " WHERE 1  ";
	$params = array();
	$type = intval($_GPC['type']);
	$keyword = trim($_GPC['keyword']);

	if (!empty($keyword)) {
		switch($type) {
			case 2 :
				$where .= " AND mobile LIKE :mobile";
				$params[':mobile'] = "%{$keyword}%";
				break;
			case 3 :
				$where .= " AND nickname LIKE :nickname";
				$params[':nickname'] = "%{$keyword}%";
				break;
			default :
				$where .= " AND ncnumber LIKE :ncnumber";
				$params[':ncnumber'] = "%{$keyword}%";
		}
	}
	if (!empty($_GPC['status']) && $_GPC['status'] != -1) {
		$where .= " AND status = :status";
		$params[':status'] = $_GPC['status'];
	}
	
	$size = 15;
	$page = $_GPC['page'];
	$sqlTotal = pdo_sql_select_count_from(WL.'members') . $where;
	$sqlData = pdo_sql_select_all_from(WL.'members') . $where . ' ORDER BY `id` DESC ';

	$list = pdo_pagination($sqlTotal, $sqlData, $params, '', $total, $page, $size);
//	foreach ($list as $key => $value) {
//		$list[$key]['fanid'] = pdo_getcolumn('mc_mapping_fans', array('openid' => $value['openid']), 'fanid');
//		if($value['ncnumber']){
//			$qrid = pdo_getcolumn('weliam_shiftcar_qrcode', array('cardsn' => $value['ncnumber']), 'qrid');
//			$ticket = pdo_getcolumn('qrcode', array('id' => $qrid), 'ticket');
//			$list[$key]['showurl'] = 'https:mp.weixin.qq.com/cgi-bin/showqrcode?ticket=' . urlencode($ticket);
//		}
//	}
	$pager = pagination($total, $page, $size);
}

if ($op == 'detail') {
	$mid = intval($_GPC['id']);
	$member = wl_mem_single($mid);
	$where = " WHERE 1 and uniacid = {$_W['uniacid']} AND (sendmid = {$mid} or takemid = {$mid})";
	$params = array();

	$size = 15;
	$page = $_GPC['page'];
	$sqlTotal = pdo_sql_select_count_from('weliam_shiftcar_record') . $where;
	$sqlData = pdo_sql_select_all_from('weliam_shiftcar_record') . $where . ' ORDER BY `id` DESC ';
	$list = pdo_pagination($sqlTotal, $sqlData, $params, '', $total, $page, $size);
	foreach ($list as $key => $value) {
		$send_member = pdo_get('weliam_shiftcar_member', array('id' => $value['sendmid']), array('avatar','nickname'));
		$take_member = pdo_get('weliam_shiftcar_member', array('id' => $value['takemid']), array('avatar','nickname','plate1','plate2','plate_number'));
		$list[$key]['favatar'] = $send_member['avatar'];
		$list[$key]['fnickname'] = $send_member['nickname'];
		$list[$key]['javatar'] = $take_member['avatar'];
		$list[$key]['jnickname'] = $take_member['nickname'];
		$list[$key]['carcard'] = $take_member['plate1'].$take_member['plate2'].$take_member['plate_number'];
	}
	$pager = pagination($total, $page, $size);
}


if($op=='addmember'){
	wl_load()->model('verify');

	if($_GPC['addmember']=="addmember"){
		$data['utype'] = $_POST['utype'];
//		$data['username'] = check_pasre($_POST['username']);
		$data['mobile'] = $_POST['mobile'];
		$data['email'] = $_POST['email'];
		$data['password'] = $_POST['password'];
		$data['salt'] = mt_rand(100,999);
		$data['password'] = pwd_hash($data['password'],$data['salt']);
		$data['createtime'] = time();
		$member = pdo_fetch("select id from ".tablename(WL.'members')." where mobile=".$data['mobile']);
		if($member){
			echo "已存在";
		}else{
			$r = pdo_insert(WL.'members',$data);
			if($r){
				echo 1;
			}else{
				echo 2;
			}
		}

		exit();
	}
	include wl_template('member/addmember');exit();
}

if($op=='editmember'){

	if($_GPC['editmember']){

//		var_dump($_POST);exit();
	}
	$member = pdo_fetch("select * from".tablename(WL.'members')." where id=".$_GPC['id']);
	if($member['utype']==1){

	}elseif ($member['utype']==2){
		$company_profile = pdo_fetch("select * from ".tablename(WL.'company_profile')." where uid=".$member['id']);	
	}else{

	}
	include wl_template('member/editmember');exit();
}

if($op=='person'){

	include wl_template('member/person');exit();
}

if($op=='company'){
	$company_profile = pdo_fetchall("select * from ".tablename(WL.'company_profile'));
	include wl_template('member/company');exit();
}

if($op=='teacher'){

	include wl_template('member/teacher');exit();
}


include wl_template('member/member');

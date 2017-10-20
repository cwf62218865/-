<?php
defined('IN_IA') or exit('Access Denied');
uni_user_permission_check('platform_qr');
$ops = array('display', 'post', 'list', 'del', 'delsata', 'extend', 'SubDisplay', 'get');
$op = !empty($_GPC['op']) && in_array($op, $ops) ? $op : 'list';
load()->model('account');
wl_load()->model('qr');

if($op == 'list') {
	$member = pdo_fetchall("select * from ".tablename(WL."members"));
	$_W['page']['title'] = '管理二维码 - 二维码管理 - 高级功能';
	$wheresql = " WHERE uniacid = :uniacid";
	$param = array(':uniacid' => $_W['uniacid']);
	$keyword = trim($_GPC['keyword']);
	if(!empty($keyword)) {
		$wheresql .= " AND cardsn LIKE '%{$keyword}%'";
	}
	$starttime = empty($_GPC['time']['start']) ? TIMESTAMP -  86399 * 30 : strtotime($_GPC['time']['start']);
	$endtime = empty($_GPC['time']['end']) ? TIMESTAMP + 6*86400: strtotime($_GPC['time']['end']) + 86399;
	if(!empty($_GPC['time']['start'])) {
		$wheresql .= " AND createtime >= '{$starttime}' AND createtime <= '{$endtime}'";
	}
	if (!empty($_GPC['status'])) {
		$wheresql .= " AND status = {$_GPC['status']}";
	}
	if (!empty($_GPC['model']) && $_GPC['model'] != -1) {
		$wheresql .= " AND model = {$_GPC['model']}";
	}
	
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$list = pdo_fetchall("SELECT * FROM ".tablename('weliam_shiftcar_qrcode'). $wheresql . ' ORDER BY `id` DESC LIMIT '.($pindex - 1) * $psize.','. $psize, $param);
	if (!empty($list)) {
		foreach ($list as $index => &$qrcode) {
			$wq_qr = pdo_get('qrcode', array('id' => $qrcode['qrid']), array('ticket', 'scene_str', 'qrcid', 'id','createtime'));
			$qrcode['scene_str'] = $wq_qr['scene_str'];
			$qrcode['qrcid'] = $wq_qr['qrcid'];
			$qrcode['id'] = $wq_qr['id'];
			$qrcode['showurl'] = 'https:mp.weixin.qq.com/cgi-bin/showqrcode?ticket=' . urlencode($wq_qr['ticket']);
			$qrcode['endtime'] = $wq_qr['createtime'] + 2592000;
			if (TIMESTAMP > $qrcode['endtime']) {
				$qrcode['endtime'] = '<font color="red">已过期</font>';
			}else{
				$qrcode['endtime'] = date('Y-m-d H:i:s',$qrcode['endtime']);
			}
			if ($qrcode['model'] == 2) {
				$qrcode['modellabel']="永久";
				$qrcode['endtime'] = '<font color="green">永不</font>';
			} else {
				$qrcode['modellabel']="临时";
			}
		}
	}
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('weliam_shiftcar_qrcode') . $wheresql, $param);
	$pager = pagination($total, $pindex, $psize);
	pdo_query("UPDATE ".tablename('qrcode')." SET status = '0' WHERE uniacid = '{$_W['uniacid']}' AND model = '1' AND createtime < '{$_W['timestamp']}' - expire");
	
	if($_GPC['export'] != ''){
		$list = pdo_fetchall("SELECT * FROM ".tablename('weliam_shiftcar_qrcode'). $wheresql . ' ORDER BY `id` DESC', $param);
		/* 输入到CSV文件 */
		$html = "\xEF\xBB\xBF";
		/* 输出表头 */
		$filter = array(
			'showurl' => '二维码',
			'cardsn' => '挪车卡编号',
			'memid' => '用户码',
			'status' => '使用状态',
			'model' => '二维码类型',
			'qrcid' => '场景ID',
			'createtime' => '生成时间'
		);

		foreach ($filter as $key => $title) {
			$html .= $title . "\t,";
		}
		$html .= "\n";
		foreach ($list as $k => $v) {
			$wq_qr = pdo_get('qrcode', array('id' => $v['qrid']), array('ticket', 'scene_str', 'qrcid', 'id','url'));
			$v['scene_str'] = $wq_qr['scene_str'];
			$v['qrcid'] = $wq_qr['qrcid'];
			$v['showurl'] = $wq_qr['url'];
			foreach ($filter as $key => $title) {
				if ($key == 'createtime') {
					$html .= date('Y-m-d H:i:s', $v[$key]) . "\t, ";
				}elseif($key == 'status'){
					switch ($v[$key]) {
						case '1':
							$html .= '未绑定' . "\t, ";
							break;
						case '2':
							$html .= '已绑定' . "\t, ";
							break;
						default:
							$html .= '已失效' . "\t, ";
							break;
					}
				}elseif($key == 'model'){
					switch ($v[$key]) {
						case '1':
							$html .= '临时' . "\t, ";
							break;
						default:
							$html .= '永久' . "\t, ";
							break;
					}
				}elseif($key == 'qrcid'){
					if(!empty($v['qrcid'])){
						$html .= $v['qrcid'] . "\t, ";
					}else{
						$html .= $v['scene_str'] . "\t, ";
					}
				}elseif($key == 'memid'){
					$html .= substr($v['cardsn'],-7) . "\t, ";
				}else {
					$html .= $v[$key] . "\t, ";
				}
			}
			$html .= "\n";
		}
		/* 输出CSV文件 */
		header("Content-type:text/csv");
		header("Content-Disposition:attachment; filename=全部数据.csv");
		echo $html;
		exit();
	}
	include wl_template('card/qr-list');
}

if($op == 'del') {
	if ($_GPC['scgq']) {
		$list = pdo_fetchall("SELECT id FROM ".tablename('qrcode')." WHERE uniacid = :uniacid AND acid = :acid AND status = '0' AND type='scene'", array(':uniacid' => $_W['uniacid'], ':acid' => $_W['acid']), 'id');
		if (!empty($list)) {
			pdo_query("DELETE FROM ".tablename('qrcode')." WHERE id IN (".implode(',', array_keys($list)).")");
			pdo_query("DELETE FROM ".tablename('qrcode_stat')." WHERE qid IN (".implode(',', array_keys($list)).")");
		}
		message('执行成功<br />删除二维码：'.count($list), web_url('card/qr/list'),'success');
	}else{
		$id = $_GPC['id'];
		pdo_delete('qrcode', array('id' =>$id, 'uniacid' => $_W['uniacid']));
		pdo_delete('qrcode_stat',array('qid' => $id, 'uniacid' => $_W['uniacid']));
		message('删除成功',web_url('card/qr/list'),'success');
	}
}

if($op == 'post') {
	$_W['page']['title'] = '生成二维码 - 二维码管理 - 高级功能';
	if(checksubmit('submit')){
		qr_createkeywords();
		$qrctype = intval($_GPC['qrc-model']);
		$allnum = intval($_GPC['qr_num']);
		include wl_template('card/qr-process');
		exit;
	}

	include wl_template('card/qr-post');
}

if($op == 'get') {
	load()->func('communication');
	$barcode = array(
		'expire_seconds' => '',
		'action_name' => '',
		'action_info' => array(
			'scene' => array(),
		),
	);
	$qrctype = intval($_GPC['qrc-model']);
	$acid = intval($_W['acid']);
	$uniacccount = WeAccount::create($acid);

	if ($qrctype == 1) {
		$qrcid = pdo_fetchcolumn("SELECT qrcid FROM ".tablename('qrcode')." WHERE acid = :acid AND model = '1' ORDER BY qrcid DESC LIMIT 1", array(':acid' => $acid));
		$barcode['action_info']['scene']['scene_id'] = !empty($qrcid) ? ($qrcid + 1) : 100001;
		$barcode['expire_seconds'] = 2592000;
		$barcode['action_name'] = 'QR_SCENE';
		$result = $uniacccount->barCodeCreateDisposable($barcode);
	} else if ($qrctype == 2) {
		$qrcid = pdo_fetchcolumn("SELECT COUNT(id) FROM ".tablename('weliam_shiftcar_qrcode')." WHERE uniacid = :uniacid AND model = '2'", array(':uniacid' => $_W['uniacid']));
		$sstr = !empty($qrcid) ? ($qrcid + 1) : 1;
		$scene_str = 'YJ'.$sstr;
		$is_exist = pdo_fetchcolumn('SELECT id FROM ' . tablename('qrcode') . ' WHERE uniacid = :uniacid AND acid = :acid AND scene_str = :scene_str AND model = 2', array(':uniacid' => $_W['uniacid'], ':acid' => $_W['acid'], ':scene_str' => $scene_str));
		if(!empty($is_exist)) {
			$scene_str = 'YJ'.date('md').substr(time(), -5).substr(microtime(), 2, 5).sprintf('%02d', rand(0, 99));
		}
		$barcode['action_info']['scene']['scene_str'] = $scene_str;
		$barcode['action_name'] = 'QR_LIMIT_STR_SCENE';
		$result = $uniacccount->barCodeCreateFixed($barcode);
	}
	
	if(!is_error($result)) {
		$insert = array(
			'uniacid' => $_W['uniacid'],
			'acid' => $acid,
			'qrcid' => $barcode['action_info']['scene']['scene_id'],
			'scene_str' => $barcode['action_info']['scene']['scene_str'],
			'keyword' => 'weliam_shiftcar_qr',
			'name' => '微信挪车卡',
			'model' => $qrctype,
			'ticket' => $result['ticket'],
			'url' => $result['url'],
			'expire' => $result['expire_seconds'],
			'createtime' => TIMESTAMP,
			'status' => '1',
			'type' => 'scene',
		);
		pdo_insert('qrcode', $insert);
		$qrid = pdo_insertid();
		$qrinsert = array(
			'uniacid' => $_W['uniacid'],
			'qrid' => $qrid,
			'model' => $qrctype,
			'cardsn' => 'NC'.$addressid.sprintf("%07d",$sstr),
			'createtime' => TIMESTAMP,
			'status' => '1',
			'remark' => $_GPC['remark']
		);
		pdo_insert('weliam_shiftcar_qrcode', $qrinsert);
		die(json_encode(array('result' => 1)));
	} else {
		$success = "公众平台返回接口错误. <br />错误代码为: {$result['errorcode']} <br />错误信息为: {$result['message']}";
		die(json_encode(array('result' => 2,'message' => $success)));
	}
}

if($op == 'extend') {
	load()->func('communication');
	$id = intval($_GPC['id']);
	if (!empty($id)) {
		$qrcrow = pdo_fetch("SELECT * FROM ".tablename('qrcode')." WHERE uniacid = :uniacid AND id = :id LIMIT 1", array(':uniacid' => $_W['uniacid'], ':id' => $id));
		$update = array();
		if ($qrcrow['model'] == 1) {
			$uniacccount = WeAccount::create($qrcrow['acid']);
			$barcode['action_info']['scene']['scene_id'] = $qrcrow['qrcid'];
			$barcode['expire_seconds'] = 2592000;
			$barcode['action_name'] = 'QR_SCENE';
			$result = $uniacccount->barCodeCreateDisposable($barcode);
			if(is_error($result)) {
				message($result['message'], '', 'error');
			}
			$update['ticket'] = $result['ticket'];
			$update['url'] = $result['url'];
			$update['expire'] = $result['expire_seconds'];
			$update['createtime'] = TIMESTAMP;
			pdo_update('qrcode', $update, array('id' => $id, 'uniacid' => $_W['uniacid']));
		}
		message('恭喜，延长临时二维码时间成功！', referer(), 'success');
	}
}

if($op == 'display') {
	$starttime = empty($_GPC['time']['start']) ? TIMESTAMP -  86399 * 30 : strtotime($_GPC['time']['start']);
	$endtime = empty($_GPC['time']['end']) ? TIMESTAMP + 6*86400: strtotime($_GPC['time']['end']) + 86399;
	$where .= " WHERE name = '微信挪车卡' AND uniacid = :uniacid AND acid = :acid AND createtime >= :starttime AND createtime < :endtime";
	$param = array(':uniacid' => $_W['uniacid'], ':acid' => $_W['acid'], ':starttime' => $starttime, ':endtime' => $endtime);
	!empty($_GPC['keyword']) && $where .= " AND name LIKE '%{$_GPC['keyword']}%'";
	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;
	$count = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('qrcode_stat'). $where, $param);
	$list = pdo_fetchall("SELECT * FROM ".tablename('qrcode_stat')." $where ORDER BY id DESC LIMIT ".($pindex - 1) * $psize.','. $psize, $param);
	if (!empty($list)) {
		$openid = array();
		foreach ($list as $index => &$qrcode) {
			if ($qrcode['type'] == 1) {
				$qrcode['type']='<span class="label label-danger">关注</span>';
			} else {
				$qrcode['type']='<span class="label label-primary">扫描</span>';
			}
			if(!in_array($qrcode['openid'], $openid)) {
				$openid[] = $qrcode['openid'];
			}
			$list[$index]['mid'] = pdo_getcolumn('weliam_shiftcar_member', array('openid' => $qrcode['openid'],'uniacid' => $_W['uniacid']), 'id');
			$list[$index]['cardsn'] = pdo_getcolumn('weliam_shiftcar_qrcode', array('qrid' => $qrcode['qid']), 'cardsn');
		}
		$openids = implode("','", $openid);
		$param_temp[':uniacid'] = $_W['uniacid'];
		$param_temp[':acid'] = $_W['acid'];
		$nickname = pdo_fetchall('SELECT nickname, openid FROM ' . tablename('mc_mapping_fans') . " WHERE uniacid = :uniacid AND acid = :acid AND openid IN ('{$openids}')", $param_temp, 'openid');
	}
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('qrcode_stat') . $where, $param);
	$pager = pagination($total, $pindex, $psize);
	include wl_template('card/qr-display');
}

if($op == 'delsata') {
	$id = $_GPC['id'];
	$b = pdo_delete('qrcode_stat',array('id' =>$id, 'uniacid' => $_W['uniacid']));
	if ($b){
		message('删除成功',web_url('card/qr/display'),'success');
	}else{
		message('删除失败',web_url('card/qr/display'),'error');
	}
}

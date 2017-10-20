<?php
/**
 * 微信挪车模块处理程序
 *
 * @author 折翼天使源码
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');
require IA_ROOT. '/addons/recruit/core/common/defines.php';
require WL_CORE . 'class/wlloader.class.php';
wl_load()->func('global');
wl_load()->func('pdo');

class Weliam_shiftcarModuleProcessor extends WeModuleProcessor {

	public function respond() {
		global $_W;
		$message = $this -> message;
		$openid = $this -> message['from'];
		$scene = $this -> message['scene'];
		$msgtype = strtolower($message['msgtype']);
		if ($msgtype == 'event') {
			if (is_numeric($scene)) {
				$qrid = pdo_fetchcolumn('select id from ' . tablename('qrcode') . ' where uniacid=:uniacid and qrcid=:qrcid', array(':uniacid' => $_W['uniacid'], ':qrcid' => $scene));
			}else{
				$qrid = pdo_fetchcolumn('select id from ' . tablename('qrcode') . ' where uniacid=:uniacid and scene_str=:scene_str', array(':uniacid' => $_W['uniacid'], ':scene_str' => $scene));
			}
			$card = pdo_get('weliam_shiftcar_qrcode',array('uniacid' => $_W['uniacid'],'qrid' => $qrid),array('cardsn','mid','status'));
			$carmember = pdo_get('weliam_shiftcar_member',array('uniacid' => $_W['uniacid'],'id' => $card['mid']),array('message','openid'));
			$member = pdo_get('weliam_shiftcar_member',array('uniacid' => $_W['uniacid'],'openid' => $openid),array('ncnumber','id'));
			if(empty($member['id'])){
				$member = array(
					'uniacid' => $_W['uniacid'], 
					'invid' => !empty($card['mid']) ? $card['mid'] : -1, 
					'openid' => $openid, 
					'status' => 1,
					'mstatus' => 1,
					'userstatus' => 1,
					'createtime' => time()
				);
				pdo_insert('weliam_shiftcar_member', $member);
			}
			
			//挪车卡未绑定
			if($card['status'] == 1 && empty($member['ncnumber'])){
				return $this -> respText('<a href="'.app_url('member/mycar/display',array('ncnum' => $card['cardsn'])).'">您好，绑定挪车卡，请点击绑定！</a>');
			}
			if($card['status'] == 1 && !empty($member['ncnumber'])){
				return $this -> respText("您好，您确定绑定新的挪车卡吗？ \n".'<a href="'.app_url('member/mycar/display',array('ncnum' => $card['cardsn'])).'">旧挪车卡将失效，确定请点击绑定。</a>');
			}
			//挪车卡已绑定
			if($card['status'] == 2 ){
				if($carmember['openid'] == $openid){
					return $this -> respText('调皮，你是要自己通知自己挪车吗？');
				}
				if($carmember['message']){
					$message = $carmember['message']." \n".'<a href="'.app_url('home/movecar',array('ncnum' => $card['cardsn'])).'">点击确定，呼叫车主挪车~~</a>';
				}else{
					$message ="临时停车，给您带来不便尽请谅解！\n".'<a href="'.app_url('home/movecar',array('ncnum' => $card['cardsn'])).'">点击确定，呼叫车主挪车~~</a>';
				}
				return $this -> respText($message);
			}
			//挪车卡已禁止
			if($card['status'] == 3){
				return $this -> respText('抱歉，此挪车卡已失效！');
			}
		} 
	}
}
<?php
defined('IN_IA') or exit('Access Denied');

function sendTplNotice($touser, $template_id, $postdata, $url = '', $account = null) {
    global $_W;
    load()->model('account');
    if (!$account) {
        if (!empty($_W['acid'])) {
            $account = WeAccount::create($_W['acid']);
        } else {
            $acid = pdo_fetchcolumn("SELECT acid FROM " . tablename('account_wechats') . " WHERE `uniacid`=:uniacid LIMIT 1", array(
                ':uniacid' => $_W['uniacid']
            ));
            $account = WeAccount::create($acid);
        }
    }
    if (!$account) {
        return;
    }
    return $account->sendTplNotice($touser, $template_id, $postdata, $url);
}

function movecar_notice($openid,$recordid) {
    global $_W;
    $m_send = $_W['wlsetting']['notice']['m_movecar'];
    $postdata = array(
        "first" => array(
            "value" => "您好，您有一条挪车提醒",
            "color" => "#173177"
        ) ,
        "keyword1" => array(
            'value' => "系统",
            "color" => "#173177"
        ) ,
        "keyword2" => array(
            'value' => "您的爱车挡住了他人车辆，麻烦您给挪一下呗",
            "color" => "#173177"
        ) ,
        "remark" => array(
            "value" => '如有疑问，请点击查看详情或联系客服',
            "color" => "#173177"
        ) ,
    );
	$url = app_url('member/record/detail',array('id' => $recordid));
    sendTplNotice($openid, $m_send, $postdata, $url);
}

function movecarsch_notice($openid,$carcard,$recordid) {
    global $_W;
    $m_send = $_W['wlsetting']['notice']['m_schedule'];
    $postdata = array(
        "first" => array(
            "value" => "你好，已成功通知到对方车主，预计15分钟内完成挪车。",
            "color" => "#173177"
        ) ,
        "keyword1" => array(
            'value' => $carcard,
            "color" => "#173177"
        ) ,
        "keyword2" => array(
            'value' => date('Y年m月d日 H:i:s', time()),
            "color" => "#173177"
        ) ,
        "keyword3" => array(
            'value' => "已成功通知车主",
            "color" => "#173177"
        ) ,
        "remark" => array(
            "value" => '点击查看详情，感谢你的使用。',
            "color" => "#173177"
        ) ,
    );
	$url = app_url('member/record/detail',array('id' => $recordid));
    sendTplNotice($openid, $m_send, $postdata, $url);
}

function delivery_notice($openid,$orderNo,$expressName,$expressOrder) {
    global $_W;
	wl_load()->model('setting');
	$settings = wlsetting_read('notice');
    $m_send = $settings['delivery'];
    $postdata = array(
        "first" => array(
            "value" => "亲，挪车卡已经启程了，好想快点来到你身边",
            "color" => "#173177"
        ) ,
        "keyword1" => array(
            'value' => $orderNo,
            "color" => "#173177"
        ) ,
        "keyword2" => array(
            'value' => $expressName,
            "color" => "#173177"
        ) ,
        "keyword3" => array(
            'value' => $expressOrder,
            "color" => "#173177"
        ) ,
        "remark" => array(
            "value" => '点击查看完整的物流信息 。如有问题请直接在微信里留言，我们将在第一时间为您服务！',
            "color" => "#173177"
        ) ,
    );
	$url = app_url('app/apply');
    sendTplNotice($openid, $m_send, $postdata, $url);
}
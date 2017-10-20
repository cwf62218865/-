<?php
defined('IN_IA') or exit('Access Denied');

function send_authmsg($mobile){
	global $_W;
	$code = rand(1000, 9999);
	if($_W['wlsetting']['api']['jtatus'] = 2){
//		$title = !empty($_W['wlsetting']['base']['name']) ? $_W['wlsetting']['base']['name'] : '慧猎网';
//		$qm = !empty($_W['wlsetting']['api']['dy_qm']) ? $_W['wlsetting']['api']['dy_qm'] : '慧猎网';
//		m('topclient')->appkey = $_W['wlsetting']['api']['dx_appid'];
//		m('topclient')->secretKey = $_W['wlsetting']['api']['dx_secretkey'];
		m('topclient')->appkey = "LTAIo0DH0xQ2bkvY";
		m('topclient')->secretKey = "Gt2l7PKr8T0uFTFrWgZAm3mFzAWQPN";
		m('smsnum')->setExtend($code);
		m('smsnum')->setSmsType("normal");
		m('smsnum')->setSmsFreeSignName("包崇林");
		m('smsnum')->setSmsParam('{"code":"'.$code.'"');
		m('smsnum')->setRecNum($mobile);
		m('smsnum')->setSmsTemplateCode("SMS_95815030");
		$resp = m('topclient')->execute(m('smsnum'),'6100e23657fb0b2d0c78568e55a3031134be9a3a5d4b3a365753805',"");
		var_dump($resp);exit();
		$res = object_array($resp);
		if($res['result']['success'] == 1){
			//生成发送记录
//			create_apirecord(-1,'',$_W['mid'],$mobile,1,'阿里大于身份验证');
			$cookie = array();
			$cookie['mobile'] = $mobile;
			$cookie['code'] = $code;
			$session = base64_encode(json_encode($cookie));
			isetcookie('__auth_session', $session, 600, true);
			die(json_encode(array("result" => 1)));	
		}else{
			die(json_encode(array("result" => 2)));
		}
	}else{
		include_once(WL_CORE . "class/CCPRestSDK.class.php");
		$accountSid = $_W['wlsetting']['api']['yun_accountsid'];
		$accountToken = $_W['wlsetting']['api']['yun_authtoken'];
		$appId = $_W['wlsetting']['api']['yun_appid'];
		$serverIP='app.cloopen.com';
		$serverPort='8883';
		$softVersion='2013-12-26';

	    $rest = new REST($serverIP,$serverPort,$softVersion);
	    $rest->setAccount($accountSid,$accountToken);
	    $rest->setAppId($appId);

	    $result = $rest->sendTemplateSMS($mobile,array($code),$_W['wlsetting']['api']['yun_sf']);
	    if($result == NULL ) {
	        die(json_encode(array("result" => 2)));
	    }
	    if($result->statusCode!=0) {
	        die(json_encode(array("result" => 2)));
	    }else{
//			create_apirecord(-1,'',$_W['mid'],$mobile,1,'云通讯身份验证');
			$cookie = array();
			$cookie['mobile'] = $mobile;
			$cookie['code'] = $code;
			$session = base64_encode(json_encode($cookie));
			isetcookie('__auth_session', $session, 600, true);
			die(json_encode(array("result" => 1)));
	    }
	}
}

function send_smsnotice($mobile,$calltel,$takemid){
	global $_W;
	if($_W['wlsetting']['api']['jtatus'] != 2){
		$title = !empty($_W['wlsetting']['api']['dy_qm']) ? $_W['wlsetting']['api']['dy_qm'] : $_W['wlsetting']['base']['name'];
		m('topclient')->appkey = $_W['wlsetting']['api']['dx_appid'];
		m('topclient')->secretKey = $_W['wlsetting']['api']['dx_secretkey'];
		m('smsnum')->setSmsType("normal");
		m('smsnum')->setSmsFreeSignName($title);
		m('smsnum')->setSmsParam('{"name":"'.$title.'","tel":"'.$calltel.'"}');
		m('smsnum')->setRecNum($mobile);
		m('smsnum')->setSmsTemplateCode($_W['wlsetting']['api']['dy_dx']);
		$resp = m('topclient')->execute(m('smsnum'),'6100e23657fb0b2d0c78568e55a3031134be9a3a5d4b3a365753805');
		$res = object_array($resp);
		if($res['result']['success'] == 1){
			create_apirecord($_W['mid'],$calltel,$takemid,$mobile,2,'阿里大于短信通知');
			return array("result" => 1);	
		}else{
		 	die(json_encode(array("result" => 2,"msg" => $res['sub_msg'])));
		}
	}else{
		include_once(WL_CORE . "class/CCPRestSDK.class.php");
		$accountSid = $_W['wlsetting']['api']['yun_accountsid'];
		$accountToken = $_W['wlsetting']['api']['yun_authtoken'];
		$appId = $_W['wlsetting']['api']['yun_appid'];
		$serverIP='app.cloopen.com';
		$serverPort='8883';
		$softVersion='2013-12-26';

	    $rest = new REST($serverIP,$serverPort,$softVersion);
	    $rest->setAccount($accountSid,$accountToken);
	    $rest->setAppId($appId);

	    $result = $rest->sendTemplateSMS($mobile,array($calltel),$_W['wlsetting']['api']['yun_dx']);
	    if($result == NULL ) {
	        die(json_encode(array("result" => 2,"msg" => '短信通知发送失败')));
	    }
	    if($result->statusCode!=0) {
	        die(json_encode(array("result" => 2,"msg" => $result->statusCode.$result->statusMsg)));
	    }else{
			create_apirecord($_W['mid'],$calltel,$takemid,$mobile,2,'云通讯短信通知');
			return array("result" => 1);
	    }
	}
}

function send_landingcall($mobile,$calltel,$takemid){
	global $_W;
	$title = !empty($_W['wlsetting']['base']['name']) ? $_W['wlsetting']['base']['name'] : '微信挪车';
	if($_W['wlsetting']['api']['ytatus'] != 2){
        m('topclient')->appkey = $_W['wlsetting']['api']['dx_appid'];
		m('topclient')->secretKey = $_W['wlsetting']['api']['dx_secretkey'];
        m('singlecall')->setCalledNum($mobile);
        m('singlecall')->setCalledShowNum($_W['wlsetting']['api']['dy_yynum']);
        m('singlecall')->setTtsCode($_W['wlsetting']['api']['dy_yy']);
        m('singlecall')->setTtsParam('{"name":"'.$title.'","mobile":"'.$calltel.'"}');
        $resp = m('topclient')->execute(m('singlecall') , "6100e23657fb0b2d0c78568e55a3031134be9a3a5d4b3a365753805");
        $res = object_array($resp);
        if ($res['result']['success'] == 1) {
        	create_apirecord($_W['mid'],$calltel,$takemid,$mobile,3,'阿里大于语音通知');
            return array("result" => 1);
        } else {
            die(json_encode(array("result" => 2,"msg" => $res['sub_msg'])));
        }
	}else{
		include_once(WL_CORE . "class/CCPRestSDK.class.php");
		$accountSid = $_W['wlsetting']['api']['yun_accountsid'];
		$accountToken = $_W['wlsetting']['api']['yun_authtoken'];
		$appId = $_W['wlsetting']['api']['yun_appid'];
		$serverIP='app.cloopen.com';
		$serverPort='8883';
		$softVersion='2013-12-26';
		
		if(!empty($_W['wlsetting']['api']['yun_hm'])){
			$displayNum = $_W['wlsetting']['api']['yun_hm'];
		}
	    // 初始化REST SDK
	    $rest = new REST($serverIP,$serverPort,$softVersion);
	    $rest->setAccount($accountSid,$accountToken);
	    $rest->setAppId($appId);
	    
	    //调用外呼通知接口
	    $result = $rest->landingCall($mobile,'nuoche.wav',$mediaTxt,$displayNum,3,$respUrl,$userData,$maxCallTime,$speed,$volume,$pitch,$bgsound);
	    if($result == NULL ) {
	        die(json_encode(array("result" => 2,"msg" => '语音通知发送失败')));
	    }
	    if($result->statusCode!=0) {
	    	die(json_encode(array("result" => 2,"msg" => $result->statusCode.$result->statusMsg)));
	    } else{
	        create_apirecord($_W['mid'],$calltel,$takemid,$mobile,3,'云通讯语音通知');
            return array("result" => 1);
	    }  
	}
}

function send_callback($mobile,$calltel,$takemid){
	global $_W;
	include_once(WL_CORE . "class/CCPRestSDK.class.php");
	$accountSid = $_W['wlsetting']['api']['yun_accountsid'];
	$accountToken = $_W['wlsetting']['api']['yun_authtoken'];
	$appId = $_W['wlsetting']['api']['yun_appid'];
	$serverIP ='sandboxapp.cloopen.com';
	$serverPort ='8883';
	$softVersion ='2013-12-26';
	
	$rest = new REST($serverIP,$serverPort,$softVersion);
    $rest->setSubAccount($_W['wlsetting']['api']['SubAccountSid'],$_W['wlsetting']['api']['SubAccountToken'],$_W['wlsetting']['api']['VoIPAccount'],$_W['wlsetting']['api']['VoIPPassword']);
    $rest->setAppId($appId);

    $result = $rest->callBack($calltel,$mobile,$customerSerNum,$fromSerNum,$promptTone,$alwaysPlay,$terminalDtmf,$userData,$maxCallTime,$hangupCdrUrl,$needBothCdr,$needRecord,$countDownTime,$countDownPrompt);
    if($result == NULL ) {
        die(json_encode(array("result" => 2,"msg" => '电话互通发起失败')));
    }
    if($result->statusCode!=0) {
        die(json_encode(array("result" => 2,"msg" => $result->statusCode.$result->statusMsg)));
    } else {
        create_apirecord($_W['mid'],$calltel,$takemid,$mobile,4,'云通讯电话回拨');
        return array("result" => 1);
    } 
}

function create_apirecord($sendmid,$sendmobile = '',$takemid,$takemobile,$type,$remark){
	global $_W;
	$data = array(
		'uniacid' => $_W['uniacid'],
		'sendmid' => $sendmid,
		'sendmobile' => $sendmobile,
		'takemid' => $takemid,
		'takemobile' => $takemobile,
		'type' => $type,
		'remark' => $remark,
		'createtime' => time()
	);
	pdo_insert('weliam_shiftcar_apirecord',$data);
}

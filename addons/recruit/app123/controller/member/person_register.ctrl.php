<?php
defined('IN_IA') or exit('Access Denied');
wl_load()->model('verify');
if($op=="index"){
    include wl_template("member/personal_reg");
}elseif ($op=="register"){
    if(check_phone($_GPC['mobie'])){
        $data['mobile'] =$_GPC['mobie'];
    }
    $identify_code=check_pasre($_POST['identify_code'],"请输入验证码");
    if($identify_code!=$_SESSION['phone_code']){
        call_back(2,"验证码不正确");
    }

    if(strlen($_POST['password'])<6 || strlen($_POST['password'])>15){
        call_back(2,"请输入6-15位密码");
    }else{
        $data['salt'] = mt_rand(100,999);
        $data['password'] = pwd_hash($_POST['password'],$data['salt']);
    }
    $data['utype'] = 1;
    $r = pdo_insert("members",$data);
    $_SESSION['uid'] = pdo_insertid();
    $_SESSION['utype'] = 1;
    if($r){
        call_back(1,"注册成功");
    }
}elseif($op=="send_code"){
    wl_load()->model('sms');
    if(check_phone($_GPC['mobie'])){
        $phone =$_GPC['mobie'];
    }
    if(!$_SESSION['last_sendtime'])
    {
        $_SESSION['phone_code']=mt_rand(1000,9999);
        $_SESSION['last_sendtime']=time();
        if(sendSms($phone,$_SESSION['phone_code'])){
            call_back(1,"ok");
        }
    }
    else
    {
        if ( (time() - $_SESSION['last_sendtime']) >50 )
        {
            $_SESSION['phone_code']=mt_rand(1000,9999);
            $_SESSION['last_sendtime']=time();
            if(sendSms($phone,$_SESSION['phone_code'])){
                call_back(1,"ok");
            }
        }else{
            return false;
        }
    }
}


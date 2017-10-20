<?php
defined('IN_IA') or exit('Access Denied');
wl_load()->model('verify');

if($op=="index"){
    unset($_SESSION['uid']);
    unset($_SESSION['utype']);
    include wl_template("member/login");
}elseif ($op=="login"){
    $username = check_pasre($_GPC['user_name'],"请输入您的手机号/用户名");
    $member = member_exists($username);
    if($member){
        $password = pwd_hash($_GPC['password'],$member['salt']);
        if($password==$member['password']){
            $_SESSION['uid'] = $member['id'];
            $_SESSION['utype'] = $member['utype'];
            if($member['utype']==1){
                $resume = m("resume")->get_resume( $_SESSION['uid']);
                if(!$resume['fullname']){
                    $url = web_url('resume/resume_reg/1');
                }elseif (!$resume['edu_experience']){
                    $url = web_url('resume/resume_reg/2');
                }elseif (!$resume['work_experience']){
                    $url = web_url('resume/resume_reg/3');
                }elseif (!$resume['introduce']){
                    $url = web_url('resume/resume_reg/4');
                }else{
                    $url = web_url('person/index');
                }
            }else{
                $company = m("company")->get_profile($member['id']);
                if(!$company['license'] || !$company['idcard1'] || !$company['idcard2']){
                    $url = web_url('member/company_register/step2');
                }elseif (!$company['nature'] || !$company['member'] || !$company['industry'] || !$company['district'] || !$company['slogan']|| !$company['tag']){
                    $url = web_url('member/company_register/step3');
                }else{
                    $url = web_url('company/index');
                }
            }

            call_back(1,$url);
        }else{
            call_back(2,"请输入正确的密码");
        }
    }
}elseif ($op=="forget_password"){
    include wl_template("member/forget_password");
}elseif ($op=="pwd_bytel"){
    if(check_phone($_GPC['tel'],2)){
        $phone =$_GPC['tel'];
        $member = pdo_fetch("select * from ".tablename('members')." where mobile=".$phone);
        if($member){
            if($_POST['yanzheng']==$_SESSION['phone_code']){
                $_SESSION['uid'] = $member['id'];
                call_back(1,web_url("member/login/set_password"));
            }else{
                call_back(2,"验证码不正确");
            }
        }else{
            call_back(2,"该手机号未注册");
        }
    }

}elseif ($op=="set_password"){
    if($_SESSION['uid']){
        include wl_template("member/set_password");
    }else{
        include wl_template("member/login");
    }
}elseif ($op=="bind_account"){
    include wl_template("member/create_bind_account");
}elseif ($op=="send_code"){

    wl_load()->model('sms');
    if($_GPC['style']=="tel"){
        if(check_phone($_GPC['tel'],2)){
            $phone =$_GPC['tel'];
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
}elseif ($op=="set_newpwd"){
        if($_GPC['new_password_ch'] && $_GPC['new_password_ch']){
            if($_GPC['new_password']==$_GPC['new_password_ch']){
                $member = m("member")->get_member($_SESSION['uid']);
                $password = pwd_hash($_GPC['new_password'],$member['salt']);
                $r = pdo_update("members",array('password'=>$password),array("id"=>$_SESSION['uid']));
                if($r){
                    call_back(1,"修改成功");
                }
            }else{
                call_back(2,"两次密码不一致");
            }
        }else{
            call_back(2,"请输入密码");
        }
}
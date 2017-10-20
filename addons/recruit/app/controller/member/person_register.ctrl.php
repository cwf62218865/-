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
    $data['createtime'] = time();

    $r = pdo_insert(WL."members",$data);
//    $r = insert_table($data,WL."members");
    if($r){
        $_SESSION['uid'] = pdo_insertid();
        $_SESSION['utype'] = 1;
        call_back(1,"注册成功");
    }else{
        call_back(1,"注册失败");
    }
}elseif($op=="send_code"){

    send_codes($_POST['mobie']);
}elseif ($op=="feelings"){
    if($_SESSION['utype']==1){
        $resume = m('resume')->get_resume($_SESSION['uid']);
        if(!$resume['fullname']){
            $url = app_url('resume/resume_reg/1');
        }elseif (!$resume['edu_experience']){
            $url = app_url('resume/resume_reg/2');
        }elseif (!$resume['work_experience']){
            $url = app_url('resume/resume_reg/3');
        }elseif (!$resume['introduce']){
            $url = app_url('resume/resume_reg/4');
        }else{
            $url = app_url('person/index');
        }
        include wl_template("resume/first_index");
    }else{
        die("只有求职者用户才能访问");
    }

}elseif ($op==""){
    
}


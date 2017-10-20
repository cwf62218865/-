<?php
defined('IN_IA') or exit('Access Denied');
wl_load()->model('api');
if($_SESSION['uid']){
    $resume = m("resume")->get_resume($_SESSION['uid']);
}

if($op=="1"){
    $identity = encrypt($_SESSION['uid'], 'E');
    include wl_template('resume/resume_reg1');
}elseif($op=="2"){
    $edu_experience = unserialize($resume['edu_experience']);

    include wl_template('resume/resume_reg2');
}elseif($op=="3"){
    $work_experience = unserialize($resume['work_experience']);
    include wl_template('resume/resume_reg3');
}elseif($op=="4"){
    include wl_template('resume/resume_reg4');
}elseif ($op=="step1_save"){
    $data['headimgurl'] = $_POST['headimgurl'];
    $data['sex'] = check_pasre($_POST['sex'],"请输入性别");
    $data['fullname'] = check_pasre($_POST['user_name'],"请输入姓名");
    $data['telphone'] = check_pasre($_POST['telphone'],"请输入手机号");
    $data['email'] = check_pasre($_POST['email'],"请输入邮箱");
    $data['city'] = check_pasre($_POST['city'],"请输入城市 ");
    $data['city_area'] = $_POST['city_area'];
    $data['address'] = $_POST['address'];
    $resume = m("resume")->get_resume($_SESSION['uid']);
    if($resume){
        $data['updatetime'] = time();
        $r = pdo_update(WL."resume",$data,array("uid"=>$_SESSION['uid']));
    }else{
        $data['uid'] = $_SESSION['uid'];
        $data['addtime'] = time();
        $r = pdo_insert(WL."resume",$data);
    }

    if($r){
        call_back(1,app_url("resume/resume_reg/2"));
    }
}elseif ($op=="step2_save"){

    $data['edu_experience'] = serialize($_POST['data']);
    $data['updatetime'] = time();
    $r = pdo_update(WL."resume",$data,array("uid"=>$_SESSION['uid']));
    if($r){
        call_back(1,app_url("resume/resume_reg/3"));
    }
}elseif ($op=="step3_save"){
    $data['work_experience'] = serialize($_POST['data']);
    $data['updatetime'] = time();
    $r = pdo_update(WL."resume",$data,array("uid"=>$_SESSION['uid']));
    if($r){
        call_back(1,app_url("resume/resume_reg/4"));
    }
}elseif ($op=="step4_save"){
    $data['nation'] = check_pasre($_POST['family_name'],"请选择民族");
    $data['political_status'] = check_pasre($_POST['identity'],"请选择政治面貌");
    $data['origin_place'] = check_pasre($_POST['place'],"请选择籍贯");

    $data['birthday'] = check_pasre($_POST['birthday'],"请选择出生年月");
    $data['introduce'] = check_pasre($_POST['introduce'],"请介绍下自己");
    $data['updatetime'] = time();
    $r = pdo_update(WL."resume",$data,array("uid"=>$_SESSION['uid']));
    if($r){
        call_back(1,app_url("person/index/send_resume"));
    }
}elseif ($op=="upload"){
    var_dump($_FILES);exit();
}


<?php
defined('IN_IA') or exit('Access Denied');
wl_load()->model('api');
if($_SESSION['uid']){
    $resume = m("resume")->get_resume($_SESSION['uid']);
}
$back_top = 1;
if($op=="1"){
    $identity = encrypt($_SESSION['uid'], 'E');
    $member = m("member")->get_member($_SESSION['uid']);
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

    $data['headimgurl'] = file_transfer($_POST['headimgurl']);
    $data['sex'] = check_pasre($_POST['sex'],"请输入性别");
    $data['fullname'] = check_pasre($_POST['user_name'],"请输入姓名");
    $data['telphone'] = check_pasre($_POST['telphone'],"请输入手机号");
    $data['email'] = check_pasre($_POST['email'],"请输入邮箱");
    $data['city'] = $_POST['city'];
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
//    var_dump($_POST);exit();
    $data['edu_experience'] = serialize($_POST['data']);
    $experience_info = m("resume")->extract_experience_info($data);
    $data['major'] = $experience_info['major'];
    $data['education'] = $experience_info['education'];
    $data['school_name'] = $experience_info['school_name'];
    $data['updatetime'] = time();
    $r = pdo_update(WL."resume",$data,array("uid"=>$_SESSION['uid']));
    if($r){
        call_back(1,app_url("resume/resume_reg/3"));
    }
}elseif ($op=="step3_save"){
    if(array_filter($_POST['data'][0])){
        $data['work_experience'] = serialize($_POST['data']);
        $experience_info = m("resume")->extract_experience_info($data);
        $data['experience'] =$experience_info['experience'];
        $data['updatetime'] = time();
        $r = pdo_update(WL."resume",$data,array("uid"=>$_SESSION['uid']));
    }else{
            $data['work_experience'] = "";
            $data['updatetime'] = time();
        $r = pdo_update(WL."resume",$data,array("uid"=>$_SESSION['uid']));
    }

    call_back(1,app_url("resume/resume_reg/4"));
}elseif ($op=="step4_save"){
    $data['nation'] = check_pasre($_POST['family_name'],"请选择民族");
    $data['political_status'] = check_pasre($_POST['identity'],"请选择政治面貌");
    $data['origin_place'] = check_pasre($_POST['place'],"请选择籍贯");

    $data['birthday'] = check_pasre($_POST['birthday'],"请选择出生年月");
    $data['birthday'] = strtotime(str_replace(".","-",$data['birthday']));
    $data['introduce'] =$_POST['introduce'];
    $data['updatetime'] = time();
    $r = pdo_update(WL."resume",$data,array("uid"=>$_SESSION['uid']));
    if($r){
        call_back(1,app_url("person/index/send_resume"));
    }
}elseif ($op=="upload"){
    var_dump($_FILES);exit();
}


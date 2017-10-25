<?php

defined('IN_IA') or exit('Access Denied');

if($op=="index"){
    include wl_template('company/index');
}
elseif ($op=="job_manage"){
//    $jobs = pdo_fetchall("select * from ".tablename(WL."jobs")." where uid=".$_SESSION['uid']." order by open desc");
    $jobs = m('jobs')->getall_jobs($_SESSION['uid']);
    include wl_template('company/job_manage');
}
elseif ($op=="job_manage_release"){
    $jobs = m('jobs')->getall_jobs($_SESSION['uid']);
    if($_GPC['job_id']){
        $jobs = pdo_fetch("select * from ".tablename(WL."jobs")." where id=".$_GPC['job_id']." and uid=".$_SESSION['uid']);
//        var_dump($jobs);exit();
    }

    $company = m('company')->get_profile($_SESSION['uid']);
    if(!$company['atlas'] ||!$company['introduce']){
        include wl_template("company/company_nomessage");exit();
    }
    include wl_template('company/job_manage_release');
}
elseif ($op=="step2_save"){
    $data['companyname'] =check_pasre($_POST['companyname'],"请输入公司名称");
    $data['license'] =check_pasre($_POST['license'],"请上传营业执照");
    $data['idcard1'] =check_pasre($_POST['idcard1'],"请上传法人身份证(正面)");
    $data['idcard2'] =check_pasre($_POST['idcard2'],"请上传法人身份证(反面)");
    $company = pdo_fetch("select id from ".tablename(WL.'company_profile')." where uid=".$_SESSION['uid']);
    if($company){
        $r = pdo_update(WL."company_profile",$data,array('uid'=>$_SESSION['uid']));
    }else{
        $data['uid'] = $_SESSION['uid'];
        $data['createtime']=time();
        $r = pdo_insert(WL."company_profile",$data);
    }
    call_back(1,app_url("company/company_register/step3"));
}elseif ($op=="step3"){
    include wl_template("company/company_reg3");
}elseif ($op=="step3_save"){
    $data['nature'] =check_pasre($_POST['company_property'],"请选择公司性质");
    $data['number'] =check_pasre($_POST['company_scale'],"请选择公司规模");
    $data['industry'] =check_pasre($_POST['company_industry'],"请选择所处行业");
    $data['district'] =check_pasre($_POST['company_city'],"请选择所处地区");
    $data['slogan'] =check_pasre($_POST['slogan'],"slogan不能为空");
    $data['tag'] =check_pasre($_POST['welfare'],"请至少选择一个福利标签");
    $r = pdo_update(WL."company_profile",$data,array('uid'=>$_SESSION['uid']));
    if($r){
        call_back(1,app_url("company/index"));
    }
}


elseif($op=="manage_resume"){

    $resume  = m("company")->getall_resume($_SESSION['uid'],0,2);
    $resume1 =m("resume")->getall_resume();
    $arr = pdo_fetchall("select r.* from ".tablename(WL."jobs_apply")." as j,".tablename(WL."resume")." as r  where j.resume_id=r.id and j.offer=1 and j.status=3 and j.uid=".$_SESSION['uid']);
    $evaluate = "";
    foreach ($arr as $resume){
        $edu_experience = unserialize($resume['edu_experience']);
        $education = "";
        $id = "";
        $edu = array('专科以下','专科','本科','硕士','博士','博士以上');
        $arr_edu = array_flip($edu);
        foreach ($edu_experience as $key=>$list){
            $value = $list['edu_district'];
            if(empty($education)){
                $education = $arr_edu[$value];
                $id = $key;
            }else{
                if($arr_edu[$value]>$education){
                    $education = $arr_edu[$value];
                    $id = $key;
                }
            }
        }
        $resume['education'] = $edu[$education];
        $resume['arr_education'] = $edu_experience[$id];
        $work_experience = unserialize($resume['work_experience']);

        $work_time = "";
        foreach ($work_experience as $list){
            $list['job_starttime'] = str_replace("月","",str_replace("年","-",$list['job_starttime']));
            $time = strtotime($list['job_starttime']);
            if(empty($work_time)){
                $work_time = $time;
            }else{
                if($time<$work_time){
                    $work_time = $time;
                }
            }
        }
        $resume['work_time'] =  date('Y')-date('Y',$work_time);
        $evaluate[] = $resume;
    }

    include wl_template('company/hr_manage_resume');
}

//职位搜索
elseif ($op=="job_name_search"){
    var_dump($_POST);exit();
}
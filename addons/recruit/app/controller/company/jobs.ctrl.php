<?php
defined('IN_IA') or exit('Access Denied');
$company =  m("company")->get_profile($_SESSION['uid']);
//职位列表查看
if($op=="show_jobs"){
    $jobs_id = $_GPC['jobs_id'];
    $jobs = m("jobs")->get_jobs($jobs_id);
    include wl_template("");
}

//职位编辑页面
elseif ($op=="edit_jobs"){
    include wl_template("");
}

//发布职位
elseif ($op=="send_jobs"){
//    var_dump($_GPC['data']['area']);exit();
    $data['jobs_name'] = check_pasre($_GPC['data']['job_name'],"请输入职位名称");
    $data['duty'] = check_pasre($_GPC['data']['job_duty'],"请输入岗位职责");
    $data['require'] = check_pasre($_GPC['data']['job_requirement'],"请输入任职要求");
    $data['number_range'] = check_pasre($_GPC['data']['job_persons'],"请输入招聘人数");
    $data['work_nature'] = check_pasre($_GPC['data']['job_nature'],"请输入工作性质");
    $data['experience'] = check_pasre($_GPC['data']['job_exp'],"请输入工作经验");
    $data['education'] = check_pasre($_GPC['data']['education'],"请输入学历");
    $data['city'] = check_pasre($_GPC['data']['city'],"请输入所在城市");
    $data['city_area'] = check_pasre($_GPC['data']['area'],"请输入所在区域");
    $data['address'] = $_GPC['data']['address'];
    $_GPC['salary'] = check_pasre($_GPC['data']['salary'],"请输入薪资");
    $salary = explode("-",$_GPC['salary']);
    $data['wage_min'] = str_replace("k","",$salary[0]);
    $data['wage_max'] = str_replace("k","",$salary[1]);
    $data['tag'] = $_GPC['data']['tag'];
    $data['uid'] =$company['uid'];
    $data['companyname'] =$company['companyname'];
//    var_dump($data);exit();
    if($_GPC['data']['job_id']){
        $data['updatetime'] =time();
        $data['open'] =1;
        $r = pdo_update(WL."jobs",$data,array('id'=>$_GPC['data']['job_id']));
//        $r = insert_table($data,WL."jobs");
    }else{
        $data['addtime'] =time();
        $data['updatetime'] =time();
        $r = insert_table($data,WL."jobs");
    }
    if($r){
        call_back(1,"ok");
    }else{
        call_back(2,"no");
    }
}

//修改职位
elseif ($op=="save_jobs"){
    $jobs_id = check_pasre($_GPC['jobs_id'],"参数错误");
    $data['jobs_name'] = check_pasre($_GPC['jobs_name'],"请输入职位名称");
    $data['city'] = check_pasre($_GPC['city'],"请输入所在城市");
    $data['city_area'] = check_pasre($_GPC['city_area'],"请输入所在区域");
    $data['wage_min'] = check_pasre($_GPC['wage_min'],"请输入最低薪资");
    $data['wage_max'] = check_pasre($_GPC['wage_max'],"请输入最高薪资");
    $data['tag'] = $_GPC['tag'];
    $data['uid'] =$company['uid'];
    $data['addtime'] =time();
    $data['companyname'] =$company['companyname'];
    $r = pdo_update(WL."jobs",$data,array('id'=>$jobs_id));
    if($r){
        call_back(1,"ok");
    }else{
        call_back(2,"no");
    }
}

//停止招聘
elseif ($op=="stop_job"){
    if($_POST['data_id']){
        $r = pdo_update(WL."jobs",array('open'=>0),array('id'=>$_POST['data_id'],'uid'=>$_SESSION['uid']));
    }
    if($r){
        call_back(1,"ok");
    }else{
        call_back(2,"no");
    }
}

//删除职位
elseif ($op=="delete_jobs"){
    if($_POST['data_id']){
        $r = pdo_delete(WL."jobs",array('id'=>$_POST['data_id'],'uid'=>$_SESSION['uid']));
    }
    if($r){
        call_back(1,"ok");
    }else{
        call_back(2,"no");
    }
}


//搜索匹配职位的岗位职责和任职要求
elseif ($op=="search_position"){
    if($_POST['job']){
        $position = pdo_fetch("select * from ".tablename(WL."position")." where position like '%".$_POST['job']."%' limit 0,1");

        $duty = unserialize($position['duty']);
        $require = unserialize($position['require']);
        $page = $_POST['page']-1;
        if($page*2>count($duty) || $page*2>count($require)){
            $page = 0;
        }
        call_back(1,array_slice($duty,$page*2,2),array_slice($require,$page*2,2));
    }

}



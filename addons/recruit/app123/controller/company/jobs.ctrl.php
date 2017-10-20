<?php
defined('IN_IA') or exit('Access Denied');

//职位查看
if($op=="show_jobs"){
    $jobs_id = $_GPC['jobs_id'];
    $jobs = m("jobs")->get_jobs($jobs_id);
    include wl_template("");
}elseif ($op=="save_jobs"){
    $jobs_id = $_GPC['jobs_id'];
    $r = update_table($_POST,"jobs");
    if($r){
        call_back(1,"");
    }else{
        call_back(2,"修改失败");
    }
}elseif ($op==""){
    
}
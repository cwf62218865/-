<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/1 0001
 * Time: 09:26
 */
//添加新评论
/*
 * 评论体系;
 * 面试流程，简历与职位关联id传递，
 */
if($op=="add_comment"){
    $apply_id = check_pasre($_POST['apply_id'],"参数错误");
    $data['content'] = check_pasre($_POST['content'],"请输入评论内容");
    $data['evaluate_information'] = check_pasre($_POST['evaluate_information'],"请评价信息真实性");
    $data['evaluate_environment'] = check_pasre($_POST['evaluate_environment'],"请评价公司环境");
    $data['evaluate_interviewer'] = check_pasre($_POST['evaluate_interviewer'],"请评价面试官");
    $apply_jobs=apply_jobs($apply_id);
    $data['uid'] = $apply_jobs['uid'];
    $data['puid'] = $apply_jobs['puid'];
    $data['resume_id'] = $apply_jobs['resume_id'];
    $data['jobs_id'] = $apply_jobs['jobs_id'];
    $data['createtime'] = time();
    $r = insert_table($data,WL."comment");
    if($r){
        call_back(1,"ok");
    }else{
        call_back(2,"no");
    }
}
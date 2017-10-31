<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/21 0021
 * Time: 10:18
 */

//修改个人职位订阅器
if($op=="save_order_jobs"){

//    $data['jobs_name'] = check_pasre($_GPC['job_name'],"请输入职位名称");
//    $data['work_place'] = check_pasre($_GPC['gz_address'],"请选择工作地点");
//    $data['wage_range'] = check_pasre($_GPC['salery'],"请选择月薪");
//    $data['trade'] = check_pasre($_GPC['hy_district'],"请选择行业");
    $data['order_time'] = check_pasre($_GPC['dy_pinlv'],"请选择订阅频率");


    $data['jobs_name'] = $_GPC['job_name'];
    $data['work_place'] = $_GPC['gz_address'];
    $data['wage_range'] = $_GPC['salery'];
    $data['trade'] = $_GPC['hy_district'];
    $data['order_time'] = $_GPC['dy_pinlv'];
    $order_jobs = pdo_fetch("select id from ".tablename(WL."order_jobs")." where puid=".$_SESSION['uid']);
    $order_jobs_ids = m("jobs")->show_order_jobs($data,"id");
    $data['order_jobs_ids'] = "";
    foreach ($order_jobs_ids as $list){
        $data['order_jobs_ids'] .=$list['id'].",";
    }
    $data['order_jobs_ids'] = substr($data['order_jobs_ids'],0,-1);
    if($order_jobs){
        $data['updatetime'] =time();
        $r =pdo_update(WL."order_jobs",$data,array('puid'=>$_SESSION['uid']));
    }else{
        $data['createtime'] =time();
        $data['updatetime'] =time();
        $data['puid'] =$_SESSION['uid'];
        $r = insert_table($data,WL."order_jobs");
    }

    if($r){
        call_back(1,"ok");
    }else{
        call_back(2,"no");
    }
}

//删除订阅器
elseif ($op=="reomve_order_jobs"){
    $order_jobs = pdo_fetch("select id from ".tablename(WL."order_jobs")." where puid=".$_SESSION['uid']);
    if($order_jobs){
        pdo_delete(WL."order_jobs",array('puid'=>$_SESSION['uid']));
        call_back(1,"ok");
    }else{
        call_back(2,"没有可删除的订阅器");
    }
}


//取消收藏职位
elseif ($op=="remove_collect_jobs"){
    $data['id'] = check_pasre($_GPC['collect_jobs_id'],"参数错误");
    $collect_jobs = pdo_fetch("select id from ".tablename(WL."collect_jobs")." where id=".$data['id']);
    if($collect_jobs){
        $data['uid'] = $_SESSION['uid'];
        pdo_delete(WL."collect_jobs",$data);
        call_back(1,"取消收藏成功");
    }
}


//职位申请处理
elseif ($op=="apply_deal"){
    $data['apply_id'] = check_pasre($_POST['dataid'],'参数错误');
    $r = pdo_update(WL."jobs_apply",array('offer'=>1),array('id'=>$data['apply_id']));
    if($r){
        call_back(1,"ok");
    }else{
        call_back(2,"no");
    }
//    var_dump($_POST);exit();
}
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/21 0021
 * Time: 10:20
 */

//收藏职位
if($op=="collection_jobs"){
    $data['jobs_id'] = check_pasre($_GPC['jobs_id'],"参数错误");
    $data['uid'] = $_SESSION['uid'];
    $data['createtime'] = time();
    $collect_jobs = pdo_fetch("select id from ".tablename(WL."collect_jobs")." where uid=".$data['uid']." and jobs_id=".$data['jobs_id']);
    if($collect_jobs){
        call_back(2,"该职位已收藏");
    }else{
        $r = insert_table($data,WL."collect_jobs");
        if($r){
            call_back(1,"收藏成功");
        }else{
            call_back(2,"收藏失败");
        }
    }
}

//取消收藏
elseif ($op=="remove_collection_jobs"){
    $data['jobs_id'] = check_pasre($_GPC['jobs_id'],"参数错误");
    $data['uid'] = $_SESSION['uid'];
    $r = delete_table($data,WL."collect_jobs");
    if($r){
        call_back(1,"取消成功");
    }else{
        call_back(2,"取消失败");
    }
}

//职位订阅器执行处理
elseif ($op=="subscribe_jobs_action"){
    $order_jobs = pdo_fetch("select * from ".tablename(WL."order_jobs")." where puid=".$_SESSION['uid']);
    if(($order_jobs['updatetime']-time())>60*60*24*7){
        pdo_update(WL."order_jobs",array('updatetime'=>time()),array('puid'=>$_SESSION['uid']));
    }
}

//订阅职位
elseif ($op=="subscribe_jobs"){

}

elseif ($op=="subscribe_jobs_list"){

}
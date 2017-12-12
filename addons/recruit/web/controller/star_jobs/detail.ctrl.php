<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/28 0028
 * Time: 13:46
 */
wl_load()->model('verify');


if ($_POST['token']=="edit"){
    $data = $_POST;
    if($data['uid']){
        $data['createtime'] = time();
        if($data['jobs_id']){
            unset($data['token']);
            unset($data['submit']);
            unset($data['jobs_id']);

            $r = pdo_update(WL."star_jobs",$data,array('id'=>$_POST['jobs_id']));
        }else{
            $r = insert_table($data,WL."star_jobs");
        }

        if($r){
            message('职位信息保存成功 ！', web_url('star_jobs/detail',array('uid'=>$data['uid'])), 'success');exit();
        }else{
            message('职位信息保存失败 ！', web_url('star_jobs/detail',array('uid'=>$data['uid'])), 'error');exit();
        }
    }else{
        message('参数错误 ！', web_url('star_jobs/detail',array('uid'=>$data['uid'])), 'error');exit();
    }

}

if($_GPC['jobs_id']){
    $list = pdo_fetch("select * from ".tablename(WL."star_jobs")." where id=".$_GPC['jobs_id']);
}
include wl_template('star_jobs/detail');exit();
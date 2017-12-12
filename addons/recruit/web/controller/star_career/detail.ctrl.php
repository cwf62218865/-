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
    $data['career_starttime'] = strtotime($data['career_starttime']);
    $data['career_endtime'] = strtotime($data['career_endtime']);
    if($data['uid']){
        $data['createtime'] = time();
        if($data['career_id']){
            unset($data['token']);
            unset($data['submit']);
            unset($data['career_id']);

            $r = pdo_update(WL."star_career",$data,array('id'=>$_POST['career_id']));
        }else{
            $r = insert_table($data,WL."star_career");
        }

        if($r){
            message('宣讲会信息保存成功 ！', web_url('star_career/detail',array('uid'=>$data['uid'])), 'success');exit();
        }else{
            message('宣讲会信息保存失败 ！', web_url('star_career/detail',array('uid'=>$data['uid'])), 'error');exit();
        }
    }else{
        message('参数错误 ！', web_url('star_career/detail',array('uid'=>$data['uid'])), 'error');exit();
    }

}

if($_GPC['career_id']){
    $list = pdo_fetch("select * from ".tablename(WL."star_career")." where id=".$_GPC['career_id']);
}
include wl_template('star_career/detail');exit();
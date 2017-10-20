<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/21 0021
 * Time: 10:14
 */

defined('IN_IA') or exit('Access Denied');
if($op=="index"){
    m("resume")->get_resume();
}
//我收到的简历
elseif ($op=="received_resume"){
    $jobs_id = isset($_GPC['jobs_id'])?$_GPC['jobs_id']:"";
//    $page = $_POST['page']?$_POST['page']:0;
    if($_POST['page']){
        $page = $_POST['page']-1;
    }else{
        $page = 0;
    }
    $received_resume  = m("company")->getall_resume($_SESSION['uid'],$page,1,$jobs_id);

    if($_POST['page']){
        call_back(1,$received_resume);
    }
    $job = pdo_fetchall("select * from ".tablename(WL."jobs")." where uid=".$_SESSION['uid']);

    $collect_resume  = m("company")->getall_collect($_SESSION['uid']);
    
    include wl_template("company/receive_resume");
}

//我收到的简历分页处理
elseif ($op=="received_resume_page"){
    if($_POST['page']){
        $page = $_POST['page']-1;
        $received_resume  = m("company")->getall_resume($_SESSION['uid'],$page,1);
        $html = "";
        foreach ($received_resume as $list){
            if($list['sex']==2){$list['sex'] = '女生';}else{$list['sex'] = '男生';}
            $list['age']=ceil(date('Y-m-d')-$list['birthday']);
            $html .=
                "<div class=\"list_item \" data-id=\"{$list['id']}\">
                    <div class=\"item_con\">
                        <a class=\"touxiang_pic\" href=".app_url('resume/index',array('uid'=>$list['uid']))." target=\"_blank\">
                            <img src=\"{$list['headimgurl']}\" style=\"width: 100px;\">
                        </a>
                        <div class=\"basic_massage\">
                            <div class=\"bm_hang1\">
                                <span class=\"name\">{$list['fullname']}</span>
                                <a class=\"view_i\" href=".app_url('resume/index',array('uid'=>$list['uid']))." target=\"_blank\">查看</a>
                                <span class=\"hope_job\">{$list['jobs_name']}</span>
                            </div>
                            <div class=\"bm_hang2\">
                                <span class=\"basic_xx\">{$list['sex']}</span>
                                <span class=\"basic_xx\" style=\"margin-left: 10px;\">{$list['age']}岁</span>
                                <span class=\"basic_xx\" style=\"margin-left: 26px;\">{$list['education']}</span>
                            </div>
                            <div class=\"bm_hang3\">
                                <span class=\"basic_xx\">{$list['school_name']}</span>
                                <span class=\"basic_xx\" style=\"margin-left: 16px;\">{$list['edu_major']}</span>
                            </div>
                            <div class=\"bm_hang4\">
                                <span class=\"basic_xx\">工作经验<span>{$list['work_time']}年</span></span>
                                <span class=\"basic_xx\" style=\"margin-left: 16px;\">{$list['telphone']}</span>
                            </div>
                        </div>
                        <div class=\"xian1\"></div>
                        <div class=\"status\">
                            <p class=\"time\">
                                <span>期望职位：<label class=\"job_hope\">播音</label><label class=\"job_hope\">编导</label><label class=\"job_hope\">记者</label></span>
                            </p>
                            <p class=\"review_tel\">
                                <span>期望薪资：<label>5k-8k</label></span>
                            </p>
                            <p class=\"review_address\">
                                <span>期望工作地点：<label>重庆</label></span>
                            </p>
                        </div>
                    </div>
                    <div class=\"review_statas\">
                                                    <div class=\"tongyi1 agree_review\" data-id=\"{$list['apply_id']}\">同意面试</div>
                            <div class=\"jujue1 refuse_review\" data-id=\"{$list['apply_id']}\">拒绝面试</div>
                            <div class=\"jujue1 shoucang_resume\" data-id=\"{$list['apply_id']}\">收藏简历</div>
                                           </div>
                </div>";
        }
       call_back(1,$html);
    }
}

//我收藏的简历分页处理
elseif ($op=="collect_resume_page"){
    if($_POST['page']){
        $page = $_POST['page']-1;
        $collect_resume  = m("company")->getall_collect($_SESSION['uid'],$page);
        $html = "";
        foreach ($collect_resume as $list){
            if($list['sex']==2){$list['sex'] = '女生';}else{$list['sex'] = '男生';}
            $list['age']=ceil(date('Y-m-d')-$list['birthday']);
            $html .=
                "<div class=\"list_item\">
                    <div class=\"item_con\">
                        <div class=\"touxiang_pic\">
                            <img src=\"{$list['headimgurl']}\" style=\"width: 100px;\">
                        </div>
                        <div class=\"basic_massage\">
                            <div class=\"bm_hang1\">
                                <span class=\"name\">{$list['fullname']}</span>
                                <span class=\"view_i\">查看</span>
                                <span class=\"hope_job\">{$list['jobs_name']}</span>
                            </div>
                            <div class=\"bm_hang2\">
                                <span class=\"basic_xx\">{$list['sex']}</span>
                                <span class=\"basic_xx\" style=\"margin-left: 10px;\">{$list['age']}岁</span>
                                <span class=\"basic_xx\" style=\"margin-left: 26px;\">{$list['education']}</span>
                            </div>
                            <div class=\"bm_hang3\">
                                <span class=\"basic_xx\">{$list['school_name']}</span>
                                <span class=\"basic_xx\" style=\"margin-left: 16px;\">{$list['edu_major']}</span>
                            </div>
                            <div class=\"bm_hang4\">
                                <span class=\"basic_xx\">工作经验<span>{$list['work_time']}年</span></span>
                                <span class=\"basic_xx\" style=\"margin-left: 16px;\">{$list['telphone']}</span>
                            </div>
                        </div>
                        <div class=\"xian1\"></div>
                        <div class=\"status\">
                            <p class=\"time\">
                                <span>期望职位：<label class=\"job_hope\">播音</label><label class=\"job_hope\">编导</label><label class=\"job_hope\">记者</label></span>
                            </p>
                            <p class=\"review_tel\">
                                <span>期望薪资：<label>5k-8k</label></span>
                            </p>
                            <p class=\"\">
                                <span>期望工作地点：<label>重庆</label></span>
                            </p>
                            <p class=\"shoucang_beizhu\">
                                <span>备注：</span>
                                <span style=\"margin-left: 18px\">1111</span>
                                <svg class=\"icon edit_ico cur\">
                                    <use xmlns:xlink=\"http://www.w3.org/1999/xlink\" xlink:href=\"#icon-xiugai\"></use>
                                </svg>
                            </p>
                        </div>
                    </div>
                    <div class=\"review_statas\">
                        <div class=\"tongyi yaoqing_interview\" data-id=\"{$list['telphone']}\">邀请面试</div>
                        <div class=\"jujue\" data-id=\"{$list['telphone']}\">取消收藏</div>
                    </div>
                </div>";
        }
        call_back(1,$html);
    }
}

//处理简历
elseif ($op=="check_resume_deal"){
    $jobs_apply_id = check_pasre($_GPC['jobs_apply_id'],"参数错误");
    $status = check_pasre($_GPC['status'],"参数错误");
    $r = pdo_update(WL."jobs_apply",array('status'=>$status),array('id'=>$jobs_apply_id,'uid'=>$_SESSION['uid']));
    if($status==2){
        $data['address'] = check_pasre($_GPC['address'],"请输入地址");
        $data['linker'] = check_pasre($_GPC['linker'],"请输入联系人");
        $data['interview_time'] = check_pasre($_GPC['interview_time'],"请输入面试时间");
        $jobs_apply = pdo_fetch("select * from ".tablename(WL."jobs_apply")." where id=".$jobs_apply_id);
        $interview = pdo_fetch("select * from ".tablename(WL.'interview')." where jobs_id=".$jobs_apply['jobs_id']." and resume_id=".$jobs_apply['resume_id']);
        if($interview){
            call_back(2,"操作异常");
        }else{
            $data['createtime'] = time();
            $data['uid'] = $jobs_apply['uid'];
            $data['resume_id'] = $jobs_apply['resume_id'];
            $data['jobs_id'] = $jobs_apply['jobs_id'];
            $data['puid'] = $jobs_apply['puid'];
            $r = insert_table($data,WL."interview");
            if ($r){
                call_back(1,"ok");
            }else{
                call_back(2,"no1");
            }
        }
    }
    if($r){
        call_back(1,"ok");
    }else{
        call_back(2,"no2");
    }
}

//拒绝面试
elseif ($op=="refuse_review"){
    if($_POST['apply_id']){
        $r = pdo_update(WL."jobs_apply",array('status'=>'-1'),array('id'=>$_POST['apply_id'],'uid'=>$_SESSION['uid']));
        if($r){
            call_back(1,"ok");
        }else{
            call_back(2,"no");
        }
    }
}

//面试邀请
elseif ($op=="send_review"){
    $data['apply_id'] = check_pasre($_POST['data_id'],"参数错误1");
    pdo_update(WL."jobs_apply",array('status'=>'3'),array('id'=>$data['apply_id'],'uid'=>$_SESSION['uid']));
    $jobs_apply = pdo_fetch("select resume_id,jobs_id,puid,uid from ".tablename(WL.'jobs_apply')." where id=".$data['apply_id']);
    $data['resume_id'] =$jobs_apply['resume_id'];
    $data['jobs_id'] =$jobs_apply['jobs_id'];
    $data['puid'] =$jobs_apply['puid'];
    $data['uid'] =$jobs_apply['uid'];
    $data['interview_time'] = check_pasre($_POST['reviewtime'],"参数错误2");
    $data['linker'] = check_pasre($_POST['contacts_name'],"参数错误3");
    $data['link_phone'] = check_pasre($_POST['contacts_tel'],"参数错误4");
    $data['city'] = check_pasre($_POST['city'],"参数错误5");
    $data['city_area'] = check_pasre($_POST['city_area'],"参数错误6");
    $data['address'] = check_pasre($_POST['detail_address'],"参数错误7");
//    var_dump($data);exit();
    $interview = pdo_fetch("select id from ".tablename(WL.'interview')." where apply_id=".$data['apply_id']);
    if($interview){
        call_back(2,"面试邀请已存在");
    }else{
        $data['createtime'] = time();
        $r = insert_table($data,WL."interview");
        if($r){
            call_back(1,"ok");
        }else{
            call_back(2,"no");
        }
    }
}

//收藏简历
elseif ($op=="collect"){
    $data_id = check_pasre($_POST['data_id'],"参数错误");
    $data['remark'] = check_pasre($_POST['beizhu'],"参数错误");
    $jobs_apply = pdo_fetch("select * from ".tablename(WL.'jobs_apply')." where id=".$data_id);
    $data['uid'] = $jobs_apply['uid'];
    $data['puid'] = $jobs_apply['puid'];
    $data['jobs_id'] = $jobs_apply['jobs_id'];
    $data['resume_id'] = $jobs_apply['resume_id'];
    $collect_resume = pdo_fetch("select id from ".tablename(WL.'collect_resume')." where uid=".$_SESSION['uid']." and resume_id=".$data['resume_id']);
    if($collect_resume){
        call_back(2,"该简历已收藏");
    }else{
        $data['createtime'] = time();
        $r = insert_table($data,WL."collect_resume");
        if($r){
            call_back(1,"ok");
        }else{
            call_back(2,"no");
        }
    }

}

//取消收藏
elseif ($op=="remove_collect"){
    $collect_id = check_pasre($_POST['data_id'],"参数错误");
    $r = pdo_delete(WL."collect_resume",array('id'=>$collect_id));
    if($r){
        call_back(1,"ok");
    }else{
        call_back(2,"no");
    }

}

//搜索职位检索简历
elseif ($op=="search_resume"){
    
}
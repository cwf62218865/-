<?php
defined('IN_IA') or exit('Access Denied');
wl_load()->model('api');
if($_SESSION['uid']){
    $resume = m("resume")->get_resume($_SESSION['uid']);
}

if($op=="index"){

    if(!$resume['fullname']){
        $url = app_url('resume/resume_reg/1');

    }elseif (!$resume['edu_experience']){
        $url = app_url('resume/resume_reg/2');

    }elseif (!$resume['work_experience']){
        $url = app_url('resume/resume_reg/3');

    }elseif (!$resume['introduce']){
        $url = app_url('resume/resume_reg/4');
    }
    include wl_template("resume/first_index");exit();
}elseif($op=="home"){
    include wl_template("resume/first_index");exit();
}

//已投递职位列表
elseif ($op=="send_resume"){
    if($_GPC['kind']=="interview"){
        $show = 2;
    }else{
        $show = 1;
    }
    $apply_jobs = m("resume")->jobs_apply($_SESSION['uid']);
    $interview_jobs = m("resume")->jobs_interview($_SESSION['uid']);
//    var_dump($apply_jobs);exit();
    include wl_template("person/send_resume");exit();
}

//投递管理投递记录分页
elseif ($op=="send_resume_ajax"){
    if($_POST['page']){
        $apply_jobs = m("resume")->jobs_apply($_SESSION['uid'],$_POST['page']);

        foreach ($apply_jobs as $list){
             $tag = "";
             foreach (array_filter(explode(",",$list['tag'])) as $li){
                 $tag .="<span class=\"fuli\">$li</span>";
             }

             if($list['wage_min']>0 && $list['wage_max']>0){
                 $salary = $list['wage_min']."-".$list['wage_max']."k";
             }else{
                 $salary = "面议";
             }
            $status = "";
            if ($list['status']==0){
                 $status = "<div class=\"status1\">HR未查看/待沟通</div>";
            }elseif ($list['status']==1){
                $status = "<div class=\"status2\">HR已查看</div>";
            }elseif ($list['status']==-1){
                $status = "<div class=\"status1\">HR已拒绝</div>";
            }elseif ($list['status']==3){
                $status = "<p class=\"time\">
                                    <svg class=\"icon icon_time\" aria-hidden=\"true\">
                                        <use xlink:href=\"#icon-shijian\"></use>
                                    </svg>
                                    <span>时间：{$list['interview']['interview_time']}</span>
                                </p>
                                <p class=\"review_tel\">
                                    <svg class=\"icon icon_tel\" aria-hidden=\"true\">
                                        <use xlink:href=\"#icon-lianxiren\"></use>
                                    </svg>
                                    <span>联系人：{$list['interview']['linker']}</span>
                                </p>
                                <p class=\"review_address\">
                                    <svg class=\"icon icon_address\" aria-hidden=\"true\">
                                        <use xlink:href=\"#icon-didian\"></use>
                                    </svg>
                                    <span>地址：{$list['interview']['address']}</span>
                                </p>
                                <div class=\"btn_ditu\" data-id=\"{$list['retoate_x']}.','.{$list['retoate_y']}\" data-city='
                {$list[city]}'></div>";
            }


            if($list['status']<>3){
             $pass = "pass";
            }else{
                $pass = "";
            }

            $html .= "<div class=\"list_item ".$pass."\">
                    <div class=\"item_con\">
                        <div class=\"hang1\">
                            <a class=\"jobname nowrap\" href='".app_url('member/index/jobs_detail',array('jobs_id'=>$list['id']))."'>{$list['jobs_name']}</a>
                            <a class=\"salary\">{$salary}</a>
                        </div>
                        <div class=\"hang2\">
                            <a class=\"company nowrap\" href='".app_url('member/index/company_detail',array('company_id'=>$list['uid']))."'>{$list['companyname']}</a>
                            <span class=\"address nowrap\">{$list['city']}</span>
                            <span class=\"date\">".date('Y-m-d',$list['updatetime'])."</span>
                        </div>
                        <div class=\"hang3\">
                                {$tag}     
                       </div>
                        <div class=\"xian1\"></div>
                        <div class=\"status\">                           
                                {$status}    
                        </div>
                    </div>
                </div>";
        }

        call_back(1,$html);
    }
}

//投递管理面试邀请分页
elseif ($op=="invite_ajax"){
    if($_POST['page']){
        $apply_jobs = m("resume")->jobs_interview($_SESSION['uid'],$_POST['page']);

        $html = "";
        foreach ($apply_jobs as $list){
            $tag = "";
            foreach (array_filter(explode(",",$list['tag'])) as $li){
                $tag .="<span class=\"fuli\">$li</span>";
            }


            $html .= "<div class=\"list_item\">
                    <div class=\"item_con\">
                        <div class=\"hang1\">
                            <a class=\"jobname nowrap\" href='".app_url('member/index/jobs_detail',array('jobs_id'=>$list['id']))."'>{$list['jobs_name']}</a>
                            <a class=\"salary\">{$list['wage_min']}-{$list['wage_max']}k</a>
                        </div>
                        <div class=\"hang2\">
                            <a class=\"company nowrap\" href='".app_url('member/index/company_detail',array('company_id'=>$list['uid']))."'>{$list['companyname']}</a>
                            <span class=\"address nowrap\">{$list['city']}</span>
                            <span class=\"date\">".date('Y-m-d',$list['updatetime'])."</span>
                        </div>
                        <div class=\"hang3\">
                                                        {$tag}
                                                    </div>
                        <div class=\"xian1\"></div>
                        <div class=\"status\">
                            <p class=\"time\">
                                <svg class=\"icon icon_time\" aria-hidden=\"true\">
                                    <use xmlns:xlink=\"http://www.w3.org/1999/xlink\" xlink:href=\"#icon-shijian\"></use>
                                </svg>
                                <span>时间：{$list['interview']['interview_time']}</span>
                            </p>
                            <p class=\"review_tel\">
                                <svg class=\"icon icon_tel\" aria-hidden=\"true\">
                                    <use xmlns:xlink=\"http://www.w3.org/1999/xlink\" xlink:href=\"#icon-lianxiren\"></use>
                                </svg>
                                <span>联系人：{$list['interview']['linker']}</span>
                            </p>
                            <p class=\"review_address\">
                                <svg class=\"icon icon_address\" aria-hidden=\"true\">
                                    <use xmlns:xlink=\"http://www.w3.org/1999/xlink\" xlink:href=\"#icon-didian\"></use>
                                </svg>
                                <span>地址：{$list['interview']['address']}</span>
                            </p>
                            <div class=\"btn_ditu\"></div>
                        </div>
                    </div>
                                        <div class=\"review_statas\">
                        <div class=\"tongyi\" data-id=\"{$list['apply_id']}\">同意面试</div>
                        <div class=\"jujue\" data-id=\"{$list['apply_id']}\">拒绝面试</div>
                    </div>
                                    </div>";
        }

        call_back(1,$html);
    }
}

//收藏职位列表
elseif ($op=="collection_jobs_list"){
    $collect_job = pdo_fetchall("select c.createtime,j.* from ".tablename(WL."collect_jobs")." as c,".tablename(WL."jobs")." as j where c.jobs_id=j.id and c.uid=".$_SESSION['uid']);
    $collect_jobs = "";
    foreach ($collect_job as $list){
        $headimgurl = pdo_fetch("select headimgurl,tag from ".tablename(WL."company_profile")." where uid=".$list['uid']);
        $jobs_apply = pdo_fetch("select id from ".tablename(WL."jobs_apply")." where puid=".$_SESSION['uid']." and jobs_id=".$list['id']);
        if(empty($jobs_apply)){
            $list['post_status'] = 1;
        }
        $list['headimgurl'] = $headimgurl['headimgurl'];
        $list['tag'] = $headimgurl['tag'];
        $collect_jobs[] = $list;
    }


    $order_jobs = pdo_fetch("select * from ".tablename(WL."order_jobs")." where puid=".$_SESSION['uid']);
    $differ_time = (time()-$order_jobs['updatetime'])/86400;
    $order_jobs_list = m("jobs")->check_order_jobs($order_jobs['order_time'],$differ_time);
    include wl_template("person/person_collection");exit();
}


//简历管理
elseif ($op=="resume_center"){
    include wl_template("person/person_collection");exit();
}

//投递简历
elseif ($op=="post_resume"){
//    var_dump($_POST);exit();
    $data['jobs_id'] = check_pasre($_POST['data_id'],"参数错误");
    $data['uid'] = check_pasre($_POST['uid'],"参数错误");
    $data['resume_id'] = $resume['id'];
    $data['puid'] = $resume['uid'];
    $data['direction'] = 2;
    $data['createtime'] = time();

    $jobs_apply = pdo_fetch("select * from ".tablename(WL."jobs_apply")." where jobs_id=".$data['jobs_id']." and resume_id=".$data['resume_id']);
    if($jobs_apply){
        call_back(2,"已存在");
    }else{
        $r = insert_table($data,WL."jobs_apply");
        if($r){
            call_back(1,"ok");
        }else{
            call_back(2,"no");
        }
    }

//    var_dump($_POST);exit();
}
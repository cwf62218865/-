<?php
defined('IN_IA') or exit('Access Denied');
wl_load()->model('api');
if($_SESSION['uid']){
    $resume = m("resume")->get_resume($_SESSION['uid']);
}

//二次元页面
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

/********************************************求职者主要页面*************************************/
//已投递职位列表
elseif ($op=="send_resume"){
    if($_GPC['kind']=="interview"){
        $show = 2;
    }else{
        $show = 1;
    }
    $apply_jobs = m("resume")->jobs_apply($_SESSION['uid']);
    $interview_jobs = m("resume")->jobs_interview($_SESSION['uid']);
    include wl_template("person/send_resume");exit();
}

//面试评价
elseif ($op=="credit_evaluate"){
    $agree_jobs = m("resume")->jobs_apply($_SESSION['uid'],-1,3);
    $data['puid'] = $_SESSION['uid'];
    $data['hr_reply'] = 1;
    $comment_jobs = m("jobs")->comment_apply($data);
    $comment_jobs = $comment_jobs['more'];
    include wl_template("person/credit_evaluate");exit();
}

//个人消息中心
elseif ($op=="person_msg"){
    $data['data']['guess'] =1;
    $jobs = m("jobs")->getall_jobs_page($data,5);
    $jobs = $jobs['more'];
    $msg = m("resume")->jobs_apply($_SESSION['uid'],-1,0);
    $new_msg = "";
    foreach ($msg as $list){
        $list['addtime'] = date("Y/m/d", $list['createtime']);
        $new_msg[] = $list;
    }
    $arr = "";
    foreach ($new_msg as $key=>$list){
        $arr[$list['addtime']][$key] = $list;
    }
    $new_arr = "";
    $i = 0;
    foreach ($arr as $key=>$list){
        $new_arr[$i]['time'] =$key;
        $new_arr[$i]['content'] = $list;
        $i++;
    }
    include wl_template("person/person_msg");exit();
}

//简历管理
elseif ($op=="resume_center"){
    include wl_template("person/person_collection");exit();
}

/****************************************ajax请求处理******************************************/
//消息请求
elseif ($op=="msg_deal"){
    if($_POST['msg']=="投递申请"){
        $jobs = m("resume")->jobs_apply($_SESSION['uid'],-1,1);
        $msg = "";
        foreach ($jobs as $list){
            $createtime = date("Y-m-d h:i",$list['createtime']);
            if($list['status']==-1){
                $status = "不合适";
            }elseif($list['status']==0){
                $status = "未查看";
            }elseif($list['status']==1){
                $status = "已查看";
            }elseif($list['status']==3){
                $status = "邀请面试";
            }
            $msg .= " <div class=\"day_msgbox\">
                        <div class=\"system_msg_title\">
                            <svg class=\"icon\" aria-hidden=\"true\">
                                <use xlink:href=\"#icon-qiuzhiyixiang\"></use>
                            </svg>
                            <span class=\"color999\">投递申请</span>
                            <span class=\"colorbbb\">{$createtime}</span>
                        </div>

                        <div class=\"delivery_left\">
                        <span class=\"delivery_logo\">
                            <img src=\"{$list['headimgurl']}\">
                        </span>
                            <span>{$list['jobs_name']}</span>
                            <span style=\"margin-left: 20px\">{$list['companyname']}</span><br>
                            <span style=\"display: inline-block;margin-top: 22px\">最新状态：{$status}</span>
                        </div>

                        <a href=\"#\" class=\"see_system_msg see_day_msg\" >查看详情>></a>
                    </div>";
        }

        call_back(1,$msg);
    }elseif ($_POST['msg']=="面试邀请"){
        $jobs = m("resume")->jobs_apply($_SESSION['uid'],-1,2);
        $msg = "";
        foreach ($jobs as $list){
            $createtime = date("Y-m-d h:i",$list['createtime']);

            $msg .= " <div class=\"day_msgbox\">
                        <div class=\"system_msg_title\">
                            <svg class=\"icon\" aria-hidden=\"true\">
                                <use xlink:href=\"#icon-gongzuojingli\"></use>
                            </svg>
                            <span class=\"color999\">面试邀请</span>
                            <span class=\"colorbbb\">{$createtime}</span>
                        </div>

                        <div class=\"delivery_left\">
                        <span class=\"delivery_logo\">
                            <img src=\"{$list['headimgurl']}\">
                        </span>
                            <span>{$list['jobs_name']}</span><br>
                            <span style=\"display: inline-block;margin-top: 22px\">{$list['companyname']}</span>
                        </div>

                        <a href=\"#\" class=\"see_system_msg see_day_msg\" >查看详情>></a>
                    </div>";
        }

        call_back(1,$msg);
    }elseif ($_POST['msg']=="面试评价"){
        $jobs = m("resume")->jobs_apply($_SESSION['uid'],-1,2);
        $msg = "";
        foreach ($jobs as $list){
            if($list['hr_reply_status']){
                $createtime = date("Y-m-d h:i",$list['createtime']);

                $msg .= " <div class=\"day_msgbox\">
                        <div class=\"system_msg_title\">
                            <svg class=\"icon\" aria-hidden=\"true\">
                                <use xmlns:xlink=\"http://www.w3.org/1999/xlink\" xlink:href=\"#icon-pinglunhuifu\"></use>
                            </svg>
                            <span class=\"color999\">面试评价</span>
                            <span class=\"colorbbb\">{$createtime}</span>
                        </div>

                        <div class=\"delivery_left\">
                        <span class=\"delivery_logo\">
                            <img src=\"/addons/recruit/app/resource/temp/2017-11-03/59fc31f650b71.jpg\">
                        </span>
                            <span><span style=\"color: #7b8fc9\">{$list['companyname']}</span>回复了您的面试评价</span>
                        </div>

                        <a href=\"#\" class=\"see_system_msg see_day_msg\">查看详情&gt;&gt;</a>
                    </div>";
            }
        }

        call_back(1,$msg);
    }elseif ($_POST['msg']=="问答提醒"){

    }elseif ($_POST['msg']=="评论回复"){

    }
    var_dump($_POST);exit();
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

//待评价保存
elseif ($op=="save_evaluate"){

    $pingfen = $_POST['pingfen'];
    $data['evaluate_information'] = intval($pingfen[0]);
    $data['evaluate_environment'] = intval($pingfen[1]);
    $data['evaluate_interviewer'] = intval($pingfen[2]);
    $data['tag'] = implode(",",$_POST['biaoqian']);
    $data['content'] = check_pasre($_POST['detail'],"请输入面试经过");
    $data['score'] = $_POST['pingjia'];
    $data['hide'] = $_POST['niming'];
    $apply_id=check_pasre($_POST['apply_id'],"参数错误");
    $apply_jobs = pdo_fetch("select * from ".tablename(WL."jobs_apply")." where id=".$apply_id);
    $data['uid'] = $apply_jobs['uid'];
    $data['puid'] = $apply_jobs['puid'];
    $data['resume_id'] = $apply_jobs['resume_id'];
    $data['jobs_id'] = $apply_jobs['jobs_id'];
    $data['createtime'] = time();
    $apply_jobs = pdo_fetch("select * from ".tablename(WL."comment")." where jobs_id=".$data['jobs_id']." and puid=".$data['puid']);
    if($apply_jobs){
        call_back(2,"您对该职位已评价,不要重复提交");
    }else{
        pdo_update(WL."jobs_apply",array("comment"=>1),array('id'=>$apply_id));
        $r = insert_table($data,WL."comment");
        if($r){
            call_back(1,"提交成功");
        }else{
            call_back(2,"保存失败");
        }
    }

}

//投递简历
elseif ($op=="post_resume"){
//    var_dump($_POST);exit();
    $data['jobs_id'] = check_pasre($_POST['data_id'],"参数错误");
    $data['uid'] = check_pasre($_POST['uid'],"参数错误");
    $data['resume_id'] = $resume['id'];
    $data['puid'] = $resume['uid'];
    $data['direction'] = 2;
    $data['offer'] = 1;
    $data['createtime'] = time();

    $jobs_apply = pdo_fetch("select * from ".tablename(WL."jobs_apply")." where jobs_id=".$data['jobs_id']." and resume_id=".$data['resume_id']);
    if(empty($_SESSION['uid'])){
        call_back(2,"请先登录账号");
    }
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



/*****************************************分页请求*************************************************/
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
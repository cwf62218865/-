<?php
defined('IN_IA') or exit('Access Denied');
wl_load()->model('verify');

//登录
if($op=="index"){
    unset($_SESSION['uid']);
    unset($_SESSION['utype']);
    include wl_template("member/login");exit();
}

//注册
elseif ($op=="register"){
    include wl_template("member/reg_screen");exit();
}

//登录处理
elseif ($op=="login_deal"){

    $username = check_pasre($_GPC['user_name'],"请输入您的手机号/用户名");
    $member = member_exists($username);

    if($member){
        $password = pwd_hash($_GPC['password'],$member['salt']);
        if($password==$member['password']){
            $_SESSION['uid'] = $member['id'];
            $_SESSION['utype'] = $member['utype'];
            if($member['utype']==1){

                $resume = m("resume")->get_resume( $_SESSION['uid']);
                if(!$resume['fullname']){
//                    $url = app_url('resume/resume_reg/1');
                    $url = app_url('person/index');
                }elseif (!$resume['edu_experience']){
//                    $url = app_url('resume/resume_reg/2');
                    $url = app_url('person/index');
                }elseif (!$resume['work_experience']){
//                    $url = app_url('resume/resume_reg/3');
                    $url = app_url('person/index');
                }elseif (!$resume['introduce']){
//                    $url = app_url('resume/resume_reg/4');
                    $url = app_url('person/index');
                }else{
                    $url = app_url('person/index/send_resume');
//                    $url = app_url('person/index');
                }
            }else{
                $company = m("company")->get_profile($member['id']);
                if(empty($company) ||!$company['license'] || !$company['idcard1'] || !$company['idcard2']){
                    $url = app_url('member/company_register/step2');
                }elseif (!$company['slogan']){
                    $url = app_url('member/company_register/step3');
                }else{
                    $url = app_url('company/resume/received_resume');
                }
            }

            call_back(1,$url);
        }else{
            call_back(2,"请输入正确的密码");
        }
    }
}


//忘记密码
elseif ($op=="forget_password"){
    include wl_template("member/forget_password");
}

//忘记密码手机验证
elseif ($op=="pwd_bytel"){
    if(check_phone($_GPC['tel'],2)){
        $phone =$_GPC['tel'];
        $member = pdo_fetch("select * from ".tablename(WL.'members')." where mobile=".$phone);
        if($member){
            if($_POST['yanzheng']==$_SESSION['phone_code']){
                $_SESSION['uid'] = $member['id'];
                call_back(1,app_url("member/login/set_password"));
            }else{
                call_back(2,"验证码不正确");
            }
        }else{
            call_back(2,"该手机号未注册");
        }
    }

}

//设置密码
elseif ($op=="set_password"){
    if($_SESSION['uid']){
        include wl_template("member/set_password");exit();
    }else{
        include wl_template("member/login");exit();
    }
}

//设置新密码处理
elseif ($op=="set_newpwd"){
    if($_GPC['new_password_ch'] && $_GPC['new_password_ch']){
        if($_GPC['new_password']==$_GPC['new_password_ch']){
            $member = m("member")->get_member($_SESSION['uid']);
            $password = pwd_hash($_GPC['new_password'],$member['salt']);
            $r = pdo_update(WL."members",array('password'=>$password),array("id"=>$_SESSION['uid']));
            if($r){
                call_back(1,"修改成功");
            }
        }else{
            call_back(2,"两次密码不一致");
        }
    }else{
        call_back(2,"请输入密码");
    }
}


//绑定账号
elseif ($op=="bind_account"){
    include wl_template("member/create_bind_account");exit();
}

//发送二维码
elseif ($op=="send_code"){

    wl_load()->model('sms');
    if($_GPC['style']=="tel"){
        if(check_phone($_GPC['tel'],2)){
            $phone =$_GPC['tel'];
        }
        if(!$_SESSION['last_sendtime'])
        {
            $_SESSION['phone_code']=mt_rand(1000,9999);
            $_SESSION['last_sendtime']=time();
            if(sendSms($phone,$_SESSION['phone_code'])){
                call_back(1,"ok");
            }
        }
        else
        {
            if ( (time() - $_SESSION['last_sendtime']) >50 )
            {
                $_SESSION['phone_code']=mt_rand(1000,9999);
                $_SESSION['last_sendtime']=time();
                if(sendSms($phone,$_SESSION['phone_code'])){
                    call_back(1,"ok");
                }
            }else{
                return false;
            }
        }
    }
    exit();
}


//手机上传头像界面
elseif ($op=="resume_headimgupload"){
    $kind = "简历头像上传";
    include wl_template("resume/mobileupload1");exit();
}

elseif ($op=="resume_worksupload"){
    $kind = "个人作品上传";
    include wl_template("resume/mobileupload1");exit();
}

//简历头像上传保存
elseif ($op=="headimgupload_deal"){
    if($_GPC['identity']){
        $id = encrypt($_GPC['identity'], 'D');
        $member = m("member")->get_member($id);
        if($member){
            $headimgurl = upload_img($_FILES);
            if($headimgurl){
                $data['uid'] = $id;
                $data['headimgurl'] = $headimgurl;
                $temp = pdo_fetch("select id from ".tablename(WL."members_temp")." where uid=".$id);
                if(empty($temp)){
                    $r = pdo_insert(WL."members_temp",$data);
                }
                if($r){
                    call_back(1,"1");
                }else{
                    call_back(2,"2");
                }
            }
        }
    }else{
        call_back(2,"11");
    }
}

//个人作品上传保存
elseif ($op=="worksupload_deal"){
    if($_GPC['identity']){
        $id = encrypt($_GPC['identity'], 'D');
        $member = m("member")->get_member($id);
        if($member){
            $worksurl = upload_img($_FILES);
            if($worksurl){
                $data['uid'] = $id;
                $data['works'] = $worksurl;
                $temp = pdo_fetch("select id from ".tablename(WL."members_temp")." where uid=".$id);
                if(empty($temp)){
                    $r = pdo_insert(WL."members_temp",$data);
                }
                if($r){
                    call_back(1,"1");
                }else{
                    call_back(2,"2");
                }
            }
        }
    }else{
        call_back(2,"11");
    }
}



elseif ($op=="add"){
    if($_POST['add']){
        $duty = $_POST['duty'];
        $data['duty']  = serialize(explode("\n",$duty));
        $require = $_POST['require'];
        $data['require']  = serialize(explode("\n",$require));
        $data['position'] = $_POST['position'];
        $data['addtime'] = time();
        $r = insert_table($data,WL."position");
        if($r){
            echo 1;
        }else{
            echo 2;
        }
        exit();
    }
//    include wl_template("position/add");
}


//公司详情页
elseif($op=="company_detail"){

    if($_GPC['company_id']) {
        $company = pdo_fetch("select * from ".tablename(WL."company_profile")." where id=".$_GPC['company_id']);
        include wl_template("member/company_pages");
        exit();
    }
}

//职位详情页
elseif($op=="jobs_detail"){
    if($_GPC['jobs_id']){
        $jobs = pdo_fetch("select * from ".tablename(WL."jobs")." where id=".$_GPC['jobs_id']);
        $company = pdo_fetch("select * from ".tablename(WL."company_profile")." where uid=".$jobs['uid']);
        $jobs_apply = pdo_fetch("select id from ".tablename(WL."jobs_apply")." where jobs_id=".$_GPC['jobs_id']." and puid=".$_SESSION['uid']);
//        echo "select id from ".tablename(WL."jobs_apply")." where jobs_id=".$_GPC['jobs_id']." and puid=".$_SESSION['uid'];exit();
//        var_dump($jobs_apply);exit();
        include wl_template("member/jobs_detail");exit();
    }

}

//职位搜索
elseif ($op=="search_jobs"){
    $jobs_count = pdo_fetchcolumn("select count(*) from ".tablename(WL."jobs"));
//    echo $jobs_count;exit();
    $jobs = m("jobs")->getall_jobs_page();
    include wl_template("member/search_jobs");exit();
}

//职位搜索ajax
elseif ($op=="search_jobs_ajax"){

    if ($_POST['data']){
        $page = $_POST['data']['page'];
        $jobs = m("jobs")->getall_jobs_page($page);
        $html = "";
        foreach ($jobs as $key=>$list){
            if($list['post_status']=="已投递"){
                $toudijianli = "toudijianli1";
            }else{
                $toudijianli = "toudijianli";
            }
            $list['updatetime'] = date("Y-m-d",$list['updatetime']);
            $html .=
                "<div class=\"list_item\">
                    <div class=\"item_con\">
                        <div class=\"hang1\">
                            <a class=\"jobname nowrap\" href='".app_url('member/index/jobs_detail',array('jobs_id'=>$list['id']))."'>{$list['jobs_name']}</a>
                            <label class=\"salary\">{$list['wage_min']}-{$list['wage_max']}K</label>
                        </div>
                        <div class=\"hang2\">
                            <label class=\"experience nowrap\">{$list['experience']}</label>
                            <span class=\"major nowrap\">{$list['education']}</span>
                            <span class=\"xingzhi\">{$list['number_range']}人/{$list['work_nature']}</span>
                        </div>
                        <div class=\"hang3\">
                            <p>工作经验：<label class=\"job_jingyan\">{$list['experience']}</label></p>
                            <p class=\"job_point\">工作地点：<label class=\"job_didian\">{$list['city']} {$list['city_area']}</label></p>
                        </div>
                        <div class=\"xian1\"></div>
                        <div class=\"companylogo\">
                            <div class=\"logo\">
                                <img src=\"{$list['headimgurl']}\">
                            </div>
                            <div class=\"companyname\">
                                <p class=\"name\">{$list['companyname']}</p>
                                <p class=\"shuxin\">{$list['updatetime']}</p>
                            </div>
                        </div>
                    </div>
                    <div class=\"review_statas\" data-id='{$list[uid]}'>
                        <div class=\"{$toudijianli}\" data-id='{$list[id]}'>{$list['post_status']}</div>
                    </div>
                </div>";
        }
        call_back(1,$html);
    }
}


elseif ($op=="show_map"){
    $retoate_x = $_GPC['retoate_x'];
    $retoate_y = $_GPC['retoate_y'];
    include wl_template("common/map");exit();
}

if(empty($_SESSION['mid'])){
    header("location:".app_url("member/index/index"));
}






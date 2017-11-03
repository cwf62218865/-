<?php
defined('IN_IA') or exit('Access Denied');
wl_load()->model('verify');

/*************************************主要公用页面渲染**************************************/
//首页
if($op=="index"){
    $company  = m("member")->company_list();

    $jobs = m("jobs")->getall_jobs_page($data,9);
    $jobs = $jobs['more'];
    //var_dump($company);exit();
//    $company = pdo_fetchall("select * from ".tablename(WL."company")." order by id desc");
    include wl_template("member/index");exit();
}
//登录
elseif ($op=="login"){
    unset($_SESSION['uid']);
    unset($_SESSION['utype']);
    include_once( WL_CORE.'/common/libweibo-master/config.php' );
    include_once( WL_CORE.'/common/libweibo-master/saetv2.ex.class.php');

    $o = new SaeTOAuthV2( WB_AKEY , WB_SKEY );
    $code_url = $o->getAuthorizeURL( WB_CALLBACK_URL );
    include wl_template("member/login");exit();
}
//注册
elseif ($op=="register"){
    include wl_template("member/reg_screen");exit();
}

//职位详情页
elseif($op=="jobs_detail"){

    if($_GPC['jobs_id']){
        $jobs = pdo_fetch("select * from ".tablename(WL."jobs")." where id=".$_GPC['jobs_id']);
        $company = pdo_fetch("select * from ".tablename(WL."company_profile")." where uid=".$jobs['uid']);
        $jobs_apply = pdo_fetch("select id from ".tablename(WL."jobs_apply")." where jobs_id=".$_GPC['jobs_id']." and puid=".$_SESSION['uid']);
        $report = pdo_fetch("select id from ".tablename(WL."report")." where jobs_id=".$_GPC['jobs_id']." and report_uid=".$_SESSION['uid']);
        $last_login_time = m("member")->last_login($jobs['uid']);

        $similar_jobs = pdo_fetchall("select * from ".tablename(WL."jobs")." where jobs_name like '%".$jobs['jobs_name']."%' and id<>".$jobs['id']);
//        echo date()-date("Y-m-d",$last_login_time['last_login_time']);exit();
//        echo "select id from ".tablename(WL."jobs_apply")." where jobs_id=".$_GPC['jobs_id']." and puid=".$_SESSION['uid'];exit();
//        var_dump($jobs_apply);exit();
        include wl_template("member/jobs_detail");exit();
    }

}

//职位搜索
elseif ($op=="search_jobs"){
    if($_GET['jobs_name']){
        $data['data']['job_name'] = $_GET['jobs_name'];
        $jobs_count = pdo_fetchcolumn("select count(*) from ".tablename(WL."jobs")." where open=1 and display=1 and jobs_name like '%".$_GET['jobs_name']."%'");
    }
    $jobs = m("jobs")->getall_jobs_page($data);
    $jobs_count = $jobs['count'];
    $jobs = $jobs['more'];
    include wl_template("member/search_jobs");exit();
}

//公司详情页
elseif($op=="company_detail"){

    if($_GPC['company_id']) {
        $company = pdo_fetch("select * from ".tablename(WL."company_profile")." where uid=".$_GPC['company_id']);
        $jobs = pdo_fetchall("select * from ".tablename(WL."jobs")." where open=1 and uid=".$company['uid']);
        $jobs_num = pdo_fetchcolumn("select COUNT(*) from ".tablename(WL."jobs")." where uid=".$company['uid']);
        $last_login_time = m("member")->last_login($company['uid']);
        include wl_template("member/company_pages");
        exit();
    }
}


//忘记密码by手机
elseif ($op=="forget_password"){

    include wl_template("member/forget_password");
}

//忘记密码by邮箱
elseif ($op=="forget_password_email"){

    include wl_template("member/forget_password_email");
}
//忘记密码by邮箱
elseif ($op=="forget_password_permit"){

    include wl_template("member/forget_password_permit");
}
//设置密码
elseif ($op=="set_password"){
    $identity = explode("*",$_GPC['identity']);
    $id = encrypt($identity[0], 'D');
    $time = $identity[1];
    $member = pdo_fetch("select id from ".tablename(WL."members")." where id=".$id." and last_login_time=".$time);
    if($member){
        $_SESSION['uid'] = $member['id'];
    }
    if($_SESSION['uid']){
        include wl_template("member/set_password");exit();
    }else{
        include wl_template("member/login");exit();
    }
}

//绑定账号
elseif ($op=="bind_account"){
    include wl_template("member/create_bind_account");exit();
}

//手机上传头像界面
elseif ($op=="mobile_upload"){
    if($_GPC['kind']=="resume"){
        $kind = "简历头像上传";
        $url = app_url("member/index/save_members_temp",array('kind'=>'headimgurl'));
    }elseif($_GPC['kind']=="id1"){
        $kind = "法人身份证(正面)";
        $url = app_url("member/index/save_members_temp",array('kind'=>'idcard1'));
    }elseif($_GPC['kind']=="id2"){
        $kind = "法人身份证(反面)";
        $url = app_url("member/index/save_members_temp",array('kind'=>'idcard2'));
    }elseif($_GPC['kind']=="license"){
        $kind = "营业执照上传";
        $url = app_url("member/index/save_members_temp",array('kind'=>'license'));
    }elseif($_GPC['kind']=="person_works"){
        $kind = "个人作品上传";
        $url = app_url("member/index/save_members_temp",array('kind'=>'person_works'));
    }elseif($_GPC['kind']=="honor"){
        $kind = "荣誉证书上传";
        $url = app_url("member/index/save_members_temp",array('kind'=>'honor'));
    }elseif($_GPC['kind']=="company_logo"){
        $kind = "公司logo上传";
        $url = app_url("member/index/save_members_temp",array('kind'=>'company_logo'));
    }elseif($_GPC['kind']=="atlas"){
        $kind = "公司图集上传";
        $url = app_url("member/index/save_members_temp",array('kind'=>'atlas'));
    }
    include wl_template("member/mobileupload1");exit();

}


//账户设置
elseif ($op=="usersetting"){
    if($_SESSION['uid']){
        $member = pdo_fetch("select * from ".tablename(WL."members")." where id=".$_SESSION['uid']);
        $resume = m("resume")->get_resume($_SESSION['uid']);
        include wl_template("member/usersetting");exit();
    }

}
//网站地图
elseif ($op=="navigation"){
    include wl_template("member/navigation");exit();
}

//关于我们
elseif ($op=="aboutus"){
    $nav = $_GPC['nav'];

    include wl_template("member/aboutus");exit();
}

//验证码
elseif ($op=="captcha"){
    header("Content-type:image/png");
    m("imageCaptcha")->set_show_mode();
    $code = m("imageCaptcha")->createImage();
    $_SESSION['imageCaptcha_content'] = strtolower($code);
    exit();
}


/************************************公用页面主要请求接口***************************************/
//切换城市
elseif ($op=="switch_city"){
    $city = check_pasre($_POST['city'],"参数错误");
    $city = str_replace("[","",$city);
    $city = str_replace("]","",$city);
    $_SESSION['city'] = $city;
    call_back(1,"ok");
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
            pdo_update(WL."members",array('last_login_time'=>time()),array('id'=>$member['id']));
            call_back(1,$url);
        }else{
            call_back(2,"请输入正确的密码");
        }
    }
}

//忘记密码手机验证
elseif ($op=="pwd_bytel"){
    if(check_phone($_GPC['tel'],2)){
        $phone =$_GPC['tel'];
        $member = pdo_fetch("select * from ".tablename(WL.'members')." where mobile=".$phone);
        if($member){
            if($_POST['yanzheng']==$_SESSION['phone_code']){
                $_SESSION['uid'] = $member['id'];
                call_back(1,app_url("member/index/set_password"));
            }else{
                call_back(2,"验证码不正确");
            }
        }else{
            call_back(2,"该手机号未注册");
        }
    }

}

//邮箱找回密码
elseif ($op=="pwd_byemail"){
    $mobile = check_pasre($_POST['tel'],"请输入手机号");
    $email = check_pasre($_POST['yanzheng'],"请输入邮箱");

    $member = pdo_fetch("select id,last_login_time from ".tablename(WL."members")." where mobile='".$mobile."' and email='".$email."'");
    if($member){
        $identity = encrypt($member['id'], 'E');
        $body = "
    点击以下链接找回密码:<br/>
    <a href='".app_url('member/index/set_password',array('identity'=>$identity.'*'.$member['last_login_time']))."'>".app_url('member/index/set_password',array('identity'=>$identity.'*'.$member['last_login_time']))."</a>
    ";
        ihttp_email($email, "应届僧密码找回", $body);
        call_back(1,"已向您的邮箱发送了找回密码");
    }else{
        call_back(2,"此手机号与邮箱暂未注册");
    }
}

//执照找回密码
elseif ($op=="pwd_bypermit"){
    $data['companyname'] = check_pasre($_POST['companyname'],"请输入公司名称");
    $data['license'] = check_pasre($_POST['license'],"请上传营业执照");
    $data['idcard1'] = check_pasre($_POST['idcard1'],"请上传法人身份证(正面)");
    $data['idcard2'] = check_pasre($_POST['idcard2'],"请上传法人身份证(反面)");
    $data['send_mobile'] = check_pasre($_POST['company_tel'],"请输入接受密码的手机号");
    $data['addtime'] = time();
    $r = insert_table($data,WL."permit_validate");
    if($r){
        call_back(1,"提交成功");
    }else{
        call_back(2,"异常错误");
    }
}
//执照找回之图片上传
elseif ($op=="permit_validate"){
    $img = upload_img($_FILES);
    call_back(1,$img);
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
//发送验证码
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
elseif($op=="normal_send_code"){
    send_codes($_POST['mobie']);exit();
}
//更改手机号码
elseif ($op=="change_mobile"){
    if(check_phone($_GPC['mobie'])){
        $phone =$_GPC['mobie'];
        $member = pdo_fetch("select * from ".tablename(WL.'members')." where mobile=".$phone);
        if($member){
            call_back(2,"该手机号已被注册");

        }else{
            if($_POST['yanzheng']==$_SESSION['phone_code']){
                pdo_update(WL."members",array('mobile'=>$phone),array('id'=>$_SESSION['uid']));
                call_back(1,"修改成功");
            }else{
                call_back(2,"验证码不正确");
            }
        }
    }
}
//切换简历显示状态
elseif ($op=="resume_display_status"){
    $kind = check_pasre($_POST['kind'],"参数错误");
    if($kind==2){$kind=1;}
    $data['display'] = $kind;
    $data['updatetime'] = time();
    if($kind<>3){
        $data['blacklist'] = "";
    }
    $r = pdo_update(WL."resume",$data,array('uid'=>$_SESSION['uid']));
    call_back(1,"ok");
}
//黑名单
elseif ($op=="blacklist"){
    $blacklist = check_pasre($_POST['name'],"请输入你想屏蔽的公式名称");
    $r = pdo_update(WL."resume",array('blacklist'=>$blacklist,'updatetime'=>time()),array('uid'=>$_SESSION['uid']));
    if($r){
        call_back(1,"ok");
    }else{
        call_back(1,"no");
    }
}
//修改密码
elseif ($op=="modify_pwd"){
    $member = pdo_fetch("select * from ".tablename(WL."members")." where id=".$_SESSION['uid']);
    $password = pwd_hash($_GPC['psw'],$member['salt']);
    if($password==$member['password']){
        if($_GPC['newpsw']==$_GPC['newpswch']){
            $password =  pwd_hash($_GPC['newpsw'],$member['salt']);
            $r = pdo_update(WL."members",array('password'=>$password),array('id'=>$_SESSION['uid']));
            call_back(1,"修改成功");
        }else{
            call_back(2,"2次输入密码不一致");
        }
    }else{
        call_back(2,"原密码不正确");
    }
}
//保存留言板
elseif ($op=="save_books"){

    if($_POST){
        if($_POST['lycheckma']==$_SESSION['imageCaptcha_content']){
            $data['fullname'] = check_pasre($_POST['lyname'],"请输入姓名");
            $data['mobile'] = check_pasre($_POST['lymobile'],"请输入手机号码");
            $data['email'] = check_pasre($_POST['lyemail'],"请输入邮箱");
            $data['content'] = check_pasre($_POST['lymsg'],"请输入反馈内容");
            $data['atlas'] = $_POST['atlas'];
            $data['addtime'] = time();
            $books = pdo_fetch("select id from ".tablename(WL."books")." where mobile=".$data['mobile']);
            if($books){
                call_back(2,"您已留言，请不要重复提交");
            }
            $r = insert_table($data,WL."books");
            if($r){
                call_back(1,"留言成功");
            }else{
                call_back(2,"异常错误");
            }
        }else{
            call_back(2,"验证码不正确");
        }
    }
    exit();
}

elseif ($op=="img_upload"){
    $data = upload_img($_FILES);
    call_back(1,$data);
}
//ajax请求上传结果,刷新页面
elseif ($op=="upload_refresh"){
    $uid = $_SESSION['uid'];
    $temp = pdo_fetch("select * from ".tablename(WL.'members_temp')." where uid=".$uid);

    if($temp['license'] || $temp['idcard1'] || $temp['idcard2']){
        if($temp['license']){
            $data['license'] = $temp['license'];
        }
        if($temp['idcard1']) {
            $data['idcard1'] = $temp['idcard1'];
        }
        if($temp['idcard2']) {
            $data['idcard2'] = $temp['idcard2'];
        }
        $company_profile = pdo_fetch("select * from ".tablename(WL.'company_profile')." where uid=".$uid);
        if($company_profile){
            $r = pdo_update(WL."company_profile",$data,array('uid'=>$uid));
        }else{
            $data['uid'] = $uid;
            $data['createtime']=time();
            $r = pdo_insert(WL."company_profile",$data);
        }
    }

    if($temp['headimgurl']){

        $data['headimgurl'] = $temp['headimgurl'];
        $resume = pdo_fetch("select * from ".tablename(WL.'resume')." where uid=".$uid);
        if($resume){

            $data['updatetime'] = time();
            $r = pdo_update(WL."resume",$data,array('uid'=>$uid));
        }else{
            $data['uid'] = $uid;
            $data['addtime']=time();
            $r = pdo_insert(WL."resume",$data);
        }
    }

    if($temp['person_works']){
        $data['person_works'] = $temp['person_works'];
        $data['updatetime'] = time();
        $r = pdo_update(WL."resume",$data,array('uid'=>$uid));
    }

    if($r){
        pdo_delete(WL."members_temp",array("uid"=>$uid));
        call_back(1,"上传成功");
    }
    exit();
}

//简历头像上传保存
elseif ($op=="save_members_temp"){

    if($_GPC['identity']){
        $id = encrypt($_GPC['identity'], 'D');
        $member = m("member")->get_member($id);
        if($member){
            $file = upload_img($_FILES);
            $data['uid'] = $id;
            $kind = $_GPC['kind'];
            $data[$kind] = $file;
            $temp = pdo_fetch("select id from ".tablename(WL."members_temp")." where uid=".$id);
            if(empty($temp)){
                $r = pdo_insert(WL."members_temp",$data);
                $temp = pdo_fetch("select id from ".tablename(WL."members_temp")." where uid=".$id);
            }
            if($r){
                call_back(1,$temp[$kind]);
            }else{
                call_back(2,"no file upload");
            }

        }
    }else{
        call_back(2,"身份验证失败");
    }
}


//职位搜索ajax
elseif ($op=="search_jobs_ajax"){
//    var_dump($_POST);exit();
    if ($_POST['data']){
        $page = $_POST['data']['page'];
        $jobs = m("jobs")->getall_jobs_page($_POST);
        $html = "";
        foreach ($jobs['more'] as $key=>$list){
            if($list['post_status']=="已投递"){
                $toudijianli = "toudijianli1";
            }else{
                $toudijianli = "toudijianli";
            }
            $list['updatetime'] = date("Y-m-d",$list['updatetime']);
            if($list['wage_min']>0 && $list['wage_max']>0){
                $list['salary'] = $list['wage_min']."-".$list['wage_max']."k";
            }else{
                $list['salary'] = "面议";
            }
            $html .=
                "<div class=\"list_item\">
                    <div class=\"item_con\">
                        <div class=\"hang1\">
                            <a class=\"jobname nowrap\" href='".app_url('member/index/jobs_detail',array('jobs_id'=>$list['id']))."'>{$list['jobs_name']}</a>
                            <label class=\"salary\">{$list['salary']}</label>
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
        call_back(1,$html,$jobs['count']);
    }
}


elseif ($op=="show_map"){
    $retoate_x = $_GPC['retoate_x'];
    $retoate_y = $_GPC['retoate_y'];
    include wl_template("common/map");exit();
}

//举报职位
elseif ($op=="tip_off"){

    $wheresql = "";
    if($_POST['data']['jobs_id']){
        $data['jobs_id'] = check_pasre($_POST['data']['jobs_id'],"参数错误");
        $wheresql = " jobs_id=".$data['jobs_id'];
    }
    if($_POST['data']['company_uid']){
        $data['company_uid'] = check_pasre($_POST['data']['company_uid'],"参数错误");
        $wheresql = " company_uid=".$data['company_uid'];
    }
    $data['hide'] = $_POST['data']['niming'];
    $data['company_scale'] = check_pasre($_POST['data']['company_scale'],"请填写举报原因");
    $data['report_content'] = check_pasre($_POST['data']['report_content'],"请填写详细描述");
    $report = pdo_fetch("select id from ".tablename(WL."report")." where ".$wheresql." and report_uid=".$_SESSION['uid']);
    if(empty($_SESSION['uid'])){
        call_back(2,"登录之后，才可以举报职位");
    }
    if (empty($report)){
        $data['addtime'] = time();
        $data['report_uid'] = $_SESSION['uid'];
        $r = insert_table($data,WL."report");
        if($r){
            call_back(1,"举报成功");
        }else{
            call_back(2,"举报失败");
        }
    }else{
        call_back(2,"您已经举报过该职位");
    }

}


/*********手机端处理接口***********/
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


/**********未知接口*******/
elseif ($op=="resume_worksupload"){
    $kind = "个人作品上传";
    include wl_template("resume/mobileupload1");exit();
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
}


if(empty($_SESSION['mid'])){
    header("location:".app_url("member/index/index"));
}






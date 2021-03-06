<?php
defined('IN_IA') or exit('Access Denied');
wl_load()->model('verify');

/*************************************主要公用页面渲染**************************************/
//首页
if($op=="index"){
    $company  = m("member")->company_list();

    $data['data']['job_order']="最新";
    $jobs = m("jobs")->getall_jobs_page($data,9);
    $jobs = $jobs['more'];

    $data['data']['job_order']="热门";
    $collect_jobs = m("jobs")->getall_jobs_page($data,9);
    $collect_jobs = $collect_jobs['more'];
    $news = pdo_fetchall("select * from ".tablename("article_news")." order by createtime desc limit 0,3");
    foreach ($news as $list){
        $list['content'] = strip_tags($list['content']);
        if(mb_strlen($list['content'])>33){
            $list['content'] = strip_tags(str_replace(" ","",mb_substr($list['content'],0,33,"UTF8")."..."));
        }
        $arr_news[] = $list;
    }

    $star_company =m("company")->star_company_list();
    $hot_words = pdo_fetchall("select word from ".tablename(WL."hotword")." order by hot desc limit 0,7");
    $hot_words_xia = pdo_fetchall("select word from ".tablename(WL."hotword")." order by hot desc limit 0,4");
//    var_dump($collect_jobs);exit();
    include wl_template("member/index");exit();
}
//退出登录
elseif ($op=="login_out"){
    unset($_SESSION['uid']);
    unset($_SESSION['utype']);
    header("location:".app_url('member/index/login'));
}

//修改密码后退出登录
elseif($op=="pwd_login_out"){
    unset($_SESSION['uid']);
    unset($_SESSION['utype']);
    header("location:".app_url('member/index/login'));exit();
}
//登录
elseif ($op=="login"){
    $weinxin_uri = urldecode("http://www.yingjieseng.com/app/index.php?c=site&a=entry&m=recruit&do=member&ac=index&op=weixin_callback");
    $back_top = 1;
   if($_SESSION['utype']==1){
       header("location:".app_url('person/index/send_resume'));
   }elseif ($_SESSION['utype']==2){
       header("location:".app_url('company/resume/received_resume'));
   }
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
        $jobs = m("jobs")->get_jobs($_GPC['jobs_id']);
        $company = m("company")->get_profile($jobs['uid']);


        $jobs_count = pdo_fetchcolumn("select count(*) from ".tablename(WL."jobs")." where open=1 and uid=".$jobs['uid']);
        $comment_count = pdo_fetchcolumn("select count(*) from ".tablename(WL."comment")." where uid=".$jobs['uid']);
        $current_comment_count = pdo_fetchcolumn("select count(*) from ".tablename(WL."comment")." where jobs_id=".$_GPC['jobs_id']." and uid=".$jobs['uid']);

//        $jobs_apply = pdo_fetch("select id from ".tablename(WL."jobs_apply")." where jobs_id=".$_GPC['jobs_id']." and puid=".$_SESSION['uid']);
        $jobs_apply = m("jobs")->judge_jobs_apply_status($_GPC['jobs_id'],$_SESSION['uid']);
        $report = pdo_fetch("select id from ".tablename(WL."report")." where jobs_id=".$_GPC['jobs_id']." and report_uid=".$_SESSION['uid']);
        $last_login_time = m("member")->last_login($jobs['uid']);
        $collect_status = pdo_fetch("select id from ".tablename(WL."collect_jobs")." where uid=".$_SESSION['uid']." and jobs_id=".$_GPC['jobs_id']);
        $similar_jobs = pdo_fetchall("select j.*,c.headimgurl from ".tablename(WL."jobs")." j,".tablename(WL."company_profile")." c where c.uid=j.uid and j.jobs_name like '%".$jobs['jobs_name']."%' and j.id<>".$jobs['id']." limit 0,2");
        $data['jobs_id'] = $_GPC['jobs_id'];
        $comment_jobs = m("jobs")->comment_apply($data);
        $comment_jobs = $comment_jobs['more'];
        $data['data']['guess'] = 1;
        $guess_jobs = m("jobs")->getall_jobs_page($data,4);
        $guess_jobs = $guess_jobs['more'];
        if($jobs['open']){
            include wl_template("member/jobs_detail");exit();
        }else{
            include wl_template("member/stop_recruitjob");exit();
        }
    }

}

//职位搜索
elseif ($op=="search_jobs"){
    if($_GET['jobs_name']){
        $data['data']['job_name'] = $_GET['jobs_name'];
        $jobs_count = pdo_fetchcolumn("select count(*) from ".tablename(WL."jobs")." where open=1 and display=1 and jobs_name like '%".$_GET['jobs_name']."%'");
    }
    $jobs = m("jobs")->getall_jobs_page($data);
    $data['data']['guess'] = 1;
    $guess_jobs = m("jobs")->getall_jobs_page($data,2);
    $guess_jobs = $guess_jobs['more'];
    $jobs_count = $jobs['count'];
    $jobs = $jobs['more'];
    $hot_words = pdo_fetchall("select word from ".tablename(WL."hotword")." order by hot desc limit 0,7");

    include wl_template("member/search_jobs");exit();
}
elseif ($op=="super_company"){
    $company = pdo_fetch("select * from ".tablename(WL."star_hr")." where id=".$_GPC['id']);
    $jobs = pdo_fetchall("select * from ".tablename(WL."star_jobs")." where uid=".$_GPC['id']);
    $star_career = pdo_fetchall("select * from ".tablename(WL."star_career")." where uid=".$_GPC['id']);
    $resume = m("resume")->get_resume($_SESSION['uid']);
    if(trim($resume['work_experience'])){
        $work_experience = unserialize($resume['work_experience']);
    }else{
        $work_experience = array();
    }
    $guess_jobs = m("jobs")->getall_jobs_page($data,4);

    include wl_template("company/super_company");exit();

}
elseif ($op=="super_company_list"){

    if(GetIpLookup($_SERVER['REMOTE_ADDR'])){
        $ip_city = GetIpLookup($_SERVER['REMOTE_ADDR']);
        $ip_city = $ip_city['province'];
    }else{
        $ip_city = "重庆";
    }
    $star_companys =m("company")->star_company_list(0,6);
    $star_company = "";
    foreach ($star_companys as $list){
        $list['star_jobs'] = pdo_fetchall("select id,jobs_name from ".tablename(WL."star_jobs")." where uid=".$list['id']." limit 0,4");
        $star_company[] = $list;
    }
    include wl_template("company/super_company_list");exit();
}
//公司详情页
elseif($op=="company_detail"){

    if($_GPC['company_id']) {
        $company = pdo_fetch("select * from ".tablename(WL."company_profile")." where uid=".$_GPC['company_id']);
//        $jobs = pdo_fetchall("select * from ".tablename(WL."jobs")." where open=1 and uid=".$company['uid']);
        $data['data']['uid'] = $_GPC['company_id'];
        $jobs = m("jobs")->getall_jobs_page($data);
        $jobs = $jobs['more'];
        $jobs_num = pdo_fetchcolumn("select COUNT(*) from ".tablename(WL."jobs")." where open=1 and display=1 and uid=".$company['uid']);
        $last_login_time = m("member")->last_login($company['uid']);
        $data['company_uid'] = $_GPC['company_id'];
        $comment_jobs = m("jobs")->comment_apply($data);
        $comment_jobs = $comment_jobs['more'];
        $company_count = m("jobs")->comment_count($_GPC['company_id']);

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
    if($_GPC['kind']=="headimgurl"){
        $kind = "简历头像上传";
        $url = app_url("member/index/save_members_temp",array('kinds'=>'headimgurl'));
    }elseif($_GPC['kind']=="idcard1"){
        $kind = "法人身份证(正面)";
        $url = app_url("member/index/save_members_temp",array('kinds'=>'idcard1'));
    }elseif($_GPC['kind']=="idcard2"){
        $kind = "法人身份证(反面)";
        $url = app_url("member/index/save_members_temp",array('kinds'=>'idcard2'));
    }elseif($_GPC['kind']=="license"){
        $kind = "营业执照上传";
        $url = app_url("member/index/save_members_temp",array('kinds'=>'license'));
    }elseif($_GPC['kind']=="resume_works"){
        $kind = "个人作品上传";
        $url = app_url("member/index/save_members_temp",array('kinds'=>'person_works'));
    }elseif($_GPC['kind']=="honor"){
        $kind = "荣誉证书上传";
        $url = app_url("member/index/save_members_temp",array('kinds'=>'honor'));
    }elseif($_GPC['kind']=="company_logo"){
        $kind = "公司logo上传";
        $url = app_url("member/index/save_members_temp",array('kinds'=>'company_logo'));
    }elseif($_GPC['kind']=="atlas"){
        $kind = "公司图集上传";
        $url = app_url("member/index/save_members_temp",array('kinds'=>'atlas'));
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

elseif ($op=="news"){
    $news = m("member")->news_list();
     $hot_news = pdo_fetchall("select * from ".tablename("article_news")." where thumb<>'' order by click desc limit 0,5");
    $news_count = pdo_fetchcolumn("select count(*) from ".tablename("article_news"));
    $news_count1 = pdo_fetchcolumn("select count(*) from ".tablename("article_news")." where kind=2");
    $news_count2 = pdo_fetchcolumn("select count(*) from ".tablename("article_news")." where kind=3");
    $news_count3 = pdo_fetchcolumn("select count(*) from ".tablename("article_news")." where kind=4");

    include wl_template("member/news");exit();
}

elseif ($op=="news_detail"){
    if($_GPC['id']){
        if($_SESSION['utype']==1){
            $resume = pdo_fetch("select fullname,headimgurl from ".tablename(WL."resume")." where uid=".$_SESSION['uid']);
            $user['fullname'] = $resume['fullname'];
        }elseif ($_SESSION['utype']==2){
            $company = pdo_fetch("select companyname,headimgurl from ".tablename(WL."company_profile")." where uid=".$_SESSION['uid']);
            $user['fullname'] = $company['companyname'];
        }

        $news = pdo_fetch("select * from ".tablename("article_news")." where id=".$_GPC['id']);
        $comment_count = pdo_fetchcolumn("select count(*) from ".tablename(WL."evaluate")." where news_id=".$_GPC['id']);
        $comment = m('member')->get_news_comment($_GPC['id']);
        $news['click'] +=1;
        pdo_update("article_news",array('click'=>$news['click']),array('id'=>$_GPC['id']));

        $hot_news = pdo_fetchall("select * from ".tablename("article_news")." where thumb<>'' order by click desc limit 0,5");
        $related_news = pdo_fetchall("select id,title from ".tablename("article_news")." order by rand() limit 0,3");
        include wl_template("member/news_detail");exit();
    }else{
        die();
    }

}

//验证码
elseif ($op=="captcha"){

    header("Content-type:image/png");
    m("imagecaptcha")->set_show_mode();
    $code = m("imagecaptcha")->createImage();
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
//文章主评论
elseif ($op=="comment_news"){

    if(empty($_SESSION['uid'])){
        call_back(3,"未登录");
    }else{
       $data['news_id'] = check_pasre($_POST['id'],"参数错误");
       $data['content'] = check_pasre($_POST['pj_content'],"请输入评论内容");
       $data['uid'] = $_SESSION['uid'];
       $data['utype'] = $_SESSION['utype'];
       $data['addtime'] = time();
       $data['comment_id'] = intval($_POST['comment_id']);
       $r = pdo_insert(WL."evaluate",$data);
       if($r){
           call_back(1,"添加成功");
       }else{
           call_back(2,"添加失败");
       }
    }
}

elseif ($op=="comment_page_ajax"){
    $page = intval($_POST['page']);
    $id = check_pasre($_POST['news_id'],"参数错误");
    $comment = m("member")->get_news_comment($id,$page);
    $html = "";
    foreach ($comment as $list){
        if($list['comment_id']){
            $html .="<div class=\"pinglun_item\">
                <div class=\"touxiang_pl\">
                    <img src=\"{$list['headimgurl']}\" style=\"width: 100%;\">
                </div>
                <div class=\"pl_user\">{$list['fullname']}</div>
                <div class=\"pinglunneir\"><label class=\"beizhu_pl\">[回复{$list['pl_user']}]</label>{$list['content']}</div>
                <div class=\"pl_status\">
                    <label class=\"time_pl\">"._format_date($list['addtime'])."</label>
                </div>
            </div>";
        }else{
            $html .="<div class=\"pinglun_item\" data-id=\"{$list['id']}\">
                <div class=\"touxiang_pl\">
                    <img src=\"{$list['headimgurl']}\" style=\"width: 100%;\">
                </div>
                <div class=\"pl_user\">{$list['fullname']}</div>
                <div class=\"pinglunneir\">{$list['content']}
                </div>
                <div class=\"pl_status\">
                    <label class=\"time_pl\">"._format_date($list['addtime'])."</label>
                    <label class=\"ico_plbtn\">
                        <span class=\"jubao_btn\">举报</span>
                        <span class=\"huifu_btn\">回复</span>
                    </label>
                </div>
            </div>";
        }
    }
   call_back(1,$html);
}

//新闻分页
elseif ($op=="news_page_ajax"){

    $page = intval($_POST['data']['page']);
    if($page){
        $page = $page-1;
    }

    $kind = "";
    if($_POST['kind']=="招聘新闻"){
        $kind = 4;
    }elseif ($_POST['kind']=="活动报道"){
        $kind = 3;
    }elseif ($_POST['kind']=="网站公告"){
        $kind = 2;
    }elseif ($_POST['kind']=="系统推荐"){
        $kind = 1;
    }
    $news = m("member")->news_list($page,$kind);
    $news_count = pdo_fetchcolumn("select count(*) from ".tablename("article_news"));
    $html = "";
    foreach ($news as $list){
        if($list['thumb']){
            $thumb = "<img src=\"/attachment/{$list['thumb']}\" />";
        }else{
            $thumb = "<img src='".WL_URL_ARES."/images/nopic-107.png' height='160' />";
        }
        $html .=" <div class=\"news_list_box\">
                    <a href=\"#\" class=\"news_list_box_img\">
                    {$thumb}
                    </a>
                    <div class=\"news_list_box_content\">
                        <a href=\"#\" class=\"news_list_box_title\">{$list['title']}</a>
                        <span class=\"news_list_box_msg\">".strip_tags($list['title'])."</span>
                        <div class=\"news_list_box_bottom_msg\">
                            <span class=\"news_list_box_classify \">
                                <svg class=\"icon\">
                                    <use xlink:href=\"#icon-shuqian1\"></use>
                                </svg>
                                <span class=\"classifys\">招聘新闻</span>
                            </span>
                            <div class=\"right_classify\">
                                <span class=\"news_list_box_classify\">
                                    <svg class=\"icon\">
                                        <use xlink:href=\"#icon-liulanliang\"></use>
                                    </svg>
                                    {$list['click']}
                                </span>
                                <span class=\"news_list_box_classify\" style=\"margin-left: 25px\">
                                   ".date('Y-m-d',$list['createtime'])."
                                </span>
                            </div>
                        </div>
                    </div>
                </div>";
    }

    call_back(1,$html,$news_count);
}

//登录处理
elseif ($op=="login_deal"){
    $data['baidu_openid'] = $_GPC['baidu_openid'];
    $data['qq_openid'] = $_GPC['qq_openid'];
    $data['weixin_openid'] = $_GPC['weixin_openid'];
    $data['weibo_openid'] = $_GPC['weibo_openid'];
    $username = check_pasre($_GPC['user_name'],"请输入您的手机号/用户名");
    $member = member_exists($username);

    if($member){
        $password = pwd_hash($_GPC['password'],$member['salt']);
        if($password==$member['password']){
            if($data['baidu_openid'] || $data['qq_openid'] || $data['weixin_openid'] || $data['weibo_openid']){
                if($member['utype']!=1){
                    call_back(2,"暂不支持企业用户绑定");
                }
                $r = pdo_update(WL."members",$data,array('id'=>$member['id']));
                if($r){
                    call_back(1,"绑定成功");
                }else{
                    call_back(2,"绑定失败");
                }
            }
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
                }elseif (!$resume['birthday']){
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
    }else{
     call_back(4,"用户名不存在");
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
            call_back(4,"该手机号未注册");
        }
    }

}

//邮箱找回密码
elseif ($op=="pwd_byemail"){
    $mobile = check_pasre($_POST['tel'],"请输入手机号");
    $email = check_pasre($_POST['yanzheng'],"请输入邮箱");
    $members = pdo_fetch("select id,last_login_time from ".tablename(WL."members")." where mobile='".$mobile."'");
    if(empty($members)){
        call_back(4,"该手机号未注册");
    }
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
            send_codes($phone);
        }

    }
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
elseif ($op=="headimgurl_upload"){
    $data = base64_upload($_POST['file']);
    call_back(1,$data);
}
//ajax请求上传结果,刷新页面
elseif ($op=="upload_refresh"){
    $uid = $_SESSION['uid'];
    $temp = pdo_fetch("select * from ".tablename(WL.'members_temp')." where uid=".$uid);
    if($temp){
        pdo_delete(WL."members_temp",array("uid"=>$uid));
        if($temp['license'] || $temp['idcard1'] || $temp['idcard2']){
            if($temp['license']){
                $data['license'] = $temp['license'];
                call_back(1,$data['license'],1);
            }
            if($temp['idcard1']) {
                $data['idcard1'] = $temp['idcard1'];
                call_back(1,$data['idcard1'],2);
            }
            if($temp['idcard2']) {
                $data['idcard2'] = $temp['idcard2'];
                call_back(1,$data['idcard2'],3);
            }

        }


        if($temp['atlas']){
            $data['atlas'] = $temp['atlas'];
            call_back(1,$data['atlas']);
        }

        if($temp['headimgurl']){
            $data['headimgurl'] = file_transfer($temp['headimgurl']);
            $resume = pdo_fetch("select id from ".tablename(WL."resume")." where uid=".$_SESSION['uid']);
            if($resume){
                pdo_update(WL."resume",array('headimgurl'=>$data['headimgurl'],'updatetime'=>time()),array('uid'=>$_SESSION['uid']));
            }else {
                $data['addtime'] = time();
                $data['updatetime'] = time();
                $data['uid'] = $_SESSION['uid'];
               insert_table($data, WL . "resume");
            }
            call_back(1, $data['headimgurl'],1);
        }
        if($temp['person_works']){
            $data['person_works'] = $temp['person_works'];
            call_back(1,$data['person_works'],2);
        }

        if($temp['honor']){
            $data['honor'] = $temp['honor'];
            call_back(1,$data['honor'],3);
        }
    }
    call_back(2,"暂无数据");
    exit();
}

//简历头像上传保存
elseif ($op=="save_members_temp"){

    if($_GPC['identity']){
        $id = encrypt($_GPC['identity'], 'D');
        $member = m("member")->get_member($id);
        if($member){
//            $file = upload_img($_FILES);
            $file = base64_upload($_POST['file']);
            $data['uid'] = $id;
            $kind = $_GPC['kinds'];
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
            $list['updatetime'] = diff_timestamp($list['updatetime']);
            if($list['wage_min']>0 && $list['wage_max']>0){
                $list['salary'] = $list['wage_min']."-".$list['wage_max']."k";
            }else{
                $list['salary'] = "薪酬面议";
            }
            $html .=
                "<div class=\"list_item\">
                    <div class=\"item_con\">
                        <div class=\"hang1\">
                            <a class=\"jobname nowrap linkhover\" href='".app_url('member/index/jobs_detail',array('jobs_id'=>$list['id']))."'>{$list['jobs_name']}</a>
                            <label class=\"salary\">{$list['salary']}</label>
                        </div>
                        <div class=\"hang2\"  style=\"width: 100%;\">
                            <span class=\"major nowrap\"  style=\"width: 65px;\">{$list['education']}</span>
                            <lable class=\"experience nowrap\">{$list['number_range']}人</lable>
                            <span class=\"xingzhi\">{$list['work_nature']}</span>
                        </div>
                        <div class=\"hang3\">
                            <p>工作经验：<label class=\"job_jingyan\">{$list['experience']}</label></p>
                            <p class=\"job_point\">工作地点：<label class=\"job_didian\">{$list['city']} {$list['city_area']}</label></p>
                        </div>
                        <div class=\"xian1\"></div>
                        <div class=\"companylogo\">
                            <a class=\"logo\" href='".app_url('member/index/company_detail',array('company_id'=>$list['uid']))."'>
                                <img src=\"{$list['headimgurl']}\">
                            </a>
                            <div class=\"companyname\">
                                <a class=\"name\" href='".app_url('member/index/company_detail',array('company_id'=>$list['uid']))."'>{$list['companyname']}</a>
                                <p class=\"shuxin\">{$list['updatetime']}刷新</p>
                            </div>
                        </div>
                    </div>
                    <a class=\"review_statas\" data-id='{$list[uid]}' href='".app_url('member/index/jobs_detail',array('jobs_id'=>$list['id']))."'>
                        查看详情
                    </a>
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
//    var_dump($_POST);exit();
    $wheresql = "";
    if($_POST['data']['jobs_id']){
        $data['jobs_id'] = check_pasre($_POST['data']['jobs_id'],"参数错误");
        $wheresql = " jobs_id=".$data['jobs_id'];
    }
    if($_POST['data']['company_uid']){
        $data['company_uid'] = check_pasre($_POST['data']['company_uid'],"参数错误");
        $wheresql = " company_uid=".$data['company_uid'];
    }
    if($_POST['data']['comment_id']){
        $data['comment_id'] = check_pasre($_POST['data']['comment_id'],"参数错误");
        $wheresql = " comment_id=".$data['comment_id'];
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

//评论点赞
elseif ($op=="comment_zan"){
    if($_SESSION['uid']){
        $comment_id = check_pasre($_POST['comment_id'],"参数错误");
        $comment = pdo_fetch("select * from ".tablename(WL."comment")." where id=".$comment_id);
        $zan = array_filter(explode(",",$comment['zan']));
        if(in_array($_SESSION['uid'],$zan)){
            call_back(2,"已点赞");
        }else{
            array_push($zan,$_SESSION['uid']);
            $zan = implode(",",$zan);
            $zan_num = count($zan);
            pdo_update(WL."comment",array('zan'=>$zan,'zan_num'=>$zan_num),array('id'=>$comment_id));
            call_back(1,"点赞成功");
        }
    }else{
        call_back(3,"未登录");
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


//公司职位分页请求
elseif ($op=="company_detail_jobs_page"){

    $jobs = m("jobs")->getall_jobs_page($_POST);
    $str = "";
    foreach ($jobs['more'] as $list){
        if($list['wage_min']>0 && $list['wage_max']>0) {
            $salary = $list['wage_min']."-".$list['wage_max'] . "K";
        }else{
            $salary = "薪酬面议";

        }

        $str .="<a class=\"job_iteme\" href=".app_url('member/index/jobs_detail',array('jobs_id'=>$list['id']))." style=\"position: relative;\">
                    <p class=\"job_hang relative\">
                        <div class=\"job_names\" href=".app_url('member/index/jobs_detail',array('jobs_id'=>$list['id'])).">{$list['jobs_name']}</div>
                        <span class=\"refume_statas\">".date('Y-m-d',$list['updatetime'])."</span>
                    </p>
                    <p class=\"miaosu\">
                        <span class=\"salery_num\">
                            {$salary}
                        </span>
                        <span class=\"miaoshuitem\"><label class=\"itaemm\">{$list['education']}</label></span>
                        <span class=\"miaoshuitem\"><label class=\"itaemm\">{$list['experience']}</label></span>
                        <span class=\"miaoshuitem\"><label class=\"itaemm\">{$list['work_nature']}</label></span>
                        <span class=\"miaoshuitem\"><label class=\"itaemm\">招聘{$list['number_range']}人</label></span>
                        <span class=\"miaoshuitem\"><label class=\"itaemm\">{$list['city']}-{$list['city_area']}</label></span>
                    </p>
                </a>";

    }

    call_back(1,$str,$jobs['count']);
}

//职位评论分页请求
elseif ($op=="jobs_comment_page"){
    $comment_jobs = m("jobs")->comment_apply($_POST['data']);

    $str = "";
    foreach ($comment_jobs['more'] as $list){
        if($list['hr_reply']){
            $hr_reply = "<div class=\"evaluate_huifu\">
                                        <img class=\"hrtx touxiangpic\" src=\"{$list['logo']}\"/>
                                        <div class=\"hrhf\">
                                            <p class=\"hftitle\">HR回复：</p><span class=\"date_num\">".date("Y-m-d",$list['reply_time'])."</span>
                                            <p class=\"hfcon\">{$list['hr_reply']}</p>
                                        </div>
                                    </div>";
        }

        if($list['hide']){
            $hide = " <svg class=\"icon\">
                                        <use xlink:href=\"#icon-xuesheng\"></use>
                                    </svg>";
        }else{
            $hide =  "<img src=\"{$list['headimgurl']}\"  class=\"touxiangpic\">";
        }
        if($list['tag']){
            $list['tag'] = explode(",",$list['tag']);
            $tag = "";
            foreach ($list['tag'] as $li){
                $tag .="<span class=\"pingjiabq\">{$li}</span>";
            }
        }

        if(in_array($_SESSION['uid'],explode(",",$list['zan']))){
            $zan = "<div class=\"hangxq zan\" data-id=\"{$list['id']}\">";
        }else{
            $zan = "<div class=\"hangxq pre_zan\" data-id=\"{$list['id']}\">";
        }


        $str .="<div class=\"evaluate_item\">
                                <div class=\"left_tx\">
                                    {$hide}
                                    <div class=\"eva_name nowrap\">{$list['fullname']}</div>
                                </div>
                                <div class=\"xiangqing\">
                                    <div class=\"xqhang\">
                                        <span class=\"ico_titlesta\">总体评价:</span>
                                        <span class=\"stars_n\">
                                        {$list['count_score']}
                                        </span>
                                        <span class=\"point_num\">{$list['score']}分</span>
                                        <span class=\"jobname_eva\">面试职位：<a class=\"namedd\" href=\"#\">{$list['jobs_name']}</a></span>
                                        <span class=\"date_eva\">".date('Y-m-d',$list['createtime'])."</span>
                                    </div>
                                    <div class=\"xqhang\" style=\"margin-top: 18px;\">
                                        {$tag}
                                    </div>
                                    <div class=\"xqhang\" style=\"margin-top: 20px;\">
                                        [面试过程]<span class=\"evaluate_con\">
                                        {$list['content']}
                                    </span>
                                    </div>
                                            {$zan}
                                        <svg class=\"icon zan1\" aria-hidden=\"true\">
                                            <use  xlink:href=\"#icon-zan1\"></use>
                                        </svg>
                                        <span class=\"zannum\">".count(array_filter(explode(",",$list['zan'])))."</span>
                                        {$hr_reply}
                                    </div>
                                </div>
                            </div>";
    }
    call_back(1,$str,$comment_jobs['count']);
}

//职位详情页分页处理
elseif ($op=="jobs_datail_comment_page"){

    $comment_jobs = m("jobs")->comment_apply($_POST['data'],6);
    $str = "";
    foreach ($comment_jobs['more'] as $list){
        if($list['hide']){
            $hide = " <svg class=\"icon\">
                                    <use xlink:href=\"#icon-xuesheng\"></use>
                                </svg>";
        }else{
            $hide = " <img src=\"{$list['headimgurl']}\">";
        }

        if($list['tag']){
            $tag = "";
            foreach (explode(",",$list['tag']) as $li){
                $tag .= "<span class=\"welfare_label\">{$li}</span>";
            }
        }

        if(in_array($_SESSION['uid'],explode(",",$list['zan']))){
            $zan = "<span class=\"good color09c hover09c\" data-id=\"{$list['id']}\">";
        }else{
            $zan = "<span class=\"good colorbbb hover09c\" data-id=\"{$list['id']}\">";
        }
        $str .="<div class=\"jobs_detail\" style=\"margin-top: 36px\" >
                        <div class=\"interviewter_head\">
                            <div class=\"interviewter_header\">
                                {$hide}
                            </div>
                            <div class=\"hover09c\" style=\"margin-top: 12px\">{$list['fullname']}</div>
                        </div>

                        <div class=\"interviewter_content\">
                            <div class=\"comment_star\">
                                <span class=\"color999\">信息真实：</span>
                                {$list['information_star']}
                            </div>

                            <div class=\"comment_star\">
                                <span class=\"color999\">公司环境：</span>
                                {$list['environment_star']}
                            </div>

                            <div class=\"comment_star\">
                                <span class=\"color999\">面试官：</span>
                                {$list['interviewer_star']}
                            </div>


                            <div style=\"margin-top: 16px\">
                                {$tag}
                            </div>
                            <div class=\"color666\" style=\"margin-top: 20px;line-height: 24px\">
                                <span class=\"colorbbb\">[面试过程]</span>
                                {$list['content']}
                            </div>
                            <div style=\"margin-top: 16px\">
                                    {$zan}
                                    <svg class=\"icon\">
                                        <use xlink:href=\"#icon-zan1\"></use>
                                    </svg>
                                    （<span class=\"good_num\">".count(array_filter(explode(",",$list['zan'])))."</span>）
                                </span>
                            </div>
                        </div>
                    </div>";
    }
    call_back(1,$str,$comment_jobs['count']);
}
//下载文件
elseif ($op=="download"){

    downfile($_GPC['filename'].".pdf");echo "<script>history.go(-1);</script>";exit();
}
elseif ($op=="companys_slide"){
    $page = $_POST['page']?$_POST['page']:0;
    $company  = m("member")->company_list($page);
    $str = "";
    foreach ($company as $list){
        $str .="<div class=\"hot_companys\">
                    <a class=\"hot_companys_logo\" href=\"".app_url('member/index/company_detail',array('company_id'=>$list['uid']))."\">
                        <img src=\"{$list['headimgurl']}\">
                    </a>
                    <a class=\"nowrap hot_companys_name\" href=\"".app_url('member/index/company_detail',array('company_id'=>$list['uid']))."\">
                        {$list['companyname']}
                    </a>
                    <div class=\"nowrap hot_companys_introduce\">
                        {$list['introduce']}
                    </div>
                    <div class=\"nowrap hot_companys_introduce\">
                        {$list['industry']}/{$list['nature']}
                    </div>
                    <span class=\"hot_company_line\"></span>
                    <div class=\"hot_company_msg\">
                        <a  href=\"".app_url('member/index/company_detail',array('company_id'=>$list['uid']))."\">
                            {$list['comment_count']}
                            <div>面试评价</div>
                        </a>

                    </div>
                    <div class=\"hot_company_msg\">
                        <a  href=\"".app_url('member/index/company_detail',array('company_id'=>$list['uid']))."\">
                            {$list['jobs_count']}
                            <div>在招职位</div>
                        </a>

                    </div>
                    <div class=\"hot_company_msg\" style=\"font-size: 14px\">
                        <span>{$list['last_login']}</span>
                        <div>企业最近登录</div>
                    </div>
                </div>";
    }
    call_back(1,$str);
}

elseif($op=="evaluate_zan"){

    if($_SESSION['uid']){
        $comment_id = check_pasre($_POST['zan_id'],"参数错误");
        $evaluate = pdo_fetch("select zan from ".tablename(WL."evaluate")." where id=".$comment_id);
        $zan = array_filter(explode(",",$evaluate['zan']));

        array_push($zan,$_SESSION['uid']);
        $zan = implode(",",$zan);

        $r = pdo_update(WL."evaluate",array('zan'=>$zan),array('id'=>$comment_id));
        if($r){
            call_back(1,"修改成功");
        }else{
            call_back(2,"修改失败");
        }
    }else{
        call_back(3,"未登录");
    }
}

elseif ($op=="report_save"){
    $data['company_scale'] = check_pasre($_POST['data']['reason'],"请选择举报原因");
    $data['report_content'] = check_pasre($_POST['data']['jubao_detail'],"请输入举报内容");
    $data['report_uid'] = $_SESSION['uid'];
    $data['evaluate_id'] = check_pasre($_POST['data']['pl_id'],"参数错误");
    $report = pdo_fetch("select id from ".tablename(WL."report")." where report_uid=".$data['report_uid']." and evaluate_id=".$data['evaluate_id']);
    if($report){
        call_back(2,"已举报");
    }else{
        $data['addtime'] = time();
        $r = insert_table($data,WL."report");
        if($r){
            call_back(1,"举报成功");
        }else{
            call_back(2,"举报失败");
        }
    }
}

elseif ($op=="star_hr_slide"){
    $page = $_POST['page']?$_POST['page']:0;
    $company_count = pdo_fetchcolumn("select count(*) from ".tablename(WL."star_hr"));
    if($page*15>$company_count){
        $page = 0;
    }
    $company  = m("company")->star_company_list($page);

    $html = "";
    foreach ($company as $list){
        $html .= "<a href=\"".app_url('member/index/super_company',array('id'=>$list['id']))."\" class=\"surper_company\"><img src=\"/attachment/{$list['headimgurl']}\"> </a>";
    }
    call_back(1,$html);
}
elseif ($op=="star_page_ajax"){
    $page = $_POST['page'];
//    $company_name = $_POST['company_name'];
    $star_companys =m("company")->star_company_list($page,6);

    $html = "";
    if($star_companys){

        foreach ($star_companys as $list){
            $list['star_jobs'] = pdo_fetchall("select id,jobs_name from ".tablename(WL."star_jobs")." where uid=".$list['id']." limit 0,4");
            if($list['star_jobs']){
                $jobs = " <div class=\"recruit_jobs\">
                <img style=\"float: left;margin: 34px 30px 0 0;\" src=\"/addons/recruit/app/resource/images/recruit_job.png\">";
                foreach ($list['star_jobs'] as $li){
                    $jobs .= "<a href='".app_url('member/index/super_company',array('id'=>$list['id']))."' class=\"recruit_jobsbtn\">
                    {$li['jobs_name']}
                </a>";
                }

                $jobs .="
                <a href='". app_url('member/index/super_company')."' style=\"float: right;margin:32px 0 0 0\" class=\"recruit_jobsbtn\">
                    更多职位>>
                </a>
                </div>";
            }


            $html .="
         <div class=\"super_company_lists\">
            <div class=\"super_company_banner\">
                <div class=\"super_company_bannerbox\">
                    <img class=\"changesize\" src=\"/attachment/{$list['banner']}\"/>
                </div>
            </div>
            <div class=\"super_company_msg\">
                <div class=\"super_company_logo\">
                    <a href=\"#\">
                        <img class=\"changesize\" src=\"/attachment/{$list['headimgurl']}\">
                    </a>
                </div>
                <div class=\"surper_company_title\">{$list['companynmae']}</div>
                <div class=\"surper_company_introduce\">
                    ".mb_substr(strip_tags($list['introduce']),0,30,"UTF8")."
                </div>
            </div>
            {$jobs}
        </div>";
        }
    }

    call_back(1,$html);
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



/********************************************第三方登录接入请求*****************************************/
elseif($op=="baidu_callback"){
    $back_top = 1;
    $apiClient = $baidu->getBaiduApiClientService();
    $profile = $apiClient->api('/rest/2.0/passport/users/getInfo',
        array('fields' => 'userid,username,sex,birthday'));
    $openid = $profile['userid'];
    if($profile){
       $account = pdo_fetch("select * from ".tablename(WL."members")." where baidu_openid=".$profile['userid']);
       if($account){
            $_SESSION['uid'] = $account['id'];
            $_SESSION['utype'] = $account['utype'];
           $resume = m("resume")->get_resume( $_SESSION['uid']);
           if(!$resume['fullname']){
               $url = app_url('person/index');
           }elseif (!$resume['edu_experience']){
               $url = app_url('person/index');
           }elseif (!$resume['introduce']){
               $url = app_url('person/index');
           }else{
               $url = $_SESSION['record_url'];

               unset($_SESSION['record_url']);
           }
           header("location:".$url);exit();
//           echo $url;exit();
//           echo "<script>window.location.href='{$url}'</script>";exit();
       }else{
           include wl_template("member/create_bind_account");exit();
       }
    }
}elseif($op=="weibo_callback"){
    $back_top = 1;
    include_once(WL_CORE.'/common/libweibo-master/saetv2.ex.class.php');
    include_once(WL_CORE.'/common/libweibo-master/config.php');
    $o = new SaeTOAuthV2( WB_AKEY , WB_SKEY );
    if (isset($_REQUEST['code'])) {
        $keys = array();
        $keys['code'] = $_REQUEST['code'];
        $keys['redirect_uri'] = WB_CALLBACK_URL;
        try {
            $token = $o->getAccessToken( 'code', $keys ) ;
        } catch (OAuthException $e) {

        }
    }

    if ($token) {
        $weibo_openid = $token['access_token'];
        $account = pdo_fetch("select * from ".tablename(WL."members")." where weibo_openid=".$token['access_token']);
        if($account){
            $_SESSION['uid'] = $account['id'];
            $_SESSION['utype'] = $account['utype'];
            $resume = m("resume")->get_resume( $_SESSION['uid']);
            if(!$resume['fullname'] || !$resume['edu_experience'] || !$resume['birthday']){
                $url = app_url('person/index');
            }else{
                $url = $_SESSION['record_url'];
                unset($_SESSION['record_url']);
            }
            header("location:".$url);exit();
        }else{
            include wl_template("member/create_bind_account");exit();
        }
    }
}elseif ($op=="qq_callback"){
    $back_top = 1;
    if($_GPC['qq_openid']){
        $qq_openid = $_GPC['qq_openid'];
        $account = pdo_fetch("select * from ".tablename(WL."members")." where utype=1 and qq_openid='".$qq_openid."'");
        if($account){
            $_SESSION['uid'] = $account['id'];
            $_SESSION['utype'] = $account['utype'];
            $resume = m("resume")->get_resume($_SESSION['uid']);
            if(!$resume['fullname'] || !$resume['edu_experience'] || !$resume['birthday']){
                $url = app_url('person/index');
            }else{
                $url = $_SESSION['record_url'];
                if(empty($url)){
                    $url = app_url("member/index/login");
                }
                unset($_SESSION['record_url']);
            }
            header("location:".$url);exit();
        }else{
            include wl_template("member/create_bind_account");exit();
        }
    }
}elseif ($op=="weixin_callback"){
    $back_top = 1;
    if($_GPC['weixin_openid']){
        $weixin_openid = $_GPC['weixin_openid'];
        $account = pdo_fetch("select * from ".tablename(WL."members")." where utype=1 and weixin_openid='".$weixin_openid."'");
        if($account){
            $_SESSION['uid'] = $account['id'];
            $_SESSION['utype'] = $account['utype'];
            $resume = m("resume")->get_resume($_SESSION['uid']);
            if(!$resume['fullname'] || !$resume['edu_experience'] || !$resume['birthday']){
                $url = app_url('person/index');
            }else{
                $url = $_SESSION['record_url'];
                if(empty($url)){
                    $url = app_url("member/index/login");
                }
                unset($_SESSION['record_url']);
            }
            header("location:".$url);exit();
        }else{
            include wl_template("member/create_bind_account");exit();
        }
    }


}elseif ($op=="record_url"){
    $_SESSION['record_url'] = $_SERVER["HTTP_REFERER"];
    call_back(1,"ok");
}

elseif($op=="company_center"){
    if($_COOKIE['ab13___session'] && $_GPC['uid']){
        $_SESSION['uid'] = $_GPC['uid'];
        $_SESSION['utype'] = 2;
        header("location:".app_url('company/index/job_manage',array('uid'=>$_GPC['uid'])));exit();
    }
}

if(empty($_SESSION['mid'])){
    header("location:".app_url("member/index/index"));
}






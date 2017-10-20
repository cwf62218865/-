<?php

defined('IN_IA') or exit('Access Denied');
wl_load()->model('verify');
$upload_path = WL_URL_APPS."file/";
$company = m("company")->get_profile($_SESSION['uid']);

//企业注册页面
if($op=="index"){
    include wl_template('member/company_reg1');
}

//企业注册第一步保存
elseif($op=="base_save"){
    $data['username'] =check_pasre($_POST['conpany_username'],"请输入企业登录用户名");
    if(check_phone($_GPC['mobie'])){
        $data['mobile'] =$_GPC['mobie'];
    }
    $data['fullname'] =check_pasre($_POST['res_name'],"请输入联系人名称");
    $identify_code      =check_pasre($_POST['identify_code'],"请输入验证码");
    if($identify_code!=$_SESSION['phone_code']){
        call_back(2,"验证码不正确");
    }
    if(strlen($_POST['password'])<6 || strlen($_POST['password'])>15){
        call_back(2,"请输入6-15位密码");
    }else{
        $data['salt'] = mt_rand(100,999);
        $data['password'] = pwd_hash($_POST['password'],$data['salt']);
    }
    $data['utype'] = 2;
    $data['createtime'] = time();
    $r = pdo_insert(WL."members",$data);
    $_SESSION['uid'] = pdo_insertid();
    $_SESSION['utype'] = 2;
    if($r){
        call_back(1,"注册成功");
    }else{
        call_back(2,"注册失败");
    }
}

elseif($op=="step2"){
    if(!$_SESSION['uid']){
        header("Location: ".app_url("member/login"));
    }
    $identity = encrypt($_SESSION['uid'], 'E');
    include wl_template('company/company_reg2');
}
elseif($op=="send_code"){
    send_codes($_POST['mobie']);
//    var_dump($_POST);exit();
//    wl_load()->model('sms');
//    if(check_phone($_GPC['mobie'])){
//        $phone =$_GPC['mobie'];
//    }
//    if(!$_SESSION['last_sendtime'])
//    {
//        $_SESSION['phone_code']=mt_rand(1000,9999);
//        $_SESSION['last_sendtime']=time();
//        if(sendSms($phone,$_SESSION['phone_code'])){
//            call_back(1,"ok");
//        }
//    }
//    else
//    {
//        if ( (time() - $_SESSION['last_sendtime']) >50 )
//        {
//            $_SESSION['phone_code']=mt_rand(1000,9999);
//            $_SESSION['last_sendtime']=time();
//            if(sendSms($phone,$_SESSION['phone_code'])){
//                call_back(1,"ok");
//            }
//        }else{
//            return false;
//        }
//    }
}

//手机端图片上传页面
elseif ($op=="mobileupload"){
    $id = encrypt($_GPC['identity'], 'D');
    $member = m("member")->get_member($id);
    if($member){
        include wl_template("member/mobileupload1");
    }else{
        die();
    }
}

//图片上传保存处理
elseif ($op=="save_license"){
    $id = encrypt($_GPC['identity'], 'D');
    $member = m("member")->get_member($id);
    if($member){
        $file = $_FILES['file'];//得到传输的数据
//得到文件名称
        $name = $file['name'];
        $type = strtolower(substr($name,strrpos($name,'.')+1)); //得到文件类型，并且都转化成小写
        $allow_type = array('jpg','jpeg','gif','png'); //定义允许上传的类型
//判断文件类型是否被允许上传
        if(!in_array($type, $allow_type)){
            //如果不被允许，则直接停止程序运行
            call_back(2,"文件格式不对") ;
        }
//判断是否是通过HTTP POST上传的
        if(!is_uploaded_file($file['tmp_name'])){
            //如果不是通过HTTP POST上传的
            call_back(2,"上传失败") ;
        }
        $upload_path = WL_APP."resource/file/"; //上传文件的存放路径
        $name = time().$_GPC['identity'].".".$type;
        $upload_path_name = $upload_path.$name;
        //开始移动文件到相应的文件夹
        if(move_uploaded_file($file['tmp_name'],$upload_path_name)){
            $data['uid'] = $id;
            $data['createtime'] = time();
            if($_GPC['kind']==1){
                $data['license'] = WL_URL_APPS."file/".$name;
            }elseif($_GPC['kind']==2){
                $data['idcard1'] = WL_URL_APPS."file/".$name;
            }elseif($_GPC['kind']==3){
                $data['idcard2'] = WL_URL_APPS."file/".$name;
            }
            pdo_insert(WL."members_temp",$data);
            call_back(1,"上传成功") ;
        }else{
            call_back(2,"上传失败") ;
        }
    }else{
        die();
    }
}

//ajax请求上传结果,刷新页面
elseif ($op=="upload_refresh"){
    $uid = $_SESSION['uid'];
    $company_profile = pdo_fetch("select * from ".tablename(WL.'company_profile')." where uid=".$uid);
    $temp = pdo_fetch("select * from ".tablename(WL.'members_temp')." where uid=".$uid);
    if($_GPC['companyname']){
        $data['companyname'] = $_GPC['companyname'];
    }
    if($temp['license']){
        $data['license'] = $temp['license'];
        if($company_profile){
            $r = pdo_update(WL."company_profile",$data,array('uid'=>$uid));
        }else{
            $data['uid'] = $uid;
            $data['createtime']=time();
            $r = pdo_insert(WL."company_profile",$data);
        }
    }elseif ($temp['idcard1']){
        $data['idcard1'] = $temp['idcard1'];
        if($company_profile){
            $r = pdo_update(WL."company_profile",$data,array('uid'=>$uid));
        }else{
            $data['uid'] = $uid;
            $data['createtime']=time();
            $r = pdo_insert(WL."company_profile",$data);
        }
    }elseif ($temp['idcard2']){
        $data['idcard2'] = $temp['idcard2'];
        if($company_profile){
            $r = pdo_update(WL."company_profile",$data,array('uid'=>$uid));
        }else{
            $data['uid'] = $uid;
            $data['createtime']=time();
            $r = pdo_insert(WL."company_profile",$data);
        }
    }
    if($r){
        pdo_delete(WL."members_temp",array("uid"=>$uid));
        call_back(1,"上传成功");
    }
}
//保存第二步信息处理
elseif ($op=="step2_save"){
    $data['companyname'] =check_pasre($_POST['companyname'],"请输入公司名称");
    $data['license'] =check_pasre($_POST['license'],"请上传营业执照");
    $data['idcard1'] =check_pasre($_POST['idcard1'],"请上传法人身份证(正面)");
    $data['idcard2'] =check_pasre($_POST['idcard2'],"请上传法人身份证(反面)");
    $company = pdo_fetch("select id from ".tablename(WL.'company_profile')." where uid=".$_SESSION['uid']);
    if($company){
        $r = pdo_update(WL."company_profile",$data,array('uid'=>$_SESSION['uid']));
    }else{
        $data['uid'] = $_SESSION['uid'];
        $data['createtime']=time();
        $r = pdo_insert(WL."company_profile",$data);
    }
    call_back(1,app_url("member/company_register/step3"));
}

//第三步页面
elseif ($op=="step3"){
    include wl_template("company/company_reg3");
}
//第三步保存处理
elseif ($op=="step3_save"){
    $data['nature'] =check_pasre($_POST['company_property'],"请选择公司性质");
    $data['number'] =check_pasre($_POST['company_scale'],"请选择公司规模");
    $data['industry'] =check_pasre($_POST['company_industry'],"请选择所处行业");
    $data['city'] =check_pasre($_POST['company_city'],"请选择所处地区");
    $data['slogan'] =check_pasre($_POST['slogan'],"slogan不能为空");
    $data['tag'] =check_pasre($_POST['welfare'],"请至少选择一个福利标签");
    $r = pdo_update(WL."company_profile",$data,array('uid'=>$_SESSION['uid']));
    if($r){
        call_back(1,app_url("company/index/job_manage"));
    }
}

//pc图片上传处理
elseif ($op=="upload"){
    $file = $_FILES['file'];//得到传输的数据
    $name = $file['name'];
    $type = strtolower(substr($name,strrpos($name,'.')+1)); //得到文件类型，并且都转化成小写
    $allow_type = array('jpg','jpeg','gif','png'); //定义允许上传的类型
    //判断文件类型是否被允许上传
    if(!in_array($type, $allow_type)){
        //如果不被允许，则直接停止程序运行
        call_back(2,"文件格式不对") ;
    }
    //判断是否是通过HTTP POST上传的
    if(!is_uploaded_file($file['tmp_name'])){
        //如果不是通过HTTP POST上传的
        call_back(2,"上传失败") ;
    }
    $upload_path = WL_APP."resource/file/".date("Y-m-d"); //上传文件的存放路径
    mkdirs($upload_path);
    $name = time().$_SESSION['uid'].".".$type;
    $upload_path_name = $upload_path."/".$name;

    //开始移动文件到相应的文件夹
    if(move_uploaded_file($file['tmp_name'],$upload_path_name)){
        call_back(1,WL_URL_APP."resource/file/".date("Y-m-d")."/".$name);
    }
}




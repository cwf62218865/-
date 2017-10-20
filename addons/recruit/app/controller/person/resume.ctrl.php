<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/21 0021
 * Time: 10:18
 */

defined('IN_IA') or exit('Access Denied');
$member = m('member')->get_member($_SESSION['uid']);
$resume = m("resume")->get_resume($_SESSION['uid']);

if(trim($resume['work_experience'])){
    $work_experience = unserialize($resume['work_experience']);
}else{
    $work_experience = array();
}

if(trim($resume['edu_experience'])){
    $edu_experience = unserialize($resume['edu_experience']);
}else{
    $edu_experience = array();
}

if(trim($resume['personal_works'])){
    $resume['personal_works'] = unserialize($resume['personal_works']);
    $personal_works = array();
    foreach ($resume['personal_works'] as $list){
        $person_works = explode(",",$list['person_works']);
        $list['thumb'] = $person_works[0];
        $personal_works[] = $list;
    }
}else{
    $personal_works = array();
}


if(trim($resume['honor'])){
    $honor = unserialize($resume['honor']);
}else{
    $honor = array();
}

//职位申请处理
if($op=="send_resume"){
    $data['puid'] = $_SESSION['uid'];
    $data['resume_id'] = $resume['id'];
    $data['uid'] = check_pasre($_GPC['companyid'],"参数错误");
    $data['jobs_id'] = check_pasre($_GPC['jobs_id'],"参数错误");
    $data['createtime'] = time();
    $jobs_apply = pdo_fetch("select id from ".tablename(WL."jobs_apply")." where jobs_id=".$data['jobs_id']." and resume_id=".$data['resume_id']);
    if($jobs_apply){
        call_back("2","你已投递过该职位，不要重复投递");
    }else{
        if(insert_table($data,WL."jobs_apply")){
            call_back(1,"ok");
        }else{
            call_back(2,"no");
        }
    }
}

//简历管理页面
elseif ($op=="manage_resume"){
    $identity = encrypt($_SESSION['uid'], 'E');
    include wl_template('resume/resume_manage');
}

//基本信息处理
elseif ($op=="manage_resume_deal"){

    $data['sex'] = check_pasre($_POST['data']['sex'],"请输入性别");

    if(trim($data['sex'])=="男生"){
        $data['sex'] =1;
    }else{
        $data['sex'] =2;
    }
    $data['fullname'] = check_pasre($_POST['data']['user_name'],"请输入姓名");
    $data['work_status'] = check_pasre($_POST['data']['job_status'],"请输入工作状态");
    $data['birthday'] = check_pasre($_POST['data']['birthday'],"请输入出生日期");
    $data['nation'] = check_pasre($_POST['data']['family_name'],"请输入民族");
    $data['origin_place'] = check_pasre($_POST['data']['placeoforigin'],"请输入籍贯");
    $data['political_status'] = check_pasre($_POST['data']['identity'],"请输入政治面貌");
    $data['telphone'] = check_pasre($_POST['data']['moblie'],"请输入电话");
    $data['email'] = check_pasre($_POST['data']['email'],"请输入邮箱");
    $data['city'] = check_pasre($_POST['data']['city'],"请输入城市");
    $data['address'] = $_POST['data']['address_msg'];
    $data['city_area'] = check_pasre($_POST['data']['area'],"请输入城市区域");
    $data['introduce'] = check_pasre($_POST['data']['introduce'],"请输入自我介绍");
    $data['updatetime'] = time();
    $r = pdo_update(WL."resume",$data,array('uid'=>$_SESSION['uid']));
    if($r){
        call_back(1,"修改成功");
    }else{
        call_back(2,"修改失败");
    }
}

//某段工作经验回填调用
elseif ($op=="work_exp"){

    if($_GPC['data_id']!==false){

        echo json_encode($work_experience[$_GPC['data_id']]);exit();
    }
}

//删除一段工作经验
elseif ($op=="work_exp_delete"){
    if($_GPC['data_id']!==false){
        unset($work_experience[$_GPC['data_id']]);
    }
    $work_experience = serialize($work_experience);
    $r = pdo_update(WL."resume",array('work_experience'=>$work_experience),array('uid'=>$_SESSION['uid']));
    if($r){
        call_back(1,"ok");
    }else{
        call_back(2,"no");
    }
}

//修改或者新增工作经验
elseif ($op=="save_work_exp"){

    $data['company_name'] = check_pasre($_POST['data']['company_name'],"请输入公司名称");
    $data['job_name'] = check_pasre($_POST['data']['job_name'],"请输入职位名称");
    $data['job_starttime'] = check_pasre($_POST['data']['start_time'],"请输入开始时间");
    $data['job_endtime'] = check_pasre($_POST['data']['end_time'],"请输入结束时间");
    $data['leave_reason'] = check_pasre($_POST['data']['leave_reason'],"请输入离职原因");
    $data['job_content'] = check_pasre($_POST['data']['job_content'],"请输入工作内容");

    if(is_numeric($_POST['data']['exp_id'])===true){
        $id = $_POST['data']['exp_id'];
        $work_experience[$id] = $data;
    }else{
        array_push($work_experience,$data);
    }
    $work_experience = serialize($work_experience);
    $r = pdo_update(WL."resume",array('work_experience'=>$work_experience),array('uid'=>$_SESSION['uid']));
    if($r){
        call_back(1,"ok");
    }else{
        call_back(2,"no");
    }
}

//某段教育经验回填调用
elseif ($op=="edu_exp"){

    if($_GPC['data_id']!==false){

        echo json_encode($edu_experience[$_GPC['data_id']]);exit();
    }
}

//删除某段教育经验
elseif ($op=="edu_exp_delete"){
    if($_GPC['data_id']!==false){
        unset($edu_experience[$_GPC['data_id']]);
    }
    $edu_experience = serialize($edu_experience);
    $r = pdo_update(WL."resume",array('edu_experience'=>$edu_experience),array('uid'=>$_SESSION['uid']));
    if($r){
        call_back(1,"ok");
    }else{
        call_back(2,"no");
    }
}

//保存或新增教育经验
elseif ($op=="save_edu_exp"){

    $data['school_name'] = check_pasre($_POST['data']['school_name'],"请输入学校名称");
    $data['edu_district'] = check_pasre($_POST['data']['education'],"请输入学历");
    $data['edu_major'] = check_pasre($_POST['data']['edu_major'],"请输入专业");
    $data['edu_finish_time'] = check_pasre($_POST['data']['identity'],"请输入毕业时间");
    if(is_numeric($_POST['data']['exp_id'])===true){
        $id = $_POST['data']['exp_id'];
        $edu_experience[$id] = $data;
    }else{
        array_push($edu_experience,$data);
    }

    $edu_experience = serialize($edu_experience);
    $r = pdo_update(WL."resume",array('edu_experience'=>$edu_experience),array('uid'=>$_SESSION['uid']));
    if($r){
        call_back(1,"ok");
    }else{
        call_back(2,"no");
    }
}

//发送邮箱
elseif ($op=="send_email"){


//    require(WL_CORE.'common/html2fpdf_zh_cn/html2fpdf.php');
//    $pdf=new HTML2FPDF();
//    $pdf->AddGBFont('GB','仿宋_GB2312');
//    $pdf->AddPage();
//    $fp = fopen("http://localhost/app/index.php?c=site&a=entry&m=recruit&do=person&ac=resume&op=manage_resume&","r");
//
////    echo filesize("../data/tpl/app/recruit/app/view/pc/resume/resume_manage.tpl.php");exit();
//    $strContent = fread($fp,"99999");
//
//    fclose($fp);
////    $strContent = file_get_contents("http://localhost/app/index.php?c=site&a=entry&m=recruit&do=person&ac=resume&op=manage_resume&");
//
//    $pdf->WriteHTML(iconv("UTF-8","GB2312",$strContent));
//    $pdf->Output("111.pdf");
//    echo "PDF file is generated successfully!";exit();


   $email = check_pasre($_POST['data']['email'],"请输入收件邮箱");
    $body = $_POST['data']['envelope_content'];
    $body = str_replace("\r\n","<br/>",$body);
    $body = str_replace("\n","<br/>",$body);
    ihttp_email($email, $resume['fullname'].'的简历推荐'.date('Y-m-d H:i:s'), $body);
    call_back(1,"ok");

}
//头像上传
elseif ($op=="headimg_upload"){
    $temp_headimgurl = upload_img($_FILES);
    if($temp_headimgurl){
        $headimgurl = str_replace("/temp/","/file/",$temp_headimgurl);
        $resume = pdo_fetch("select id from ".tablename(WL."resume")." where uid=".$_SESSION['uid']);
        if(rename($_SERVER['DOCUMENT_ROOT'].$temp_headimgurl,$_SERVER['DOCUMENT_ROOT'].$headimgurl)){
            if($resume){

                unlink($_SERVER['DOCUMENT_ROOT'].$resume['headimgurl']);
                $r = pdo_update(WL."resume",array('headimgurl'=>$headimgurl,'updatetime'=>time()),array('uid'=>$_SESSION['uid']));
            }else{

                $data['headimgurl'] = $headimgurl;
                $data['addtime'] =time();
                $data['updatetime'] =time();
                $data['uid'] = $_SESSION['uid'];

                $r = insert_table($data,WL."resume");
//                $r = pdo_insert(WL."resume",$data);
            }
            if($r){
                call_back(1,"修改成功",$headimgurl);
            }else{
                call_back(2,"修改失败");
            }
        };
    }
}

//手机端处理头像上传
elseif ($op=="headimgupload_deal"){
    $temp = pdo_fetch("select headimgurl from ".tablename(WL."members_temp")." where uid=".$_SESSION['uid']);
    if($temp['headimgurl']){
        unlink($_SERVER['DOCUMENT_ROOT'].$resume['headimgurl']);
        $r = pdo_update(WL."resume",array('headimgurl'=>$temp['headimgurl'],'updatetime'=>time()),array('uid'=>$_SESSION['uid']));
        if($r){
            pdo_delete(WL."members_temp",array('uid'=>$_SESSION['uid']));
            call_back(1,"处理成功");
        }else{
            call_back(2,"处理失败");
        }
    }else{
        call_back(2,"暂无数据");
    }
}


//个人作品保存
elseif ($op=="save_personal_works"){
//    var_dump($personal_works);exit();
    $person_works = check_pasre($_POST['data']['person_works'],"请上传个人作品");
    $data['person_works'] = str_replace("/temp/","/file/",$person_works);
    $person_works = explode(",",$person_works);

    foreach ($person_works as $list){
        $new_path = str_replace("/temp/","/file/",$list);
        rename($_SERVER['DOCUMENT_ROOT'].$list,$_SERVER['DOCUMENT_ROOT'].$new_path);
    }
    $data['person_workmsg'] = check_pasre($_POST['data']['person_workmsg'],"请填写描述作品");

    if(is_numeric($_POST['data']['works_url'])===true){

        $id = $_POST['data']['works_url'];
        $personal_works[$id] = $data;
    }else{

        array_push($personal_works,$data);
    }

    $personal_works = serialize($personal_works);
    $r = pdo_update(WL."resume",array('personal_works'=>$personal_works,'updatetime'=>time()),array('uid'=>$_SESSION['uid']));
    if($r){
        call_back(1,"添加成功");
    }else{
        call_back(2,"添加失败");
    }
}




//个人作品上传
elseif ($op=="works_upload"){
    $worksurl = upload_img($_FILES);
    call_back(1,$worksurl);
//    if($worksurl){
//        unlink($_SERVER['DOCUMENT_ROOT'].$resume['person_works']);
//        $r = pdo_update(WL."resume",array('person_works'=>$worksurl,'updatetime'=>time()),array('uid'=>$_SESSION['uid']));
//        if($r){
//            call_back(1,"修改成功");
//        }else{
//            call_back(2,"修改失败");
//        }
//    }
}

//某段个人作品回填调用
elseif ($op=="personal_work"){
    if($_GPC['data_id']!==false){
        echo json_encode($personal_works[$_GPC['data_id']]);exit();
    }
}

elseif($op=="personal_work_delete"){

    if(is_numeric($_POST['data_id'])===true){
        $id = $_POST['data_id'];
       unset($personal_works[$id]);
        $personal_works = serialize($personal_works);
        $r = pdo_update(WL."resume",array('personal_works'=>$personal_works,'updatetime'=>time()),array('uid'=>$_SESSION['uid']));
        if($r){
            call_back(1,"删除成功");
        }else{
            call_back(2,"删除失败");
        }
    }
}


//个人作品处理
elseif ($op=="personal_works_deal"){

}



//荣誉证书保存
elseif ($op=="save_certificate"){


    $data['certificate_img'] = check_pasre($_POST['data']['certificate_img'],"参数错误");
    $data['certificate_time'] = check_pasre($_POST['data']['certificate_time'],"参数错误");
    $data['certificate_content'] = check_pasre($_POST['data']['certificate_content'],"参数错误");

    if(is_numeric($_POST['data']['certificate_id'])===true){
        $id = $_POST['data']['certificate_id'];
        $honor[$id] = $data;
    }else{
        array_push($honor,$data);
    }
    $honor = serialize($honor);
    $r = pdo_update(WL."resume",array('honor'=>$honor),array('uid'=>$_SESSION['uid']));
    if($r){
        call_back(1,"ok");
    }else{
        call_back(2,"no");
    }
//    var_dump($_POST);exit()
}


//某段荣誉回填调用
elseif ($op=="show_honor"){
    if($_GPC['data_id']!==false){
        echo json_encode($honor[$_GPC['data_id']]);exit();
    }
}

//删除某段荣誉
elseif ($op=="honor_delete"){
    if(is_numeric($_POST['data_id'])===true){
        $id = $_POST['data_id'];
        unset($honor[$id]);
        $honor = serialize($honor);
        $r = pdo_update(WL."resume",array('honor'=>$honor,'updatetime'=>time()),array('uid'=>$_SESSION['uid']));
        if($r){
            call_back(1,"删除成功");
        }else{
            call_back(2,"删除失败");
        }
    }
}

//荣誉证书上传
elseif ($op=="certificate_upload"){
    $temp_certificate = upload_img($_FILES);
    call_back(1,$temp_certificate);
//    echo json_encode($temp_certificate);exit();
}

//下载简历
elseif ($op=="download_resume"){
//    $download_resume_uid = check_pasre($_POST['data_id'],)
}

elseif ($op=="video_upload"){
    $temp_video = upload_video($_FILES);
    $video = str_replace("/temp/","/file/",$temp_video);

    if(rename($_SERVER['DOCUMENT_ROOT'].$temp_video,$_SERVER['DOCUMENT_ROOT'].$video)){
        unlink($_SERVER['DOCUMENT_ROOT'] . $resume['personal_video']);
        $r = pdo_update(WL . "resume", array('person_video' => $video, 'updatetime' => time()), array('uid' => $_SESSION['uid']));
        if($r){
            call_back(1,"修改成功",$video);
        }else{
            call_back(2,"修改失败");
        }
    }
}

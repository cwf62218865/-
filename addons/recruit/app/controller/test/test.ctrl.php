<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/25 0025
 * Time: 13:41
 */
//echo $_SERVER['REMOTE_ADDR'] ;exit();

function GetIpLookup($ip = ''){
    if(empty($ip)){
        return '请输入IP地址';
    }
    $res = @file_get_contents('http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js&ip=' . $ip);
    if(empty($res)){ return false; }
    $jsonMatches = array();
    preg_match('#\{.+?\}#', $res, $jsonMatches);
    if(!isset($jsonMatches[0])){ return false; }
    $json = json_decode($jsonMatches[0], true);
    if(isset($json['ret']) && $json['ret'] == 1){
        $json['ip'] = $ip;
        unset($json['ret']);
    }else{
        return false;
    }
    return $json;
}
$ipInfos = GetIpLookup($_SERVER['REMOTE_ADDR']); //baidu.com IP地址
var_dump($ipInfos);exit();
/*
 * 发布职位数据
 */
$hotword = pdo_fetchall("select word from ".tablename(WL."hotword"));
$arr = "var mycars=new Array(";

foreach ($hotword as $list){
    $arr = $arr."'".$list[word]."',";
}
echo $arr;exit();
var_dump($hotword);exit();



use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
if($op=="jobs") {
    $data['jobs_name'] = "web前端";
    $data['city'] = "北京";
    $data['city_area'] = "朝阳区";
    $data['wage_min'] = "4000";
    $data['wage_max'] = "6000";
    $data['tag'] = "五险一金,美女多,双休";

$url = app_url("company/jobs/send_jobs");
    foreach ($data as $key => $list) {
        $url = $url.$key."=".$list."&";
    }

echo $url;exit();

}elseif ($op=="send_resume"){

    $data['companyid'] = "655";
    $data['jobs_id'] = 31;

    $url = app_url("person/resume/send_resume");
    foreach ($data as $key => $list) {
        $url = $url.$key."=".$list."&";
    }

    echo $url;exit();
}elseif ($op=="resume_deal"){
    $data['jobs_apply_id'] = 2;
    $data['status'] = 2;
    $data['address'] = "北京朝阳市";
    $data['linker'] = "包崇林";
    $data['interview_time'] = "2017年9月25 上午9:50";

    $url = app_url("company/resume/check_resume_deal");
    foreach ($data as $key => $list) {
        $url = $url.$key."=".$list."&";
    }

    echo $url;exit();
}elseif ($op=="collect"){
    $data['jobs_id'] = 1;
    $url = app_url("person/collection/collection_jobs");
    echo $url."&jobs_id=".$data['jobs_id'];exit();
}elseif ($op=="news"){
    include wl_template("member/news");exit();
 }
 elseif ($op=="agreement"){
     include wl_template("member/agreement");exit();
  }

  elseif ($op=="super_company_list"){
    include wl_template("company/super_company_list");exit();
}


elseif ($op=="test"){
    require WL_CORE."common/qiniu_sdk/autoload.php";
    $bucket = "yingjieseng";
    $auth = new Auth();

    $upToken = $auth->uploadToken($bucket);
    include wl_template("member/test");exit();
}elseif ($op=="test1"){
    var_dump($_REQUEST);exit();
}
elseif ($op=="upload_test"){
    require WL_CORE."common/qiniu_sdk/autoload.php";
    $bucket = "yingjieseng";
    $auth = new Auth();
    $uploadMgr = new UploadManager();
    $token = $auth->uploadToken($bucket);
    $name=$_FILES['file']['name'];
    $filePath=$_FILES['file']['tmp_name'];
    $type=$_FILES['file']['type'];
    list($ret, $err) = $uploadMgr->putFile($token, $name, $filePath,null,$type,false);
    echo "\n====> putFile result: \n";
    if ($err !== null) {
        var_dump($err);
    } else {
        var_dump($ret);
    }
}elseif($op=="q"){
    a($a);
    echo $a;exit();
}


function a(&$a=1){
    $a = 8;
}

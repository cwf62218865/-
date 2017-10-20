<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/25 0025
 * Time: 13:41
 */

/*
 * 发布职位数据
 */
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
}

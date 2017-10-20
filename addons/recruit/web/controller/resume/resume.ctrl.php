<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/28 0028
 * Time: 16:36
 */
defined('IN_IA') or exit('Access Denied');

$ops = array('display','detail','ajax','addmember','editmember');
$op = in_array($op, $ops) ? $op : 'display';


if($op=="display"){
    $where = "";
    $size = 15;
    $page = $_GPC['page'];
    $sqlTotal = pdo_sql_select_count_from(WL.'resume') . $where;
    $sqlData = pdo_sql_select_all_from(WL.'resume') . $where . ' ORDER BY `id` DESC ';

    $list = pdo_pagination($sqlTotal, $sqlData, $params, '', $total, $page, $size);
    $lists = "";
    foreach ($list as $resume){
        $edu_experience = unserialize($resume['edu_experience']);
        $education = "";
        $id = "";
        $edu = array('专科以下','专科','本科','硕士','博士','博士以上');
        $arr_edu = array_flip($edu);
        foreach ($edu_experience as $key=>$list){
            $value = $list['edu_district'];
            if(empty($education)){
                $education = $arr_edu[$value];
                $id = $key;
            }else{
                if($arr_edu[$value]>$education){
                    $education = $arr_edu[$value];
                    $id = $key;
                }
            }
        }
        $resume['education'] = $edu[$education];
        $resume['arr_education'] = $edu_experience[$id];
        $work_experience = unserialize($resume['work_experience']);

        $work_time = "";
        foreach ($work_experience as $list){
            $list['job_starttime'] = str_replace("月","",str_replace("年","-",$list['job_starttime']));
            $time = strtotime($list['job_starttime']);
            if(empty($work_time)){
                $work_time = $time;
            }else{
                if($time<$work_time){
                    $work_time = $time;
                }
            }
        }
        $resume['work_time'] =  date('Y')-date('Y',$work_time);

        $lists[] = $resume;
    }
    $pager = pagination($total, $page, $size);
    include wl_template("resume/resume");
}

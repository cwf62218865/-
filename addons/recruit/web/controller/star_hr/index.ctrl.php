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
    $sqlTotal = pdo_sql_select_count_from(WL.'star_hr') . $where;
    $sqlData = pdo_sql_select_all_from(WL.'star_hr') . $where . ' ORDER BY `id` asc ';

    $lists = pdo_pagination($sqlTotal, $sqlData, $params, '', $total, $page, $size);
    $star_hr = "";
    foreach ($lists as $list){
        $list['jobs_count'] = pdo_fetchcolumn("select count(*) from ".tablename(WL."star_jobs")." where uid=".$list['id']);
        $list['career_count'] = pdo_fetchcolumn("select count(*) from ".tablename(WL."star_career")." where uid=".$list['id']);
        $star_hr[] = $list;
    }
    $pager = pagination($total, $page, $size);
    include wl_template("star_hr/display");
}

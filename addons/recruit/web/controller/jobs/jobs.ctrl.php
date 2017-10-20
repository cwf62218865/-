<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/28 0028
 * Time: 10:39
 */
defined('IN_IA') or exit('Access Denied');
$ops = array('display','detail','ajax','addmember','editmember');
$op = in_array($op, $ops) ? $op : 'display';

if($op=="display"){
    $where = "";
    $size = 15;
    $page = $_GPC['page'];
    $sqlTotal = pdo_sql_select_count_from(WL.'jobs') . $where;
    $sqlData = pdo_sql_select_all_from(WL.'jobs') . $where . ' ORDER BY `id` DESC ';

    $list = pdo_pagination($sqlTotal, $sqlData, $params, '', $total, $page, $size);
    $pager = pagination($total, $page, $size);
    include wl_template("jobs/jobs");
}




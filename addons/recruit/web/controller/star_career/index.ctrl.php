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
    $where = " where uid=".$_GPC['uid'];
    $size = 15;
    $page = $_GPC['page'];
    $sqlTotal = pdo_sql_select_count_from(WL.'star_career') . $where;

    $sqlData = pdo_sql_select_all_from(WL.'star_career') . $where . ' ORDER BY `id` desc ';

    $lists = pdo_pagination($sqlTotal, $sqlData, $params, '', $total, $page, $size);

    $pager = pagination($total, $page, $size);
    include wl_template("star_career/display");
}

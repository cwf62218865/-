<?php
defined('IN_IA') or exit('Access Denied');
wl_load()->model('mc');
$ops = array('display','detail','ajax','add','edit');
$op = in_array($op, $ops) ? $op : 'display';

if($op=="display"){
    $where = "";
    $size = 15;
    $page = $_GPC['page'];
    $sqlTotal = pdo_sql_select_count_from(WL.'position') . $where;
    $sqlData = pdo_sql_select_all_from(WL.'position') . $where . ' ORDER BY `id` asc ';

    $list = pdo_pagination($sqlTotal, $sqlData, $params, '', $total, $page, $size);
    $pager = pagination($total, $page, $size);
    include wl_template("position/display");
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
//        if($r){
//            echo 1;
//        }else{
//            echo 2;
//        }
//        exit();
    }
    include wl_template("position/add");
}





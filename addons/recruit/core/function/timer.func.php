<?php

/**
 * 天数差
 */
function diffDate($date1,$date2){

    $startdate=strtotime($date1);
    $enddate=strtotime($date2);
    $days=round(($enddate-$startdate)/3600/24) ;
    return  $days; //days为得到的天数;
}


/*
 * 与当前时间对比，获取多久之前
 */

function _format_date($time){
    $t=time()-$time;
    $f=array(
        '31536000'=>'年',
        '2592000'=>'个月',
        '604800'=>'星期',
        '86400'=>'天',
        '3600'=>'小时',
        '60'=>'分钟',
        '1'=>'秒'
    );
    foreach ($f as $k=>$v)    {
        if (0 !=$c=floor($t/(int)$k)) {
            return $c.$v.'前';
        }
    }
}


/*
 * 当前时间拆分，分别获取年月日时分秒
 */
function doTimeParse($time)
{
    $year   = floor($time / 60 / 60 / 24 / 365);
    $time  -= $year * 60 * 60 * 24 * 365;
    $month  = floor($time / 60 / 60 / 24 / 30);
    $time  -= $month * 60 * 60 * 24 * 30;
    $week   = floor($time / 60 / 60 / 24 / 7);
    $time  -= $week * 60 * 60 * 24 * 7;
    $day    = floor($time / 60 / 60 / 24);
    $time  -= $day * 60 * 60 * 24;
    $hour   = floor($time / 60 / 60);
    $time  -= $hour * 60 * 60;
    $minute = floor($time / 60);
    $time  -= $minute * 60;
    $second = $time;
    $elapse = '';

    $unitArr = array('年'  =>'year', '个月'=>'month',  '周'=>'week', '天'=>'day',
        '小时'=>'hour', '分钟'=>'minute', '秒'=>'second'
    );

    foreach ( $unitArr as $cn => $u )
    {
        if ( $$u > 0 )
        {
            $elapse = $$u . $cn;
            break;
        }
    }
    return "发表于".$elapse."前";
}
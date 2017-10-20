<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/15 0015
 * Time: 08:49
 */

defined('IN_IA') or exit('Access Denied');
$op = $_GPC['op']?$_GPC['op']:"index";
if($op=="index"){
    include wl_template("member/reg_screen");
}
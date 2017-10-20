<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/21 0021
 * Time: 10:14
 */

defined('IN_IA') or exit('Access Denied');
if($op=="index"){
    m("resume")->get_resume();
}
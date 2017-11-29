<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/28 0028
 * Time: 17:44
 */

define('IN_MOBILE', true);
require '../framework/bootstrap.inc.php';
load()->app('common');
load()->app('template');
load()->model('app');
require IA_ROOT . '/app/common/bootstrap.app.inc.php';
load()->func('communication');


ihttp_email("1421514791@qq.com", '包崇林的简历推荐'.date('Y-m-d H:i:s'), "12345上山打老虎");


<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/28 0028
 * Time: 11:35
 */

if($op=="list"){
    include wl_template("test/list");
}elseif ($op=="detail"){

    if (checksubmit('submit')) {
        message('更新设置成功！', web_url('test/index/detail'));
    }
    include wl_template("test/detail");
}
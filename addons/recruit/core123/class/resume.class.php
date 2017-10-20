<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/18 0018
 * Time: 16:41
 */

class resume{

    /*
     * 获取一行简历
     */
    public function get_resume($uid){
        $resume = pdo_fetch("select * from ".tablename('resume')." where uid=".$uid);
        return $resume;
    }
    
}
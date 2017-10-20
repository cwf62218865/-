<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/13 0013
 * Time: 16:56
 */

class member{
    /*
     * 获取一行用户信息
     */
    public function get_member($id){
        $member = pdo_fetch("select * from ".tablename(WL.'members')." where id=".$id);
        return $member;
    }
    
}
<?php

defined('IN_IA') or exit('Access Denied');

/*
 * 验证手机号
 * kind 类型 1验证是否存在，2表示其他
 */
function check_phone($mobile,$kind=1){
    if(!(trim($mobile))){
        call_back(2,"请输入联系人手机号码");
    }
    if(preg_match("/^1[34578]\d{9}$/", $mobile)){
        if($kind==1){
            $mobile = pdo_fetch("select mobile from ".tablename("members")." where mobile=".$mobile);
            if($mobile){
                call_back(2,"该手机号已被注册");
            }else{
                return true;
            }
        }else{
            return true;
        }
    }else{
        call_back(2,"请输入正确的手机号");
    }
}

/*
 *验证用户是否存在
 */
function member_exists($username){

    $username = trim($username);
    $member = pdo_fetch("select * from ".tablename("members")." where mobile='".$username."' or username='".$username."'");
    if($member){
        return $member;
    }else{
        call_back(2,"该用户不存在");
    }
}
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


    //企业最后登录时间
    public function last_login($uid){
        $last_login_time = pdo_fetch("select last_login_time from ".tablename(WL."members")." where id=".$uid);
        $last_login_time = intval((time()-$last_login_time['last_login_time'])/86400);
        if($last_login_time==1){
            $last_login_time = "昨天";
        }elseif ($last_login_time==2){
            $last_login_time = "前天";
        }elseif ($last_login_time>2 && $last_login_time<8){
            $last_login_time = "3天前";
        }elseif($last_login_time>7){
            $last_login_time = "一周前";
        }else{
            $last_login_time = "今天";
        }

        return $last_login_time;
    }


    //公司列表分页
    public function company_list($page=0,$pagenum=8){
        $limit = " limit ".$page*$pagenum.",".$pagenum;
        $company = pdo_fetchall("select * from ".tablename(WL."company_profile")." order by id desc ".$limit);
        $company_profile = "";
        foreach ($company as $key=>$list){
            $company_profile[$key] = $list;
            $company_profile[$key]['last_login'] = $this->last_login($list['uid']);
            $company_profile[$key]['jobs_count'] = pdo_fetchcolumn("select count(*) from ".tablename(WL."jobs")." where uid=".$list['uid']);
        }
        return $company_profile;
    }


}
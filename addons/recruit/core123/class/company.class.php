<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/18 0018
 * Time: 10:38
 */

class company{

    public function get_profile($uid){
        $company = pdo_fetch("select * from ".tablename('company_profile')." where uid=".$uid);
        return $company;
    }

    /*
     * 收藏简历
     */
    public function collect_resume($uid,$resume_id){
        $collect_resume = pdo_fetch("select id from ".tablename('collect_resume')." where uid=".$uid." and resume_id=".$resume_id);
        if($collect_resume){
            return true;
        }else{
            $resume = m("resume")->get_resume($resume_id);
            $data['uid'] = $uid;
            $data['resume_id'] = $resume_id;
            $data['puid'] = $resume['uid'];
            $data['createtime'] = time();
           return pdo_insert("collect_resume",$data);
        }
    }

    /*
     * 取消收藏简历
     */
    public function cancel_collect_resume($id){
        return pdo_delete("collect_resume",array("id"=>$id));
    }

    /*
     * 停止招聘
     */
    public function cancel_recruit($jobs_id){
        return pdo_update("jobs",array("open"=>0),array("id"=>$jobs_id));
    }

    /*
     * 收到的简历
     */
    public function getall_resume($uid,$jobs_id=""){
        $wheresql = " where 1=1 and uid=".$uid;
        if($jobs_id){
            $wheresql .=" and jobs_id=".$jobs_id;
        }
        $resume_jobs = pdo_fetchall("select resume_id from ".tablename('resume_jobs').$wheresql);
        $resume = "";
        foreach ($resume_jobs as $list){
            $resume[] = pdo_fetch("select * from ".tablename('resume')." where id=".$list['resume_id']);
        }
        return $resume;
    }
    
    
    
}
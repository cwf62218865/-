<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/21 0021
 * Time: 14:08
 */

class person{

    private $resume;
    public function __construct()
    {
        if($_SESSION['utype']==1){
            $this->resume = m('resume');
        }else{
//            header("location:".app_url("member/index/index"));
            die("只有求职者用户才能访问");
        }
    }

    public function get_profile(){

    }


    /*
     * 投递简历
     */
    public function send_resume($uid,$jobs_id){
        $resume = $this->get_resume($uid);
        $jobs = m("jobs")->get_jobs($jobs_id);
        $data['resume_id'] = $resume['id'];
        $data['puid'] = $resume['uid'];
        $data['uid'] = $jobs['uid'];
        $data['jobs_id'] = $jobs['id'];
        $data['creatime'] = time();
        $resume_jobs = pdo_fetch("select * from ".tablename(WL.'jobs_apply')." where puid=".$uid." and jobs_id=".$jobs_id);
        if($resume_jobs){
            return true;
        }else{
            pdo_insert(WL."resume_jobs",$data);
        }
    }
    
    /*
     * 查看投递的简历
     */
    public function check_send_resume(){
        
    }

    /*
     * 收藏职位
     */
    public function collect_jobs($page=0,$pagenum=6){
        $limit = " limit ".$page*$pagenum.",".$pagenum;
        $collect_job = pdo_fetchall("select c.createtime,j.* from ".tablename(WL."collect_jobs")." as c,".tablename(WL."jobs")." as j where c.jobs_id=j.id and c.uid=".$_SESSION['uid'].$limit);
        $collect_jobs['count'] = pdo_fetchall("select count(*) from ".tablename(WL."collect_jobs")." where uid=".$_SESSION['uid'].$limit);
        $collect_jobs['list'] = "";
        foreach ($collect_job as $list){
            $headimgurl = pdo_fetch("select headimgurl,tag from ".tablename(WL."company_profile")." where uid=".$list['uid']);
            $jobs_apply = pdo_fetch("select id from ".tablename(WL."jobs_apply")." where puid=".$_SESSION['uid']." and jobs_id=".$list['id']);
            if(empty($jobs_apply)){
                $list['post_status'] = 1;
            }
            $list['headimgurl'] = $headimgurl['headimgurl'];
            $list['tag'] = $headimgurl['tag'];
            $collect_jobs['list'][] = $list;
        }

        return $collect_jobs;
        
    }

    /*
     * 评价面试职位
     */
    public function comment_jobs($uid,$jobs_id,$content){
        $resume_jobs = pdo_fetch("select * from ".tablename(WL.'resume_jobs')." where offer=1 and puid=".$uid." and jobs_id=".$jobs_id);
        if($resume_jobs){
            $data['uid'] = $resume_jobs['uid'];
            $data['puid'] = $resume_jobs['puid'];
            $data['resume_id'] = $resume_jobs['resume_id'];
            $data['jobs_id'] = $resume_jobs['jobs_id'];
            $data['content'] = $content;
            $data['createtime'] = time();
            return pdo_insert(WL."comment",$data);
        }else{
           call_back(2,"还没有面试邀请");
        }
    }
}
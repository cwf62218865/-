<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/18 0018
 * Time: 10:38
 */

class company{

    private $jobs = "";

    public function __construct()
    {
        if($_SESSION['utype']==2){
            $this->jobs = m('jobs');
        }else{
//            header("location:".app_url("member/index/index"));
//            die("只有企业用户才能访问");
        }
    }

    public function get_profile($uid,$field=""){
        if(empty($field)){
            $field = "*";
        }
        $company = pdo_fetch("select ".$field." from ".tablename(WL.'company_profile')." where uid=".$uid);
        return $company;
    }

    /*
     * 收藏简历
     */
    public function collect_resume($uid,$resume_id){
        $collect_resume = pdo_fetch("select id from ".tablename(WL.'collect_resume')." where uid=".$uid." and resume_id=".$resume_id);
        if($collect_resume){
            return true;
        }else{
            $resume = m("resume")->get_resume($resume_id);
            $data['uid'] = $uid;
            $data['resume_id'] = $resume_id;
            $data['puid'] = $resume['uid'];
            $data['createtime'] = time();
           return pdo_insert(WL."collect_resume",$data);
        }
    }

    /*
     * 取消收藏简历
     */
    public function cancel_collect_resume($id){
        return pdo_delete(WL."collect_resume",array("id"=>$id));
    }

    /*
     * 停止招聘
     */
    public function cancel_recruit($jobs_id){
        return pdo_update(WL."jobs",array("open"=>0),array("id"=>$jobs_id));
    }

    /*
     * 收到的简历
     * status 1表示收到的简历，2表示面试邀请的简历 3表示收到的简历
     */
    public function getall_resume($uid,$page=0,$status=1,$jobs_id="",$unset_id=""){
        $wheresql = " where uid=".$uid;

        if($status==1){
            $wheresql .=" and direction=2 and status=3 ";
        }elseif ($status==2){
            $wheresql .=" and status=3 and offer=1 ";
        }elseif ($status==3){
            $wheresql .=" and direction=2 ";
        }

        if($jobs_id){
            $wheresql .=" and jobs_id=".$jobs_id;
        }

        if($unset_id){
            $orderby = " order by case when id=".$unset_id." then 0 else 1 end,createtime desc";
        }else{
            $orderby = " order by createtime desc";
        }
        if($page==-1){
            $limit = "";
        }else{
            $limit = " limit ".($page*6).",6";
        }


        $resume_jobs = pdo_fetchall("select id,resume_id,jobs_id,uid,puid,status from ".tablename(WL.'jobs_apply').$wheresql.$orderby.$limit);


        $resumes = "";
        foreach ($resume_jobs as $list){
            $resume = m("resume")->get_resume($list['puid']);
            $job = m("jobs")->get_jobs($list['jobs_id']);
            $resume['collect_resume'] = pdo_fetch("select id from ".tablename(WL.'collect_resume')." where jobs_id=".$list['jobs_id']." and resume_id=".$list['resume_id']);

            $resume['jobs_name'] = $job['jobs_name'];
            $resume['open'] = $job['open'];
            $resume['jobs_id'] = $list['jobs_id'];
            $resume['apply_id'] = $list['id'];
            $resume['puid'] = $list['puid'];
            $resume['status'] = $list['status'];
            if($list['status']==3){
                $interview = pdo_fetch("select * from ".tablename(WL.'interview')." where apply_id=".$list['id']);
                $resume['linker'] = $interview['linker'];
                $resume['link_phone'] = $interview['link_phone'];
                $resume['interview_time'] = $interview['interview_time'];
                $interview_time = explode(" ",$interview['interview_time']);
                $resume['interview_date'] = str_replace("月","-",str_replace("日","",$interview_time[0]));

            }

            $resumes[] = $resume;
        }
        return array_filter($resumes);
    }



    /*
     * 我收藏的简历
     */
    public function  getall_collect($uid,$page=0,$resume_id=""){
        $wheresql = " where 1=1 and uid=".$uid;
        if($resume_id){
            $wheresql .=" and resume_id=".$resume_id;
        }
        $limit = " limit ".($page*6).",6";
        $resume_jobs = pdo_fetchall("select * from ".tablename(WL.'collect_resume').$wheresql.$limit);
//        var_dump($resume_jobs);exit();
        $resumes = "";
        foreach ($resume_jobs as $list){
            $resume = pdo_fetch("select * from ".tablename(WL.'resume')." where id=".$list['resume_id']);
            if($resume['experience']){
                $resume['experience'] = $resume['experience']."年以上工作经验";
            }else{
                $resume['experience'] = "无工作经验";
            }
            $job = pdo_fetch("select jobs_name from ".tablename(WL.'jobs')." where id=".$list['jobs_id']);
            $resume['jobs_name'] = $job['jobs_name'];
            $resume['remark'] = $list['remark'];
            $resume['collect_id'] = $list['id'];
            $resumes[] = $resume;
        }
        return $resumes;
    }


    /*
     * 热门公司筛选
     */
    public function company_list(){
        $companys = pdo_fetchall("select * from ".tablename(WL.'company_profile')." order by createtime desc limit 0,5");

        $company = "";
        foreach ($companys as $list){
            $list['jobs_num'] = pdo_fetchcolumn("select count(*) from ".tablename(WL.'jobs')." where uid=".$list['uid']);

            $company[] = $list;
        }
        return $company;
    }

    /*
     * 名企分页列表
     */
    public function star_company_list($page=0){
        $limit = " limit ".($page*15).",15";
        $company = pdo_fetchall("select * from ".tablename(WL."star_hr").$limit);
        return $company;
    }

}
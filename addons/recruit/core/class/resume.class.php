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
    public function get_resume($uid,$filed=""){
        if(empty($filed)){
            $filed = "*";
            $resume = pdo_fetch("select ".$filed." from ".tablename(WL.'resume')." where uid=".$uid);
        }else{
            $resume = pdo_fetch("select ".$filed." from ".tablename(WL.'resume')." where uid=".$uid);
            return $resume;
        }

        if($resume){
            if($resume['experience']){
                $resume['experience'] = $resume['experience']."年以上工作经验";
            }else{
                $resume['experience'] = "无工作经验";
            }
        }
        return $resume;
    }

    /*
     * 我投递过的职位
     * status 0表示所有 1表示投递申请 2表示面试邀请 3表示同意面试
     */
    public function jobs_apply($uid,$page=0,$status=1){
        if($page==-1){
            $limit = "";
        }else{
            $limit = " limit ".($page*6).",6";
        }
        if($status==1){
            $jobs_apply = pdo_fetchall("select * from ".tablename(WL."jobs_apply")." where direction=2 and offer=1 and puid=".$uid." order by createtime desc ".$limit);
        }elseif($status==2){
            $jobs_apply = pdo_fetchall("select * from ".tablename(WL."jobs_apply")." where direction=1 and status=3 and puid=".$uid." order by createtime desc ".$limit);
        }elseif ($status==0){

            $jobs_apply = pdo_fetchall("select * from ".tablename(WL."jobs_apply")." where puid=".$uid." order by createtime desc ".$limit);
        }elseif ($status==3){
            $jobs_apply = pdo_fetchall("select * from ".tablename(WL."jobs_apply")." where comment=0 and offer=1 and status=3 and puid=".$uid." order by createtime desc ".$limit);
        }

        $jobs = "";
        foreach ($jobs_apply as $key=>$list){
            $jobs[$key] = pdo_fetch("select * from ".tablename(WL.'jobs')." where id=".$list['jobs_id']);
            $jobs[$key]['status'] = $list['status'];
            $jobs[$key]['apply_id'] = $list['id'];
            $jobs[$key]['company_uid'] = $list['uid'];
            $jobs[$key]['direction'] = $list['direction'];
            $jobs[$key]['createtime'] = $list['createtime'];
            $jobs[$key]['offer'] = $list['offer'];
            if($list['status']==3){
                $jobs[$key]['interview'] = pdo_fetch("select * from ".tablename(WL.'interview')." where jobs_id=".$list['jobs_id']." and resume_id=".$list['resume_id']);
            }
            if($list['comment']==1){
                $comment_jobs = pdo_fetch("select id from ".tablename(WL . "comment")." where hr_reply<>'' and jobs_id=".$list['jobs_id']." and puid=".$list['puid']);
                if($comment_jobs){
                    $jobs[$key]['hr_reply_status'] = 1;
                }
            }
            $company_profile = pdo_fetch("select * from ".tablename(WL."company_profile")." where uid=".$list['uid']);
            $jobs[$key]['tag'] = $company_profile['tag'];
            $jobs[$key]['headimgurl'] = $company_profile['headimgurl'];
            $jobs[$key]['retoate_x'] = $company_profile['retoate_x'];
            $jobs[$key]['retoate_y'] = $company_profile['retoate_y'];
            $jobs[$key]['city'] = $company_profile['city'];

            if($status==3){

                $interview = pdo_fetch("select time_stamp from ".tablename(WL."interview")." where apply_id=".$list['id']);
                if($interview['time_stamp']>time()){
                    $jobs[$key] = "";
                }
            }
        }

        return array_filter($jobs);
    }

    /*
     * 企业邀约简历
     *
     */
    public function resume_apply($uid,$page=1,$status=0){
        if($page==-1){
            $limit = "";
        }else{
            $limit = " limit ".(($page-1)*6).",6";
        }
        if($status==1){
            $jobs_apply = pdo_fetchall("select * from ".tablename(WL."jobs_apply")." where status=3 and direction=1 and uid=".$uid." order by createtime desc ".$limit);
        }elseif ($status==2){
            $jobs_apply = pdo_fetchall("select * from ".tablename(WL."jobs_apply")." where direction=2 and uid=".$uid." order by createtime desc ".$limit);
        }else{
            $jobs_apply = pdo_fetchall("select * from ".tablename(WL."jobs_apply")." where uid=".$uid." order by createtime desc ".$limit);
        }

        $jobs = "";
        foreach ($jobs_apply as $key=>$list){
            $jobs[$key] = pdo_fetch("select id,jobs_name from ".tablename(WL.'jobs')." where id=".$list['jobs_id']);
            $jobs[$key]['status'] = $list['status'];
            $jobs[$key]['apply_id'] = $list['id'];
            $jobs[$key]['company_uid'] = $list['uid'];
            $jobs[$key]['direction'] = $list['direction'];
            $jobs[$key]['createtime'] = $list['createtime'];
            $jobs[$key]['offer'] = $list['offer'];
            $resume = $this->get_resume($list['puid']);
            $jobs[$key]['headimgurl'] = $resume['headimgurl'];
            $jobs[$key]['fullname'] = $resume['fullname'];
            $jobs[$key]['education'] = $resume['education'];
            $jobs[$key]['edu_major'] = $resume['arr_education']['edu_major'];
            $jobs[$key]['school_name'] = $resume['arr_education']['school_name'];
        }

        return $jobs;
    }

    public function jobs_interview($uid,$page=1){
        $limit = " limit ".(($page-1)*6).",6";
        if($page==-1){
            $limit = "";
        }
        $orderby = " order by createtime desc";
        $jobs_apply = pdo_fetchall("select * from ".tablename(WL."jobs_apply")." where direction=1 and status=3 and puid=".$uid.$orderby.$limit);

        $jobs = "";
        foreach ($jobs_apply as $key=>$list){
            $jobs[$key] = m('jobs')->get_jobs($list['jobs_id']);
            $jobs[$key]['status'] = $list['status'];
            $jobs[$key]['apply_id'] = $list['id'];
            $jobs[$key]['direction'] = $list['direction'];
            $jobs[$key]['createtime'] = $list['createtime'];
            $jobs[$key]['offer'] = $list['offer'];
            if($list['status']==3){
                $jobs[$key]['interview'] = pdo_fetch("select * from ".tablename(WL.'interview')." where jobs_id=".$list['jobs_id']." and resume_id=".$list['resume_id']);
            }
            $company_profile = pdo_fetch("select * from ".tablename(WL."company_profile")." where uid=".$list['uid']);
            $jobs[$key]['tag'] = $company_profile['tag'];
            $jobs[$key]['retoate_x'] = $company_profile['retoate_x'];
            $jobs[$key]['retoate_y'] = $company_profile['retoate_y'];
            $jobs[$key]['city'] = $company_profile['city'];
        }
        return $jobs;
    }


    /*
     * 获取多行简历
     */
    public function getall_resume($data=""){
        $wheresql = " where 1=1 ";
        if($data['keyword']){
            $wheresql .=" and (hope_job like '%".$data['keyword']."%' or fullname like '%".$data['keyword']."%' or school_name like '%".$data['keyword']."%' or education like '%".$data['keyword']."%' or major like '%".$data['keyword']."%')";
        }

        if($data['didian']){
            $wheresql .=" and hope_place like '%".$data['didian']."%' ";
        }

        if($data['experience']){
            if($data['experience']=="无经验"){
                $wheresql .=" and experience=0 ";
            }elseif ($data['experience']=="1年以下"){
                $wheresql .=" and experience=1 ";
            }elseif ($data['experience']=="1-3年"){
                $wheresql .=" and experience>=1 and experience<=3";
            }elseif ($data['experience']=="3-5年"){
                $wheresql .=" and experience>=3 and experience<=5";
            }elseif ($data['experience']=="5年以上"){
                $wheresql .=" and experience>=5";
            }
        }

        if($data['major']){
            $wheresql .=" and major=".$data['major'];
        }

        if($data['page']){
            $limit = " limit ".($data['page']*6).",6";
        }else{
            $limit = " limit 0,6";
        }
        $resumes = pdo_fetchall("select * from ".tablename(WL.'resume').$wheresql.$limit);
        $arr = "";
        foreach ($resumes as $resume){
           if($data['collect']){
               $collect = pdo_fetch("select id from ".tablename(WL."collect_resume")." where uid=".$_SESSION['uid']." and puid=".$resume['uid']);
                if($collect){
                    $resume['collect'] = 1;
                }
           }

            $blacklist = explode(",",$resume['blacklist']);
            if($resume['experience']){
                $resume['experience'] = $resume['experience']."年以上工作经验";
            }else{
                $resume['experience'] = "无工作经验";
            }
            if($_SESSION['utype']==2){
               if($this->blacklist($blacklist)){
                   $arr[] = "";
               }else{
                   $arr[] = $resume;
               }
            }else{
                $arr[] = $resume;
            }
        }
        return array_filter($arr);
    }

    /*
     * 黑名单针对当前身份为企业
     */
    public function blacklist($blacklist){
        $company_profile = m('company')->get_profile($_SESSION['uid'],"companyname");
        foreach ($blacklist as $li){
            if(strpos($company_profile['companyname'],$li)!==false){
                return true;
            }
        }
    }


    /*
     * 教育经验及工作经验信息提取
     */
    public function extract_experience_info($resume){
        $data = "";
        if($resume['edu_experience']){
            $edu_experience = unserialize($resume['edu_experience']);
            $education = "";
            $id = "";
            $edu = array('专科以下','专科','本科','硕士','博士','博士以上');
            $arr_edu = array_flip($edu);
            foreach ($edu_experience as $key=>$list){
                $value = $list['edu_district'];
                if(empty($education)){
                    $education = $arr_edu[$value];
                    $id = $key;
                }else{
                    if($arr_edu[$value]>$education){
                        $education = $arr_edu[$value];
                        $id = $key;
                    }
                }
            }
            $data['education'] = $edu[$education];
            $data['major'] = $edu_experience[$id]['edu_major'];
            $data['school_name'] = $edu_experience[$id]['school_name'];
        }

        if($resume['work_experience']){
            $work_experience = unserialize($resume['work_experience']);
//            var_dump($work_experience);exit();
            $work_time = "";
            foreach ($work_experience as $list){
            $list['job_starttime'] = str_replace("月","",str_replace("年","-",$list['job_starttime']));
            $list['job_starttime'] = strtotime($list['job_starttime']);
            $list['job_endtime'] = str_replace("月","",str_replace("年","-",$list['job_endtime']));
            $list['job_endtime'] = strtotime($list['job_endtime']);
//            if(empty($work_time)){
//                $work_time = $time;
//            }else{
//                if($time<$work_time){
//                    $work_time = $time;
//                }
//            }
                $work_time =$work_time+($list['job_endtime'] - $list['job_starttime']);
            }
            if($work_time){
                $data['experience'] =intval($work_time/31536000)?intval($work_time/31536000):1;
                $data['experience'] =  $data['experience'];
            }else{
                $data['experience'] = 0;
            }
        }

        return $data;

    }

}
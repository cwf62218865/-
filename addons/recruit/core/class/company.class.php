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

    public function get_profile($uid){
        $company = pdo_fetch("select * from ".tablename(WL.'company_profile')." where uid=".$uid);
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
     * status 1表示收到的简历，2表示面试邀请的简历
     */
    public function getall_resume($uid,$page=0,$status=1,$jobs_id=""){
        $wheresql = " where 1=1 and uid=".$uid;

        if($status==1){
            $wheresql .=" and status<>3";
        }elseif ($status==2){
            $wheresql .=" and status=3";
        }

        if($jobs_id){
            $wheresql .=" and jobs_id=".$jobs_id;
        }
        $limit = " limit ".($page*6).",6";
//        echo "select id,resume_id,jobs_id,status from ".tablename(WL.'jobs_apply').$wheresql;exit();
        $resume_jobs = pdo_fetchall("select id,resume_id,jobs_id,uid,status from ".tablename(WL.'jobs_apply').$wheresql.$limit);
//        $count = pdo_fetchcolumn("select COUNT(*) from ".tablename(WL.'jobs_apply').$wheresql);

        $resumes = "";
        foreach ($resume_jobs as $list){
            $resume = pdo_fetch("select * from ".tablename(WL.'resume')." where id=".$list['resume_id']);
            $job = pdo_fetch("select jobs_name from ".tablename(WL.'jobs')." where id=".$list['jobs_id']);
            $resume['collect_resume'] = pdo_fetch("select id from ".tablename(WL.'collect_resume')." where uid=".$list['uid']." and resume_id=".$list['resume_id']);

            $resume['jobs_name'] = $job['jobs_name'];
            $resume['apply_id'] = $list['id'];
            $resume['status'] = $list['status'];
            if($list['status']==3){
                $interview = pdo_fetch("select * from ".tablename(WL.'interview')." where apply_id=".$list['id']);
                $resume['linker'] = $interview['linker'];
                $resume['link_phone'] = $interview['link_phone'];
                $resume['interview_time'] = $interview['interview_time'];
            }
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
            $resume['education'] = $edu[$education];
            $resume['school_name'] = $edu_experience[$id]['school_name'];
            $resume['edu_major'] = $edu_experience[$id]['edu_major'];

            $work_experience = unserialize($resume['work_experience']);
            $work_time = "";
            foreach ($work_experience as $list){
                $list['job_starttime'] = str_replace("月","",str_replace("年","-",$list['job_starttime']));
                $time = strtotime($list['job_starttime']);
                if(empty($work_time)){
                    $work_time = $time;
                }else{
                    if($time<$work_time){
                        $work_time = $time;
                    }
                }
            }
            $resume['work_time'] =  date('Y')-date('Y',$work_time);
            $resumes[] = $resume;
        }

        return $resumes;
    }


    /*
     * 我收藏的简历
     */
    public function  getall_collect($uid,$page=0,$resume_id=""){
        $wheresql = " where 1=1 and uid=".$uid;
        if($resume_id){
            $wheresql .=" and resume_id=".$resume_id;
        }
        $limit = " limit ".$page.",4";
        $resume_jobs = pdo_fetchall("select * from ".tablename(WL.'collect_resume').$wheresql.$limit);
        $resumes = "";
        foreach ($resume_jobs as $list){
            $resume = pdo_fetch("select * from ".tablename(WL.'resume')." where id=".$list['resume_id']);
            $job = pdo_fetch("select jobs_name from ".tablename(WL.'jobs')." where id=".$list['jobs_id']);
            $resume['jobs_name'] = $job['jobs_name'];
            $resume['remark'] = $list['remark'];
            $resume['collect_id'] = $list['id'];
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
            $resume['education'] = $edu[$education];
            $resume['school_name'] = $edu_experience[$id]['school_name'];
            $resume['edu_major'] = $edu_experience[$id]['edu_major'];

            $work_experience = unserialize($resume['work_experience']);
            $work_time = "";
            foreach ($work_experience as $list){
                $list['job_starttime'] = str_replace("月","",str_replace("年","-",$list['job_starttime']));
                $time = strtotime($list['job_starttime']);
                if(empty($work_time)){
                    $work_time = $time;
                }else{
                    if($time<$work_time){
                        $work_time = $time;
                    }
                }
            }
            $resume['work_time'] =  date('Y')-date('Y',$work_time);
            $resumes[] = $resume;
        }
        return $resumes;
    }
    
    
}
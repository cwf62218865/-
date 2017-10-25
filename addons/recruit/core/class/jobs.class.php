<?php

class jobs{

    public function get_jobs($id){
        $jobs = pdo_fetch("select * from ".tablename(WL.'jobs')." where id=".$id);
        return $jobs;
    }
    
    public function getall_jobs($uid){
        $jobs = pdo_fetchall("select * from ".tablename(WL."jobs")." where uid=".$uid." order by open desc");
        $arr = "";
        foreach ($jobs as $list){
            $list['resume_count'] = pdo_fetchcolumn("select COUNT(*) from ".tablename(WL."jobs_apply")." where jobs_id=".$list['id']);
            $arr[] = $list;
        }
//     var_dump($arr);exit();
        return $arr;
    }

    //职位分页
    public function getall_jobs_page($data=""){
        if(empty($data['data']['page'])){
            $data['data']['page']=1;
        }
        $page = ($data['data']['page'] -1)*6;
        $wheresql = " where 1=1 ";
        $orderby = " order by open desc";
        if($data['data']['job_nature'] && $data['data']['job_nature']<>"不限"){
            $wheresql .=" and work_nature='".$data['data']['job_nature']."' ";
        }
        if($data['data']['job_address1'] && $data['data']['job_address1']<>"不限"){
            $wheresql .=" and city_area='".$data['data']['job_address1']."' ";
        }
        if($data['data']['job_address'] && $data['data']['job_address']<>"全国"){
            $wheresql .=" and city like '%".$data['data']['job_address']."%' ";
        }
        if($data['data']['job_education'] && $data['data']['job_education']<>"不限"){
            $wheresql .=" and education='".$data['data']['job_education']."' ";
        }
        if($data['data']['job_order'] && $data['data']['job_order']=="最新"){
            $orderby = " order by addtime,open desc";
        }
        if($data['data']['job_name']){
            $wheresql .=" and jobs_name like '%".$data['data']['job_name']."%' ";
        }
        $limit = " limit ".$page.",6";
//        echo "select * from ".tablename(WL."jobs").$wheresql.$orderby.$limit;exit();
        $jobs = pdo_fetchall("select * from ".tablename(WL."jobs").$wheresql.$orderby.$limit);
        $job['count'] = pdo_fetchcolumn("select COUNT(*) from ".tablename(WL."jobs").$wheresql." order by open desc");
        $job['more'] = "";
        foreach ($jobs as $li){
            $jobs_apply = pdo_fetch("select id from ".tablename(WL."jobs_apply")." where jobs_id=".$li['id']." and puid=".$_SESSION['uid']);
            $headimgurl = pdo_fetch("select headimgurl from ".tablename(WL.'company_profile')." where uid=".$li['uid']);
            if($jobs_apply){
                $li['post_status'] = "已投递";
            }else{
                $li['post_status'] = "投递简历";
            }
            $li['headimgurl'] = $headimgurl['headimgurl'];
            $job['more'][] = $li;
        }
//        foreach ($jobs as $list){
//            $company = pdo_fetch("select * from ".tablename(WL."company_profile")." where uid=".$list['uid']);
//            $list['com']
//        }
        return $job;
    }

public function search_jobs(){

    }
}



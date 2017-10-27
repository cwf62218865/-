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
        $orderby = " order by addtime desc,open desc";
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



    //自己的订阅器信息
    public function order_jobs(){
        $order_jobs = pdo_fetch("select * from ".tablename(WL."order_jobs")." where puid=".$_SESSION['uid']);
        return $order_jobs;
    }


    //订阅器职位搜索
    public function show_order_jobs($order_jobs){
        $wheresql = " where 1=1 ";
        if($order_jobs['jobs_name']){
            $wheresql .= " jobs_name like '%".$order_jobs['jobs_name']."%'";
        }

        if($order_jobs['work_place']){
            $wheresql .= " city_area like '%".$order_jobs['work_place']."%'";
        }

        if($order_jobs['wage_range']){
            if($order_jobs['wage_range']=="2k以下"){
                $wheresql .= " wage_max<2";
            }elseif ($order_jobs['wage_range']=="2k-4k"){
                $wheresql .= " wage_max<=4 and wage_min>=2";
            }elseif ($order_jobs['wage_range']=="4k-6k"){
                $wheresql .= " wage_max<=6 and wage_min>=4";
            }elseif ($order_jobs['wage_range']=="6k-10k"){
                $wheresql .= " wage_max<=10 and wage_min>=6";
            }elseif ($order_jobs['wage_range']=="10k以上"){
                $wheresql .= " wage_max>=10";
            }

//            if($order_jobs['trade']){
//
//                $wheresql .= " city_area like '%".$order_jobs['work_place']."%'";
//            }

        }
        $order_jobs_lists = pdo_fetchall("select * from ".tablename(WL."jobs").$wheresql." order by addtime desc");
        return $order_jobs_lists;
    }


    //订阅器时间判断
    public function check_order_jobs($order_times,$differ_time){
        $order_jobs = $this->order_jobs();
        if($order_times=="三天一次"){
            if($differ_time>3){
                return show_order_jobs($order_jobs);
            }
        }elseif ($order_times=="一周一次"){
            if($differ_time>7){
                return show_order_jobs($order_jobs);
            }
        }elseif ($order_times=="一个月一次"){
            if($differ_time>30){
                return show_order_jobs($order_jobs);
            }
        }elseif ($order_times=="半年一次"){
            if($differ_time>182){
                return show_order_jobs($order_jobs);
            }
        }elseif ($order_times=="一年一次"){
            if($differ_time>365){
                return show_order_jobs($order_jobs);
            }
        }
    }
}



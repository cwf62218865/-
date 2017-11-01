<?php

class jobs{

    public function get_jobs($id){
        $jobs = pdo_fetch("select * from ".tablename(WL.'jobs')." where display=1 and id=".$id);
        return $jobs;
    }
    
    public function getall_jobs($uid){
        $jobs = pdo_fetchall("select * from ".tablename(WL."jobs")." where display=1 and uid=".$uid." order by open desc,addtime desc");
        $arr = "";
        foreach ($jobs as $list){
            $list['resume_count'] = pdo_fetchcolumn("select COUNT(*) from ".tablename(WL."jobs_apply")." where jobs_id=".$list['id']);
            $arr[] = $list;
        }
//     var_dump($arr);exit();
        return $arr;
    }

    //职位分页
    public function getall_jobs_page($data="",$pagenum=6){
        if(empty($data['data']['page'])){
            $data['data']['page']=1;
        }
        $page = ($data['data']['page'] -1)*$pagenum;
        $wheresql = " where  display=1 and open=1 ";
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
        if($_SESSION['city'] && $_SESSION['city']<>"全国"){
            $wheresql .= " and city like '%".$_SESSION['city']."%' ";
        }
        $limit = " limit ".$page.",".$pagenum;
//        echo "select * from ".tablename(WL."jobs").$wheresql.$orderby.$limit;exit();
        $jobs = pdo_fetchall("select * from ".tablename(WL."jobs").$wheresql.$orderby.$limit);
        $job['count'] = pdo_fetchcolumn("select COUNT(*) from ".tablename(WL."jobs").$wheresql." order by open desc");
        $job['more'] = "";
        foreach ($jobs as $li){
            $jobs_apply = pdo_fetch("select id from ".tablename(WL."jobs_apply")." where jobs_id=".$li['id']." and puid=".$_SESSION['uid']);
            $headimgurl = pdo_fetch("select headimgurl from ".tablename(WL.'company_profile')." where uid=".$li['uid']);
            $li['collect_count'] = pdo_fetchcolumn("select count(*) from ".tablename(WL."collect_jobs")." where jobs_id=".$li['id']);
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


    public function order_jobs_list($order_jobs_lists){
//        var_dump($order_jobs_lists);exit();
        $order_jobs_list = "";
        foreach ($order_jobs_lists as $list){
            $company_profile = pdo_fetch("select * from ".tablename(WL."company_profile")." where uid=".$list['uid']);
            $list['headimgurl'] = $company_profile['headimgurl'];
            $list['companyname'] = $company_profile['companyname'];
            $list['tag'] = $company_profile['tag'];
            $list['address'] = $company_profile['city'].$company_profile['city_erea'].$company_profile['district'];
            $jobs_apply = pdo_fetch("select id from ".tablename(WL."jobs_apply")." where puid=".$_SESSION['uid']." and jobs_id=".$list['id']);
            $is_collect = pdo_fetch("select id from ".tablename(WL."collect_jobs")." where uid=".$_SESSION['uid']." and jobs_id=".$list['id']);
            if(empty($jobs_apply)){
                $list['post_status'] = 1;
            }
            if(empty($is_collect)){
                $list['is_collect'] = 1;
            }
            $order_jobs_list[] = $list;
        }
        return $order_jobs_list;
    }

    //订阅器职位搜索
    public function show_order_jobs($order_jobs,$parse=""){
        $wheresql = " where display=1 and open=1 ";
        if($order_jobs['jobs_name']){
            $wheresql .= " and jobs_name like '%".$order_jobs['jobs_name']."%'";
        }

        if($order_jobs['work_place']){
            $wheresql .= " and city_area like '%".$order_jobs['work_place']."%'";
        }

        if($order_jobs['wage_range']){
            if($order_jobs['wage_range']=="2k以下"){
                $wheresql .= " and wage_max<2";
            }elseif ($order_jobs['wage_range']=="2k-4k"){
                $wheresql .= " and wage_max<=4 and wage_min>=2";
            }elseif ($order_jobs['wage_range']=="4k-6k"){
                $wheresql .= " and wage_max<=6 and wage_min>=4";
            }elseif ($order_jobs['wage_range']=="6k-10k"){
                $wheresql .= " and wage_max<=10 and wage_min>=6";
            }elseif ($order_jobs['wage_range']=="10k以上"){
                $wheresql .= " and wage_max>=10";
            }

            if($order_jobs['trade']){

                $wheresql .= " city_area like '%".$order_jobs['work_place']."%'";
            }

        }
        if(empty($parse)){
            $parse = "*";
        }

        $order_jobs_lists = pdo_fetchall("select ".$parse." from ".tablename(WL."jobs").$wheresql." order by addtime desc");
        return $order_jobs_lists;
    }


    //订阅器时间判断
    public function check_order_jobs($order_times,$differ_time){

        $order_jobs = $this->order_jobs();
        if($order_times=="三天一次" && $differ_time>3){
            return $this->refresh_jobs($order_jobs);
        }elseif ($order_times=="一周一次" && $differ_time>7){
            return $this->refresh_jobs($order_jobs);
        }elseif ($order_times=="一个月一次" && $differ_time>30){
            return $this->refresh_jobs($order_jobs);
        }elseif ($order_times=="半年一次" && $differ_time>182){
            return $this->refresh_jobs($order_jobs);
        }elseif ($order_times=="一年一次" && $differ_time>365){
            return $this->refresh_jobs($order_jobs);
        }else{
//            return $this->refresh_jobs($order_jobs);
            $order_jobs_lists = pdo_fetchall("select * from ".tablename(WL."jobs")." where id in(".$order_jobs['order_jobs_ids'].") order by addtime desc");
            return $this->order_jobs_list($order_jobs_lists);
        }
    }


    public function refresh_jobs($order_jobs){
        $old_order_jobs_count = count(explode(",",$order_jobs['order_jobs_ids']));
        $order_jobs_lists = $this->show_order_jobs($order_jobs);
        $order_jobs_ids = $this->order_jobs_list($order_jobs_lists);
        $new_order_jobs_count = count($order_jobs_ids);
        $data['add_order_num'] = $new_order_jobs_count-$old_order_jobs_count;
        $data['order_jobs_ids'] = "";
        foreach ($order_jobs_ids as $list){
            $data['order_jobs_ids'] .=$list['id'].",";
        }
        $data['order_jobs_ids'] = substr($data['order_jobs_ids'],0,-1);
        $data['updatetime'] = time();
        pdo_update(WL."order_jobs",$data,array('puid'=>$_SESSION['uid']));
        return $order_jobs_ids;
    }

    //职位简历关联信息查询
    public function apply_jobs($id){
        $jobs_apply = pdo_fetch("select * from ".tablename(WL."jobs_apply")." where id=".$id);
        return $jobs_apply;
    }
}



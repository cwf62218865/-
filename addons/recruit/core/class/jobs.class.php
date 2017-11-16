<?php

class jobs{

    public function get_jobs($id,$field=""){
        if($field){
            $jobs = pdo_fetch("select ".$field." from ".tablename(WL.'jobs')." where id=".$id);
            return $jobs[$field];
        }else{
            $jobs = pdo_fetch("select * from ".tablename(WL.'jobs')." where display=1 and id=".$id);
            return $jobs;
        }
    }


    public function getall_jobs($uid,$open=1){
        if($open==1){
            $wheresql = " where open=1 ";
        }else{
            $wheresql = " where 1=1 ";
        }
        $jobs = pdo_fetchall("select * from ".tablename(WL."jobs").$wheresql." and uid=".$uid." order by open desc,addtime desc");
        $arr = "";
        foreach ($jobs as $list){
            $list['resume_count'] = pdo_fetchcolumn("select COUNT(*) from ".tablename(WL."jobs_apply")." where direction=2 and jobs_id=".$list['id']);
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
        $wheresql = " where  display=1 and open=1";
        $orderby = " order by id asc,open desc";
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
        if($data['data']['job_order'] && $data['data']['job_order']=="默认排序"){
            $orderby = " order by id asc,open desc";
        }
        if($data['data']['job_order'] && $data['data']['job_order']=="发布时间"){
            $orderby = " order by addtime desc,open desc";
        }

        if($data['data']['job_order'] && $data['data']['job_order']=="关注度"){
            $orderby = " order by collect_num desc,open desc";
        }

        if($data['data']['job_name']){
            $wheresql .=" and jobs_name like '%".$data['data']['job_name']."%' ";
        }
        if($_SESSION['city'] && $_SESSION['city']<>"全国"){
            $wheresql .= " and city like '%".$_SESSION['city']."%' ";
        }
        if($data['data']['guess']){
            $orderby = " order by rand() ";
        }
        if($data['data']['uid']){
            $wheresql .=" and uid=".$data['data']['uid'];
        }

        $limit = " limit ".$page.",".$pagenum;
//        echo "select * from ".tablename(WL."jobs").$wheresql.$orderby.$limit;exit();
        $jobs = pdo_fetchall("select * from ".tablename(WL."jobs").$wheresql.$orderby.$limit);
//        var_dump($jobs);exit();
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
            $wheresql .= " and (city_area like '%".$order_jobs['work_place']."%' or city like '%".$order_jobs['work_place']."%') ";
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

    /*
     * 评论面试职位
     *
     */
    public function comment_apply($data,$pagenum=6)
    {
        $wheresql = " where 1=1 ";
        if ($data['uid']) {
            $wheresql .= " and hr_reply='' and uid=" . $data['uid'];
        }
        if ($data['jobs_id']) {
            $wheresql .= " and jobs_id=" . $data['jobs_id'];
        }

        if ($data['company_uid']) {
            $wheresql .= " and uid=" . $data['company_uid'];
        }
        if ($data['puid']) {
            $wheresql .= " and puid=" . $data['puid'];
        }
        if ($data['hr_reply']) {
            $wheresql .= " and hr_reply<>''";
        }
        if(empty($data['page'])){
            $data['page']=1;
        }
        $page = ($data['page'] -1)*$pagenum;
        $limit = " limit ".$page.",".$pagenum;
//        echo "select * from " . tablename(WL ."comment") . $wheresql.$limit;exit();
        $comment_jobs = pdo_fetchall("select * from " . tablename(WL ."comment") . $wheresql.$limit);
//        var_dump($comment_jobs);exit();
        $comment['count'] = pdo_fetchcolumn("select count(*) from " . tablename(WL ."comment") . $wheresql);

        $comment['more'] = "";
        foreach ($comment_jobs as $list) {
            $jobs = pdo_fetch("select id,jobs_name from " . tablename(WL . "jobs") . " where id=" . $list['jobs_id']);
            $company = pdo_fetch("select headimgurl from " . tablename(WL . "company_profile") . " where uid=" . $list['uid']);
            $resume = pdo_fetch("select fullname,headimgurl from " . tablename(WL . "resume") . " where uid=" . $list['puid']);
            $list['jobs_name'] = $jobs['jobs_name'];
            $list['jobs_id'] = $jobs['id'];
            $list['headimgurl'] = $resume['headimgurl'];
            $list['logo'] = $company['headimgurl'];
            if ($list['hide']) {
                $list['fullname'] = "匿名";
            } else {
                $list['fullname'] = $resume['fullname'];
            }
            $list['information_star'] = "";
            for ($i = 0; $i < $list['evaluate_information']; $i++) {
                $list['information_star'] .=
                    "<svg class=\"icon colorffc549\" >
                        <use xlink:href=\"#icon-pingfen\"></use>
                    </svg>";
            }

            for ($i = 0; $i < (5 - $list['evaluate_information']); $i++) {
                $list['information_star'] .=
                    "<svg class=\"icon colorffc549\" >
                        <use xlink:href=\"#icon-pingfenbanfen\"></use>
                    </svg>";
            }

            $list['environment_star'] = "";
            for ($i = 0; $i < $list['evaluate_environment']; $i++) {
                $list['environment_star'] .=
                    "<svg class=\"icon colorffc549\" >
                        <use xlink:href=\"#icon-pingfen\"></use>
                    </svg>";
            }

            for ($i = 0; $i < (5 - $list['evaluate_environment']); $i++) {
                $list['environment_star'] .=
                    "<svg class=\"icon colorffc549\" >
                        <use xlink:href=\"#icon-pingfenbanfen\"></use>
                    </svg>";
            }

            $list['interviewer_star'] = "";
            for ($i = 0; $i < $list['evaluate_interviewer']; $i++) {
                $list['interviewer_star'] .=
                    "<svg class=\"icon colorffc549\" >
                        <use xlink:href=\"#icon-pingfen\"></use>
                    </svg>";
            }

            for ($i = 0; $i < (5 - $list['evaluate_interviewer']); $i++) {
                $list['interviewer_star'] .=
                    "<svg class=\"icon colorffc549\" >
                        <use xlink:href=\"#icon-pingfenbanfen\"></use>
                    </svg>";
            }
            $count_score = ceil(($list['evaluate_interviewer'] + $list['evaluate_environment'] + $list['evaluate_information']) / 3);
            $list['count_score'] = "";
            for ($i = 0; $i < $count_score; $i++) {
                $list['count_score'] .=
                    " <svg class=\"icon star\" aria-hidden=\"true\">
                                <use  xlink:href=\"#icon-pingfen\"></use>
                                </svg>";
            }

            for ($i = 0; $i < (5 - $count_score); $i++) {
                $list['count_score'] .=
                    " <svg class=\"icon star\" aria-hidden=\"true\">
                                <use  xlink:href=\"#icon-pingfenbanfen\"></use>
                                </svg>";
            }
            $list['score'] = $count_score;
            $comment['more'][] = $list;
        }
        return $comment;
    }


    /*
     * 面试评价总体评分计算
     */
    public function comment_count($company_uid){
        $comment_jobs = pdo_fetchall("select * from ".tablename(WL."comment")." where uid=".$company_uid);
        $count = pdo_fetchcolumn("select COUNT(*) from ".tablename(WL."comment")." where uid=".$company_uid);
        $data['comment_count'] = $count;
        $information_count = "";
        $environment_count = "";
        $interviewer_count = "";
        foreach ($comment_jobs as $list){
            $information_count +=$list['evaluate_information'];
            $environment_count +=$list['evaluate_environment'];
            $interviewer_count +=$list['evaluate_interviewer'];
        }
        $data['information_count'] = ceil($information_count/$count);
        $data['environment_count'] = ceil($environment_count/$count);
        $data['interviewer_count'] = ceil($interviewer_count/$count);
        $data['count'] = ($data['information_count']+$data['environment_count']+$data['interviewer_count'])/3;
        $data['count'] = sprintf("%.1f", $data['count']);
        $data['information_star'] = "";
        for($i=0;$i<$data['information_count'];$i++){
            $data['information_star'] .=
                "<svg class=\"icon star\" aria-hidden=\"true\">
                                <use  xlink:href=\"#icon-pingfen\"></use>
                                </svg>";
        }

        for($i=0;$i<(5-$data['information_count']);$i++){
            $data['information_star'] .=
                " <svg class=\"icon star\" aria-hidden=\"true\">
                    <use  xlink:href=\"#icon-pingfenbanfen\"></use>
                    </svg>";
        }

        $data['environment_star'] = "";
        for($i=0;$i<$data['environment_count'];$i++){
            $data['environment_star'] .=
                "<svg class=\"icon star\" aria-hidden=\"true\">
                                <use  xlink:href=\"#icon-pingfen\"></use>
                                </svg>";
        }

        for($i=0;$i<(5-$data['environment_count']);$i++){
            $data['environment_star'] .=
                " <svg class=\"icon star\" aria-hidden=\"true\">
                    <use  xlink:href=\"#icon-pingfenbanfen\"></use>
                    </svg>";
        }

        $data['interviewer_star'] = "";
        for($i=0;$i<$data['interviewer_count'];$i++){
            $data['interviewer_star'] .=
                "<svg class=\"icon star\" aria-hidden=\"true\">
                                <use  xlink:href=\"#icon-pingfen\"></use>
                                </svg>";
        }

        for($i=0;$i<(5-$data['interviewer_count']);$i++){
            $data['interviewer_star'] .=
                " <svg class=\"icon star\" aria-hidden=\"true\">
                    <use  xlink:href=\"#icon-pingfenbanfen\"></use>
                    </svg>";
        }

        return $data;
    }
}



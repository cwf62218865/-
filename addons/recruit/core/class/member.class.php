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
        }elseif($last_login_time>30){
            $last_login_time = "一个月前";
        }else{
            $last_login_time = "今天";
        }

        return $last_login_time;
    }


    //公司列表分页
    public function company_list($page=0,$pagenum=8){
        if($_SESSION['city'] && $_SESSION['city']<>"全国"){
            $wheresql = " where companyname <> '' and headimgurl<>'' and city like '%".$_SESSION['city']."%' ";
        }else{
            $wheresql = " where companyname <> '' and headimgurl<>'' ";
        }
        $company_count = pdo_fetchcolumn("select count(*) from ".tablename(WL."company_profile").$wheresql);

        if($page*$pagenum>$company_count){
            $page = 0;
        }
        $limit = " limit ".$page*$pagenum.",".$pagenum;

        $company = pdo_fetchall("select * from ".tablename(WL."company_profile").$wheresql." order by id desc ".$limit);
        $company_profile = "";
        foreach ($company as $key=>$list){
            $company_profile[$key] = $list;
            $company_profile[$key]['last_login'] = $this->last_login($list['uid']);
            $company_profile[$key]['jobs_count'] = pdo_fetchcolumn("select count(*) from ".tablename(WL."jobs")." where open=1 and display=1 and uid=".$list['uid']);
            $company_profile[$key]['comment_count'] = pdo_fetchcolumn("select count(*) from ".tablename(WL."comment")." where uid=".$list['uid']);

        }
        return $company_profile;
    }


    /*
     * 评论新闻
     */
    public function get_news_comment($id,$page=0){
        $limit = " limit ".($page*4).",4";
        $comment = pdo_fetchall("select * from ".tablename(WL."evaluate")." where news_id=".$id.$limit);
        $comments ="";
        foreach ($comment as $list){
            if($list['utype']==1){
                $resume = pdo_fetch("select fullname,headimgurl from ".tablename(WL."resume")." where uid=".$list['uid']);
                $list['headimgurl'] = $resume['headimgurl'];
                $list['fullname'] = $resume['fullname'];
            }elseif ($list['utype']==2){
                $company = pdo_fetch("select companyname,headimgurl from ".tablename(WL."company_profile")." where uid=".$list['uid']);
                $list['headimgurl'] = $company['headimgurl'];
                $list['fullname'] = $company['companyname'];
            }

            if($list['comment_id']){
                $pre_comment = pdo_fetch("select uid,utype from ".tablename(WL."evaluate")." where id=".$list['comment_id']);

                if($pre_comment['utype']==1){
                    $resume = pdo_fetch("select fullname from ".tablename(WL."resume")." where uid=".$pre_comment['uid']);
                    $list['pl_user'] = $resume['fullname'];
                }elseif ($pre_comment['utype']==2){
                    $company = pdo_fetch("select companyname from ".tablename(WL."company_profile")." where uid=".$pre_comment['uid']);
                    $list['pl_user'] = $company['companyname'];
                }
            }
            $comments[] = $list;
        }
        return $comments;
    }

    //新闻列表
    public function news_list($page=0,$kind=""){
        $limit = " limit ".($page*6).",6";
        if($kind){
            $wheresql = " where cateid=".$kind;
        }else{
            $wheresql = "";
        }
        $article_news = pdo_fetchall("select * from ".tablename("article_news").$wheresql.$limit);
//        var_dump($article_news);exit();
        return $article_news;
    }

}
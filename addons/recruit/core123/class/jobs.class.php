<?php

class jobs{

    public function get_jobs($id){
        $jobs = pdo_fetch("select * from ".tablename('jobs')." where id=".$id);
        return $jobs;
    }
    

    public function search_jobs(){

    }
}



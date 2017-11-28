<?php
require_once("../../API/qqConnectAPI.php");
$qc = new QC();
$qc->qq_callback();
$openid = $qc->get_openid();
header("location:/app/index.php?c=site&a=entry&m=recruit&do=member&ac=index&op=qq_callback&qq_openid=".$openid);exit();





<?php
require_once("../../API/qqConnectAPI.php");
$qc = new QC();
$qc->qq_callback();
$openid = $qc->get_openid();

echo $openid;




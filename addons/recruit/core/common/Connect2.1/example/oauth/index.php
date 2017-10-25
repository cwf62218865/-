<?php
session_start();
require_once("../../API/qqConnectAPI.php");
$qc = new QC();
$qc->qq_login();

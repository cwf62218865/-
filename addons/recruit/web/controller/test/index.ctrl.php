<?php
defined('IN_IA') or exit('Access Denied');
set_time_limit(0);
if (!$_W['isfounder']) {
	message('无权访问!');
}
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
load()->func('file');
wl_load()->model('auth');
require WL_CORE .'common/version.php';







echo get_hash_table('code',651);exit();
//for($i=1;$i<=256;$i++){
//    $sql = "DROP TABLE IF EXISTS `qs_new_resume_{$i}`";
//   if($db->query($sql)){
//       echo "ok";
//   }else{
//       echo "no";
//   }
//}exit();


for($i=1;$i<=256;$i++){
    $sql="CREATE TABLE IF NOT EXISTS `qs_new_resume_{$i}` (
   `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `display` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `audit` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `title` varchar(80) NOT NULL,
  `fullname` varchar(15) NOT NULL,
  `sex` tinyint(3) unsigned NOT NULL,
  `sex_cn` varchar(3) NOT NULL,
  `trade` varchar(60) NOT NULL,
  `trade_cn` varchar(100) NOT NULL,
  `birthdate` smallint(4) unsigned NOT NULL,
  `marriage` tinyint(3) unsigned NOT NULL,
  `marriage_cn` varchar(5) NOT NULL,
  `experience` smallint(5) unsigned NOT NULL,
  `experience_cn` varchar(30) NOT NULL,
  `district` varchar(60) NOT NULL DEFAULT '',
  `district_cn` varchar(100) NOT NULL DEFAULT '',
  `wage_hope` int(10) unsigned NOT NULL,
  `wage_hope_number` int(10) unsigned NOT NULL,
  `wage_hope_month` smallint(2) unsigned NOT NULL,
  `wage_current` int(10) unsigned NOT NULL,
  `wage_current_number` int(10) unsigned NOT NULL,
  `wage_current_month` smallint(2) unsigned NOT NULL,
  `secret` tinyint(1) unsigned NOT NULL,
  `residence` varchar(80) NOT NULL DEFAULT '',
  `residence_cn` varchar(50) NOT NULL DEFAULT '',
  `education` smallint(5) unsigned NOT NULL,
  `education_cn` varchar(30) NOT NULL DEFAULT '',
  `current` tinyint(5) unsigned NOT NULL,
  `current_cn` varbinary(50) NOT NULL,
  `tag` varchar(160) NOT NULL,
  `telephone` varchar(50) NOT NULL,
  `email` varchar(60) NOT NULL DEFAULT '',
  `currentjobs` varchar(200) NOT NULL DEFAULT '',
  `intention_jobs` varchar(60) NOT NULL DEFAULT '',
  `intention_jobs_cn` varchar(100) NOT NULL,
  `evaluation` text NOT NULL,
  `photo_img` varchar(80) NOT NULL,
  `photo_audit` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `addtime` int(10) unsigned NOT NULL,
  `refreshtime` int(10) unsigned NOT NULL,
  `talent` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `complete_percent` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `user_status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `key` text NOT NULL,
  `click` int(10) unsigned NOT NULL DEFAULT '1',
  `tpl` varchar(60) NOT NULL,
  `native_place` varchar(100) NOT NULL COMMENT '籍贯',
  `work_experience` text NOT NULL,
  `edu_experience` text NOT NULL,
  `object_experience` text NOT NULL,
  `language_ablity` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `refreshtime` (`refreshtime`),
  KEY `addtime` (`addtime`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8";
    pdo_query($sql);
}
//
exit();
//$data['username'] = "包崇林badadaasda";
//$data['email'] = "1421514791@qq.com";
//$data['mobile'] = "15023726868";
//$data['sex'] = "男";
//$id = inserttable(table('qs_members'),$data,true);
//$table_name=get_hash_table('code',$full_code);
function get_hash_table($table,$code,$s=256){
    $hash = sprintf("%u", crc32($code));
    $hash1 = intval(fmod($hash, $s));
    return $table."_".$hash1;
}
//
//$resume['id'] = $id;
//$resume['fullname'] = "包崇林adq";
//$resume['title'] = "标题";
//$resume['trade'] = "行业";
//
//$table = get_hash_table('qs_resume',$id);
//
//echo inserttable(table($table),$resume,true);exit();

$str = "";
for($i=1;$i<=256;$i++){
    $str[] = "qs_new_resume_".$i;
}
$str = implode(",",$str);
//
$sql = "CREATE TABLE IF NOT EXISTS `qs_new_resume` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `display` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `audit` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `title` varchar(80) NOT NULL,
  `fullname` varchar(15) NOT NULL,
  `sex` tinyint(3) unsigned NOT NULL,
  `sex_cn` varchar(3) NOT NULL,
  `trade` varchar(60) NOT NULL,
  `trade_cn` varchar(100) NOT NULL,
  `birthdate` smallint(4) unsigned NOT NULL,
  `marriage` tinyint(3) unsigned NOT NULL,
  `marriage_cn` varchar(5) NOT NULL,
  `experience` smallint(5) unsigned NOT NULL,
  `experience_cn` varchar(30) NOT NULL,
  `district` varchar(60) NOT NULL DEFAULT '',
  `district_cn` varchar(100) NOT NULL DEFAULT '',
  `wage_hope` int(10) unsigned NOT NULL,
  `wage_hope_number` int(10) unsigned NOT NULL,
  `wage_hope_month` smallint(2) unsigned NOT NULL,
  `wage_current` int(10) unsigned NOT NULL,
  `wage_current_number` int(10) unsigned NOT NULL,
  `wage_current_month` smallint(2) unsigned NOT NULL,
  `secret` tinyint(1) unsigned NOT NULL,
  `residence` varchar(80) NOT NULL DEFAULT '',
  `residence_cn` varchar(50) NOT NULL DEFAULT '',
  `education` smallint(5) unsigned NOT NULL,
  `education_cn` varchar(30) NOT NULL DEFAULT '',
  `current` tinyint(5) unsigned NOT NULL,
  `current_cn` varbinary(50) NOT NULL,
  `tag` varchar(160) NOT NULL,
  `telephone` varchar(50) NOT NULL,
  `email` varchar(60) NOT NULL DEFAULT '',
  `currentjobs` varchar(200) NOT NULL DEFAULT '',
  `intention_jobs` varchar(60) NOT NULL DEFAULT '',
  `intention_jobs_cn` varchar(100) NOT NULL,
  `evaluation` text NOT NULL,
  `photo_img` varchar(80) NOT NULL,
  `photo_audit` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `addtime` int(10) unsigned NOT NULL,
  `refreshtime` int(10) unsigned NOT NULL,
  `talent` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `complete_percent` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `user_status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `key` text NOT NULL,
  `click` int(10) unsigned NOT NULL DEFAULT '1',
  `tpl` varchar(60) NOT NULL,
  `native_place` varchar(100) NOT NULL COMMENT '籍贯',
  `work_experience` text NOT NULL,
  `edu_experience` text NOT NULL,
  `object_experience` text NOT NULL,
  `language_ablity` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `refreshtime` (`refreshtime`),
  KEY `addtime` (`addtime`)
) ENGINE=MRG_MyISAM DEFAULT CHARSET=utf8 UNION=({$str});
";
if($db->query($sql)){
    echo "ok";
}else{
    echo "no";
};exit();
header("Content-type: text/html; charset=utf-8");
$sphinx = new SphinxClient();
$sphinx->setServer("localhost", 9312);
$sphinx->setMatchMode(SPH_MATCH_ANY);
$sphinx->setMaxQueryTime(100);

$result = $sphinx->query("adq","*");
foreach ($result['matches'] as $key=>$value){

    echo $key."<br/>";
}
//     echo "<pre>";
//     var_dump($result['matches']);
//     echo "</pre>";

?>



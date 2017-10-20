<?php 
defined('IN_IA') or exit('Access Denied');
$ops = array('list');
$op = in_array($op, $ops) ? $op : 'list';

if ($op == 'list') {
	include wl_template('app123/plugins_list');
}

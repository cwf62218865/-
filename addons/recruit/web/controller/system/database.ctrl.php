<?php
$ops = array('optimize', 'run');
$do = in_array($op, $ops) ? $do : 'run';

if($op == 'run') {
	if(checksubmit()) {
		$sql = $_POST['sql'];
		pdo_run($sql);
		message('查询执行成功.', 'refresh');
	}
}
include wl_template('system/database');
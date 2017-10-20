<?php


require IA_ROOT . "/addons/recruit/core/common/defines.php";
require WL_CORE . "class/wlloader.class.php";
wl_load()->func("global");
class Weliam_shiftcarModule extends WeModule
{
	public function welcomeDisplay()
	{
		header('location: ' . web_url('dashboard/index'));
		exit();
	}
}

function getTopDomainhuo()
{
	$host = $_SERVER['HTTP_HOST'];
	$host = strtolower($host);

	if (strpos($host, '/') !== false) {
		$parse = @parse_url($host);
		$host = $parse['host'];
	}


	$topleveldomaindb = array('com', 'edu', 'gov', 'int', 'mil', 'net', 'org', 'biz', 'info', 'top', 'wang', 'pro', 'name', 'so', 'in', 'museum', 'coop', 'aero', 'xxx', 'idv', 'mobi', 'cc', 'me', 'co', 'asia');
	$str = '';

	foreach ($topleveldomaindb as $v ) {
		$str .= (($str ? '|' : '')) . $v;
	}

	$matchstr = '[^\\.]+\\.(?:(' . $str . ')|\\w{2}|((' . $str . ')\\.\\w{2}))$';

	if (preg_match('/' . $matchstr . '/ies', $host, $matchs)) {
		$domain = $matchs[0];
	}
	 else {
		$domain = $host;
	}

	return $domain;
}


?>
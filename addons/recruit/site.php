<?php

//bbs.折翼天使资源社区 独家解密
defined('IN_IA') || die('Access Denied');
session_start();

class recruitModuleSite extends WeModuleSite
{
	public function __call($name, $arguments)
	{
		global $_W;
		global $_GPC;
		require IA_ROOT . '/addons/recruit/core/common/defines.php';
		require WL_CORE . 'class/wlloader.class.php';
		wl_load()->func('global');
		wl_load()->func('pdo');
		$isWeb = stripos($name, 'doWeb') === 0;
		$isMobile = stripos($name, 'doMobile') === 0;
		$isAdmin = stripos($name, 'doAdmin') === 0;
		if ($isWeb || $isMobile || $isAdmin) {
			$dir = IA_ROOT . '/addons/' . $this->modulename . '/';
			if ($isWeb) {
				$dir .= 'web/';
				$controller = strtolower(substr($name, 5));
				$tmpdir = IA_ROOT . '/addons/recruit/core/common';
				$f = file_get_contents($tmpdir . '/common.log');
				$commonlog = json_decode($f, true);
			}
			if ($isMobile) {
				$dir .= 'app/';
				$controller = strtolower(substr($name, 8));
			}

			if ($isAdmin) {
				$dir .= 'admin/';
				$controller = strtolower(substr($name, 7));
			}
			$file = $dir . 'index.php';
			if (file_exists($file)) {
				require $file;
				die;
			}
		}
		trigger_error('访问的方法 ' . $name . ' 不存在.', 512);
	}

}
<?php

/***************************************************************************
 *
 * Copyright (c) 2012 Baidu.com, Inc. All Rights Reserved
 *
 **************************************************************************/

require_once(WL_CORE.'/common/baidu-sdk/Baidu.php');

$clientId = 'IjjFj71Sa4S5m28RpxsMLbXm';
$clientSecret = 'jnuDfLt4EhsMIxQGKfHG2GXDRXGLwc96';
$redirectUri = 'http://ios.huiliewang.com/app/index.php?c=site&a=entry&m=recruit&do=member&ac=index&op=baidu_callback&';
$domain = '.robin928.sinaapp.com';

$baidu = new Baidu($clientId, $clientSecret, $redirectUri, new BaiduCookieStore($clientId));
// Get User ID and User Name
$user = $baidu->getLoggedInUser();

 
/* vim: set expandtab ts=4 sw=4 sts=4 tw=100: */
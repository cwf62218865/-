<?php

define('IN_QISHI', true);
include WL_CORE.'common/msg_sdk/aliyun-php-sdk-core/Config.php';
include_once WL_CORE.'common/api_sdk/Dysmsapi/Request/V20170525/SendSmsRequest.php';
include_once WL_CORE.'common/api_sdk/Dysmsapi/Request/V20170525/QuerySendDetailsRequest.php';

function sendSms($phone,$code) {
    
    //此处需要替换成自己的AK信息
    $accessKeyId = "LTAICZ7goroUPoYh";
    $accessKeySecret = "qXfaSjVazvj0WSn5nQS92LU5M2uzGZ";
    //短信API产品名
    $product = "Dysmsapi";
    //短信API产品域名
    $domain = "dysmsapi.aliyuncs.com";
    //暂时不支持多Region
    $region = "cn-hangzhou";

    //初始化访问的acsCleint
    $profile = DefaultProfile::getProfile($region, $accessKeyId, $accessKeySecret);
    DefaultProfile::addEndpoint("cn-hangzhou", "cn-hangzhou", $product, $domain);
    $acsClient= new DefaultAcsClient($profile);
    
    $request = new Dysmsapi\Request\V20170525\SendSmsRequest;
    //必填-短信接收号码
    $request->setPhoneNumbers($phone);
    //必填-短信签名
    $request->setSignName("慧猎网");
    //必填-短信模板Code
    $request->setTemplateCode("SMS_74215036");
    //选填-假如模板中存在变量需要替换则为必填(JSON格式)
    $request->setTemplateParam("{\"code\":\"$code\",\"product\":\"\"}");
    //选填-发送短信流水号
    $request->setOutId("1234");
    
    //发起访问请求
    $acsResponse = $acsClient->getAcsResponse($request);
	
    //var_dump($acsResponse);
	return true;
}

function querySendDetails() {
    
    //此处需要替换成自己的AK信息
    $accessKeyId = "LTAICZ7goroUPoYh";
    $accessKeySecret = "qXfaSjVazvj0WSn5nQS92LU5M2uzGZ";
    //短信API产品名
    $product = "";
    //短信API产品域名
    $domain = "";
    //暂时不支持多Region
    $region = "cn-hangzhou";
    
    //初始化访问的acsCleint
    $profile = DefaultProfile::getProfile($region, $accessKeyId, $accessKeySecret);
    DefaultProfile::addEndpoint("cn-hangzhou", "cn-hangzhou", $product, $domain);
    $acsClient= new DefaultAcsClient($profile);
    
    $request = new Dysmsapi\Request\V20170525\QuerySendDetailsRequest();
    //必填-短信接收号码
    $request->setPhoneNumber("");
    //选填-短信发送流水号
    $request->setBizId("abcdefgh");
    //必填-短信发送日期，支持近30天记录查询，格式yyyyMMdd
    $request->setSendDate("20170628");
    //必填-分页大小
    $request->setCurrentPage(1);
    $request->setPageSize(10);
    //必填-当前页码
    $request->setContent(1);
    
    //发起访问请求

    $acsResponse = $acsClient->getAcsResponse($request);
    //var_dump($acsResponse);
	echo "ok";
    
}




?>
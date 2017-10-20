<?php
 /*
 * huiliewang 验证码
 * ============================================================================
 * 版权所有: 慧猎网，并保留所有权利。
 * 网站地址: http://www.huiliewang.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
*/
header('Expires: Thu, 01 Jan 1970 00:00:00 GMT');
// HTTP/1.1
header('Cache-Control: private, no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0, max-age=0', false);
// HTTP/1.0
header('Pragma: no-cache');
session_start();
error_reporting(E_ERROR);
class imageCaptcha
{
private $height;
private $width;
private $textNum; 
private $textContent;
private $fontColor;
private $randFontColor; 
private $fontSize;
private $bgColor;
private $randBgColor;
private $textLang;
private $noisePoint;
private $noiseLine;
private $distortion;
private $distortionImage;
private $showBorder;
private $image;
private $rootpath;
public function imageCaptcha()
{
	$this->textNum = 4;
	$this->fontSize = 15;
	$this->fontFamily = '';
	$this->textLang = 'en';
	$this->noisePoint = 100;
	$this->noiseLine = 0;
	$this->distortion = false;
	$this->showBorder = false;
	$this->data  = array (
		'verify_userreg' => '0',
		'verify_userlogin' => '1',
		'verify_getpwd' => '0',
		'verify_link' => '1',
		'verify_adminlogin' => '0',
		'captcha_width' => '150',
		'captcha_height' => '40',
		'captcha_textcolor' => '',
		'captcha_textfontsize' => '25',
		'captcha_textlength' => '4',
		'captcha_lang' => 'en',
		'captcha_bgcolor' => '',
		'captcha_noisepoint' => '0',
		'captcha_noiseline' => '5',
		'captcha_distortion' => '0',
	);
	$this->rootpath= WL_CORE;
}
public function set_show_mode()
{
	$this->cfg=$this->data;
	$this->width=$this->cfg['captcha_width'];
	$this->height=$this->cfg['captcha_height'];
	$this->textNum=empty($this->cfg['captcha_textlength'])?mt_rand(3,6):$this->cfg['captcha_textlength'];
	$this->fontColor=$this->cfg['captcha_textcolor']?sscanf($this->cfg['captcha_textcolor'],'#%2x%2x%2x'):'';
	$this->fontSize=$this->cfg['captcha_textfontsize'];
	$this->textLang=$this->cfg['captcha_lang'];
	$this->bgColor=$this->cfg['captcha_bgcolor']?sscanf($this->cfg['captcha_bgcolor'],'#%2x%2x%2x'):'';
	$this->noisePoint=$this->cfg['captcha_noisepoint'];
	$this->noiseLine=$this->cfg['captcha_noiseline'];
	$this->distortion=$this->cfg['captcha_distortion'];
}
public function initImage() //@初始化验证码图片
{   
	if(empty($this->width))
	{
	$this->width=floor($this->fontSize*1.3)*$this->textNum+20;
	}
	if(empty($this->height))
	{
	$this->height=floor($this->fontSize*2.3);
	}
	$this->image=imagecreatetruecolor($this->width,$this->height);
	if(empty($this->bgColor))
	{
	$this->randBgColor=imagecolorallocate($this->image,mt_rand(200,255),mt_rand(200,255),mt_rand(200,255));
	}
	else
	{
	$this->randBgColor=imagecolorallocate($this->image,$this->bgColor[0],$this->bgColor[1],$this->bgColor[2]);
	}
	imagefill($this->image,0,0,$this->randBgColor);
}
public function randText($type)//@产生随机字符
{    
	$string='';
	switch($type)
	{
		case 'en':
		//$string='ACDEFGHKLMNPQRSTUVWXYabcdehkmnprsuvwxy3469';
		$string='ABCDEFHKLMNPRTXYabcdehkmnprxy34678';
		$string=str_shuffle($string);
		$string=substr($string,0,$this->textNum);
		$string=chunk_split($string,1,',');
		$string=rtrim($string,',');
		$string=explode(',',$string);
		break;
		case 'cn':
		$string="到,去,请,钱,且,我,为,未,网,问,人,如,让,他,太,条,日,一,有,元,要,也,盘,怕,品,是,上,说,算,时,的,到,都,等,点,发,分,非,丰,否,个,给,刚,过,告,好,和,后,会,话,就,将,加,接,急,看,库,开,可,空,了,来,里,啦,老,在,做,再,中,走,想,下,写,先,新,才,错,次,此,从,不,吧,把,表,你,能,那,呢,吗,慢,忙,么";
		$string=iconv("utf-8","utf-8",$string);
		$string=explode(',',$string);
		shuffle($string);
		$string=array_slice($string,0,$this->textNum);
		break;
	}
	return $string;
}
public function createText()//@输出文字到验证码
{    
	$text_array=$this->randText($this->textLang);
	$this->textContent=implode('',$text_array);
	if ($this->textLang=="cn" && strcasecmp("utf8","utf8")!=0)
	{
	$this->textContent=iconv("utf-8","utf8",$this->textContent);
	}
	if(empty($this->fontColor))
	{
	$this->randFontColor=imagecolorallocate($this->image,mt_rand(0,100),mt_rand(0,100),mt_rand(0,100));
	}
	else
	{
	$this->randFontColor=imagecolorallocate($this->image,$this->fontColor[0],$this->fontColor[1],$this->fontColor[2]);
	}
	$font=$this->getfontFamily();
	if(empty($font)) exit();
	$fontdir =$this->rootpath.'common/font/'.$this->textLang."/";
	for($i=0;$i<$this->textNum;$i++)
	{
		$this->fontFamily=$fontdir.$font[array_rand($font,1)];
		imagettftext($this->image,$this->fontSize,mt_rand(-20,20),$i*$this->fontSize+($this->width/$this->textNum)-floor($this->fontSize/2),floor($this->height/2+$this->fontSize/2),$this->randFontColor,$this->fontFamily,$text_array[$i]);
	}
}
public function createNoisePoint()//@生成干扰点
{    
	for($i=0;$i<$this->noisePoint;$i++)
	{
		//$pointColor=imagecolorallocate($this->image,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));
		imagesetpixel($this->image,mt_rand(0,$this->width),mt_rand(0,$this->height),$this->randFontColor);
	}
}
public function getfontFamily()//获取字体
{    
	$dir =$this->rootpath.'common/font/'.$this->textLang."/";
		if($handle = @opendir($dir))
		{
			$i = 0;
			while(false !== ($file = @readdir($handle)))
			{
				if(strcasecmp(substr($file,-4),'.ttf')===0)
				{
					$list[]= $file;
					$i++;
				}
			}
		}	
	return 	$list;
}
public function createNoiseLine()//@产生干扰线
{    
	for($i=0;$i<$this->noiseLine;$i++)
	{
		//$lineColor=imagecolorallocate($this->image,mt_rand(150,255),mt_rand(150,255),mt_rand(150,255));
		imageline($this->image,0,mt_rand(0,$this->width),$this->width,mt_rand(0,$this->height),$this->randFontColor);
	}
}
public function distortionText()//@扭曲文字
{    
	$this->distortionImage=imagecreatetruecolor($this->width,$this->height);
	imagefill($this->distortionImage,0,0,$this->randBgColor);
	for($x=0;$x<$this->width;$x++)
	{
		for($y=0;$y<$this->height;$y++)
		{
		$rgbColor=imagecolorat($this->image,$x,$y);
		imagesetpixel($this->distortionImage,(int)($x+sin($y/$this->height*2*M_PI-M_PI*0.5)*3),$y,$rgbColor);
		}
	}
	$this->image=$this->distortionImage;
}
public function createImage()//@生成验证码图片
{    
	header('Expires: Thu, 01 Jan 1970 00:00:00 GMT');
	// HTTP/1.1
	header('Cache-Control: private, no-store, no-cache, must-revalidate');
	header('Cache-Control: post-check=0, pre-check=0, max-age=0', false);
	// HTTP/1.0
	header('Pragma: no-cache');
	$this->initImage(); //创建基本图片
	$this->createText(); //输出验证码字符
	$this->createNoisePoint(); //产生干扰点
	$this->createNoiseLine(); //产生干扰线
	if($this->distortion =="1")//扭曲文字
	{
	$this->distortionText();
	}
	if($this->showBorder)//添加边框
	{
	$color = ImageColorAllocate($this->image, $this->showBordercolor[0],$this->showBordercolor[1],$this->showBordercolor[2]);
	imagerectangle($this->image,0,0,$this->width-1,$this->height-1,$color);
	} 
	imagepng($this->image);
	imagedestroy($this->image);
	if($this->distortion !=false)
	{
	imagedestroy($this->distortionImage);
	}
	return $this->textContent;
}
}

//    header("Content-type:image/png");
//    m("imageCaptcha")->set_show_mode();
//    $code = m("imageCaptcha")->createImage();
//    $_SESSION['imageCaptcha_content'] = strtolower($code);



?>
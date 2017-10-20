<?php
require('html2fpdf.php');

$pdf=new HTML2FPDF();
$pdf->AddGBFont('GB','·ÂËÎ_GB2312');
$pdf->AddPage();
$fp = fopen("sample.html","r");
$strContent = fread($fp, filesize("sample.html"));
fclose($fp);
//$strContent = file_get_contents("https://localhost/sample.html");
//echo $strContent;exit();
$pdf->WriteHTML(iconv("UTF-8","GB2312",$strContent));
ob_clean();
$pdf->Output("tmp11aaaa.pdf");

//echo "PDF file is generated successfully!";

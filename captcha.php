<?php
session_start();
function get_rand_image_url()
{
	return "images/capbackimg/img" . rand(0,10) . ".jpg";	
}
$RandStr = md5(microtime());
$RandStr =str_replace('0','',$RandStr );
$RandStr =str_replace('o','',$RandStr );
$ResStr = substr($RandStr,0,5);
$Image =imagecreatefromjpeg(get_rand_image_url());
$Color = imagecolorallocate($Image, 0, 0, 0);//text color-white
imagestring($Image, 5, 15, 3, $ResStr, $Color );
$_SESSION['capkey'] = $ResStr ;
header("Content-type: image/jpeg");
imagejpeg($Image);
?>
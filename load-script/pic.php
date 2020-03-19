<?php
$time=time();
require_once 'config.php';
$file=htmlspecialchars($_GET['file']);
list(, , $type,)=getimagesize($file);

if($type==1){$funci='imagecreatefromgif';} //$funco='imagegif';}
if($type==2){$funci='imagecreatefromjpeg';} //$funco='imagejpeg';}
if($type==3){$funci='imagecreatefrompng';} //$funco='imagepng';}
if($type)
{
$im1 = $funci($file);
$im2=imagecreatetruecolor($neww,$newh);
imagecopyresized($im2, $im1, 0,0,0,0,$neww,$newh, imagesx($im1), imagesy($im1));

header('Content-type: image/gif');
imagegif($im2);
}
?>
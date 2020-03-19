<?php
require 'config.php';
require 'func.php';

$time=time();
$neww=intval($_REQUEST['x']);
$newh=intval($_REQUEST['y']);
$pr=intval($_REQUEST['pr']);

if(!$neww or !$newh)
{exit;}

if($neww>1600 or $neww<1)
{$neww=130;}
if($neww>1600 or $neww<1)
{$neww=130;}

$file=htmlspecialchars($_GET['file']);
if(substr($file,0,5)=='load/')
{
list($sx,$sy, $type,)=getimagesize($file);
$sxy=round($sx/$sy,3);
$swh=round($neww/$newh,3);

if(!$pr)
{
if($sxy<$swh)
{$neww=intval($newh*$sxy);}
else
{$newh=intval($neww/$sxy);}
}

if($type==1)
{$funci='imagecreatefromgif';} //$funco='imagegif';}
if($type==2)
{$funci='imagecreatefromjpeg';} //$funco='imagejpeg';}
if($type==3)
{$funci='imagecreatefrompng';} //$funco='imagepng';}
if($type)
{
$im1 = $funci($file);
$im2=imagecreatetruecolor($neww,$newh);
imagecopyresized($im2, $im1, 0,0,0,0,$neww,$newh, imagesx($im1), imagesy($im1));

header('Content-type: image/gif');
imagegif($im2);
}
}
?>
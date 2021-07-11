<?php
$mt=microtime(1);
require 'inc/config.php';
require 'inc/func.php';
if($zip)
{include 'inc/zip.php';}

$p=intval($_GET['p']);
$sort=intval($_GET['sort']);
if($sort>1 OR $sort<0)
{$sort=0;}

$file=str_replace("\0", null, htmlspecialchars($_GET['file']));

if(!file_exists($file) OR !is_file($file) OR !in_array(r($file), explode(',',$allfile)) OR strstr($file,'..') OR strstr($file,'://'))
{$file = null;}


$title=''.translit($file).' Download';

print '<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>'.$title.'</title>
<link rel="stylesheet" href="./images/style.css" type="text/css" />
</head>
<body>
<div class="head2">'.$title.'</div>';

if(!$file)
{
exit('<div class="red">Error:- No File Exist!<br/></div>'.$foot);
}

echo '<div class="d2">';
include 'ads/ads1.php';
echo '</div>';

$r = r($file);
print '<div class="fot">File: '.translit($file).'<br/>';

$filesize=filesize($file);
if($filesize>1024)
{$filesize=round($filesize/1024, 2).' Kb';}
else
{$filesize.=' Bytes';}
print 'File Size: '.$filesize.'<br/>';

$filectime=filemtime($file);
print 'File Creation Date: '.date('d/m/y H:i',filemtime($file)).'<br/>';

$basename=basename($file);
if(file_exists('skrin/'.$basename.'.jpg') and $p)
{
print 'Screenshot: <br/><img src="skrin/'.$basename.'.jpg" alt="'.translit($file).'"/><br/>';
}
elseif(file_exists('skrin/'.$basename.'.gif') and $p)
{
print 'Screenshot: <br/><img src="skrin/'.$basename.'.gif" alt="'.translit($file).'"/><br/>';
}
elseif(file_exists('skrin/'.$basename.'.png') and $p)
{
print 'Screenshot: <br/><img src="skrin/'.$basename.'.png" alt="'.translit($file).'"/><br/>';
}

if($r=='mp4' or $r=='3gp' or $r=='avi' and $p)
{
$currentFile =$file;
if(file_exists(getcwd().'/thumbs/'.$basename.'.jpg'))
{
$thumb='<img src="./thumbs/'.$basename.'.jpg" alt="" />';
}
else
{
$input=$currentFile;
if($videopreview)
{
$input=$currentFile;
exec("\"$ffmpegpath\" -itsoffset \"-$VthumbATseconds\" -i \"$input\" -vcodec mjpeg -vframes 1 -an -f rawvideo -s 90x50 \"./thumbs/$basename.jpg\"");
}
}
print $thumb.'<br/>';
}

if($descri)
{
if(file_exists('description/'.$basename.'.txt'))
{$descrip=file_get_contents('description/'.$basename.'.txt');
}
if(!$descrip=='')
{
print 'Desription: '.$descrip.'<br />';
}
}



if($r == 'mp3')
{
     $filesize = filesize($file);
     $mpfile = fopen($file, "r");
     fseek($mpfile, -128, SEEK_END);
     
     $tag = fread($mpfile, 3);
     
     
     if($tag == "TAG")
     {
         $data["song"] = trim(fread($mpfile, 30));
         $data["artist"] = trim(fread($mpfile, 30));
         $data["album"] = trim(fread($mpfile, 30));
         $data["year"] = trim(fread($mpfile, 4));
         $data["comment"] = trim(fread($mpfile, 30));
// $data["genre"] = $genre_arr[ord(trim(fread($mpfile, 1)))];
         
     }
     else
         die("MP3 file does not have any ID3 tag!");
     
     fclose($file);
     
     while(list($key, $value) = each($data))
     {
         print("$key: $value<br>\r\n");    
     }
}
if($r!='jpg' AND $r!='gif' AND $r!='png')
{
print '<img src="./images/down.gif" alt=""/> <strong><a href="'.$file.'">File Download</a></strong><br/>';
}
else
{

echo '<b>Image File Download</b><br/>';
list($x,$y, $type,)=@getimagesize($file);
if($type==1){$type='gif';}
if($type==2){$type='jpeg';}
if($type==3){$type='png';}
if($p)
{print '<img src="pic.php?file='.$file.'" alt="'.basename($file).'" /><br/>';}
print 'Image Type: '.$type.'<br/>
Original Size: '.$x.'x'.$y.'<br/>
&raquo;<a href="'.$file.'">Download Original</a><br/>
&raquo;<a href="imgload.php?x=130&amp;y=130&amp;file='.$file.'">Download 130x130</a><br/>
&raquo;<a href="imgload.php?x=132&amp;y=176&amp;file='.$file.'">Download 132x176</a><br/>
&raquo;<a href="imgload.php?x=176&amp;y=220&amp;file='.$file.'">Download 176x220</a><br/>
&raquo;<a href="imgload.php?x=240&amp;y=320&amp;file='.$file.'">Download 240x320</a><br/>
&raquo; Or Enter Size<br/>
<form method="post" action="imgload.php?file='.$file.'">
<div>
<input name="x" maxlength="3" size="1"/>x<input name="y" maxlength="3" size="1"/><br/>
<input name="pr" type="checkbox" value="1"/>Maintain Aspect Ratio<br/>
<input value="submit" type="submit"/>
</div>
</form>';
}

echo '<div class="d2">';
include 'ads/ads1.php';
echo '</div>';

echo '<div class="bor">Back To: ';

if(($countj=count(explode('/',$dir)))>1)
{
$j=explode('/',$dir);
for($i=0; $i<=$countj; $i++)
{
$u=$j[count($j)-1];
if($u)
{
$g[$i]= ' &raquo; <a href="index.php?dir='.join('/', $j).'&amp;p='.$p.'&amp;sort='.$sort.'">'.transdir($u).'</a>';
unset($j[count($j)-1]);
}
}
for($i=count($g)-1; $i>=0; $i--)
{print $g[$i];}

print '<br/>';
}
print '<img src="./images/home.gif" alt=""/> <a href="'.$siteaddress.'">Home</a> ';


print '<img src="./images/menu.gif" alt=""/><a href="index.php?p=1&amp;sort='.$sort.'">Menu</a> ';
print '<div class="footer2">&copy; <a href="'.$siteaddress.'">'.$sitename.'</a> 2012</div></body></html>';
?>

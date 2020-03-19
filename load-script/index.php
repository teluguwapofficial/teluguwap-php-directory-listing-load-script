<?php
$mt=microtime(1);
require 'inc/config.php';
require 'inc/func.php';
if($zip)
{include('inc/zip.php');}



$p=intval($_GET['p']);
$sort=intval($_GET['sort']);
if($sort>0 OR $sort<0)
{$sort=1;}

$dir=htmlspecialchars($_GET['dir']);

while(substr($dir,0,1)=='/')
{$dir=substr($dir,1,strlen($dir));}

if(strstr($dir,'..') OR !is_dir('load/'.$dir) OR strstr($dir,'://'))
{
$dir=null;
}

$opis = false;

$dir_exp=explode('/',$dir);
header('Content-type: text/html; charset=utf-8');
header('Cache-control: no-cache');
print '<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>


<title>'.transdir($dir_exp[count($dir_exp)-1]).' Downloads</title>
<link rel="stylesheet" href="./images/style.css" type="text/css" />
</head>
<body>';

if(!$dir)
{
print '<div class="head2">Downloads<br/></div>';
}
else
{
$dir_exp=explode('/',$dir);
print '<div class="head2"><b>'.transdir($dir_exp[count($dir_exp)-1]).'</b></div>';
}

echo '<div class="d2">';
include 'ads/ads1.php';
echo '</div>';


$newtime=date("m.d H:i",filectime($file));
$glob_dir=glob('load/'.$dir.'/*',GLOB_ONLYDIR);
if($glob_dir)
{
$count=sizeof($glob_dir);
$countstr=ceil($count/$dirstr);
$page=intval($_GET['page']);
if($sort)
{usort($glob_dir, 'sortnew');}
$start = $page * $dirstr;
if($start>=$count OR $start<0)
{$start=0;}
$end = $start + $dirstr;
if($end>=$count)
{$end = $count;}
for($i=$start; $i<$end; $i++)
{
$dirt=str_replace('load/',null,$glob_dir[$i]);
$dir_exp=explode('/',$dirt);
$count=countf($dirt);

if(file_exists('./load/'.$dirt.'/.folder.jpg'))
{
echo '<div class="bg"><table><tbody><tr><td><img src="./load/'.$dirt.'/.folder.jpg" alt="" height="90" width="70"><td/><td><strong>Files</strong>:- '.$count.'<br/><br/><strong>Movie</strong>:-';
}
else
{
print '<div class="bg"><table><tbody><tr><td><img src="./images/dir.gif" alt=""/>';
}

print '<a href="index.php?dir='.$dirt.'&amp;p=1&amp;sort='.$sort.'">'.$dir_exp[count($dir_exp)-1].'</a> </td></tr></tbody></table></div>';
}
}


if($p)
{
if(file_exists('./load/'.$dir.'/.folder.jpg'))
{
echo '<hr><img src="./load/'.$dir.'/.folder.jpg" alt="" height="120" width="90"></br>';

}
}

if(file_exists('./load/'.$dir.'/info.txt'))
{
include 'load/'.$dir.'/info.txt';
echo '<hr>';
}

$glob_file=glob("load/$dir/*.{{$allfile}}",GLOB_BRACE);
if($glob_file)
{
if($sort)
{usort($glob_file, 'sortnew');}
$count=sizeof($glob_file);
$countstr=ceil($count/$filestr);
$page=intval($_GET['page']);
$start = $page * $filestr;
if($start>=$count OR $start<0)
{$start=0;}
$end = $start + $filestr;
if($end>=$count)
{$end = $count;}
for($i=$start; $i<$end; $i++)
{
$name=translit($glob_file[$i]);
$filesize=filesize($glob_file[$i]);
if($filesize>1024)
{$filesize=round($filesize/1024, 2).' Kb';}
else
{$filesize.=' Bytes';}
if(r($glob_file[$i])=='txt')
{
$text=file($glob_file[$i]);
$name=$text[0];
$opis=$text[1].$text[2].$text[3].$text[4];
}

$basename=basename($glob_file[$i]);

if($p and file_exists('skrin/'.$basename.'.gif'))
{print '<img src="pic.php?file=skrin/'.$basename.'.gif" alt="'.$basename.'" /><br />';}
elseif($p and file_exists('skrin/'.$basename.'.jpg'))
{print '<img src="pic.php?file=skrin/'.$basename.'.jpg" alt="'.$basename.'" /><br />';}
elseif($p and file_exists('skrin/'.$basename.'.png'))
{print '<img src="pic.php?file=skrin/'.$basename.'.png" alt="'.$basename.'" /><br />';}
if((r($glob_file[$i])=='jpg' or r($glob_file[$i])=='gif' or r($glob_file[$i])=='png') and $p)
{print '<img src="pic.php?file='.$glob_file[$i].'" alt="'.$basename.'" /><br />';}

if((r($glob_file[$i])=='mp4' or r($glob_file[$i])=='3gp' or r($glob_file[$i])=='avi') and $p)
{
$currentFile =$glob_file[$i];
if(file_exists(getcwd().'/thumbs/'.$basename.'.jpg'))
{
$thumb='<img src="./thumbs/'.$basename.'.jpg" alt="" />';
}
else
{
if($videopreview)
{
$input=$currentFile;
exec("\"$ffmpegpath\" -itsoffset \"-$VthumbATseconds\" -i \"$input\" -vcodec mjpeg -vframes 1 -an -f rawvideo -s 90x50 \"./thumbs/$basename.jpg\"");
}
}
print $thumb;
}

if(r($glob_file[$i])=='jar')
{
print '<a href="'.$glob_file[$i].'">Download JAR ('.$filesize.')</a>[<a href="file.php?p=1&amp;file='.$glob_file[$i].'&amp;sort='.$sort.'">инфо</a>]<br />';
print '<a href="jad.php?p=1&amp;file='.$glob_file[$i].'&amp;sort='.$sort.'">Download JAD</a><br />';
}
else
{
print '<div class="bg"><img src="./images/file.gif" alt=""/> <a href="file.php?p=1&amp;file='.$glob_file[$i].'&amp;sort='.$sort.'">'.$basename.'</a> ['.$filesize.']</div>';
}


}
}

if($countstr>1)
{
print nav_page($countstr,$page,$dir,$p,$sort,'index');
}
$dir_exp=explode('/',$dir);

echo '<div class="d2">';
include 'ads/ads1.php';
echo '</div>';

echo '<div class="">';
if($p)
{
print '<strong>Images:</strong> On/<a href="index.php?p=0&amp;sort='.$sort.'&amp;dir='.$dir.'">Off</a><br/>';
}
else
{
print '<strong>Images:</strong><a href="index.php?p=1&amp;sort='.$sort.'&amp;dir='.$dir.'">On</a>/Off<br/>';
}


if($sort)
{
print '<strong>Sort By: </strong><a href="index.php?p='.$p.'&amp;sort=0&amp;dir='.$dir.'">Name</a>/Date<br/></div><div class="fot">';}
else
{
print '<strong>Sort By: </strong>Name /<a href="index.php?p='.$p.'&amp;sort=1&amp;dir='.$dir.'">Date</a><br/></div><div class="fot">';
}

echo '</div>';
echo '<div class="d2">';
include 'ads/ads1.php';
echo '</div>';
print '<img src="./images/home.gif" alt=""/> <a href="http://'.$_SERVER['HTTP_HOST'].'/">Home</a> ';

if(!$dir)
{ 
}
else
{
print '<img src="./images/menu.gif" alt=""/><a href="index.php?p=1&amp;sort='.$sort.'">Menu</a> ';
}
if(($countj=count(explode('/',$dir)))>1)
{
$j=explode('/',$dir);
for($i=0; $i<=$countj; $i++)
{
$u=$j[count($j)-2];
if($u)
{
unset($j[count($j)-1]);
$g[$i]= '<img src="./images/dir.gif" alt=""/> <a href="index.php?dir='.join('/', $j).'&amp;p=1&amp;sort='.$sort.'">'.transdir($u).'</a>';
}
}
for($i=count($g)-1; $i>=0; $i--)
{print $g[$i];}

print '<br/>';
}



print '</div>';

print '<div class="footer2">&copy; <a href="'.$siteaddress.'">'.$sitename.'</a> 2020</div></body></html>';

?>
<?php

function dep($dep) {
    return implode(',', array_keys(array_flip(explode(',', $dep))));
}


function json_url($url)
{
$curl = curl_init($url);
curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);  
$json_response = curl_exec($curl);
curl_close($curl);
$results=json_decode($json_response, true);

return $results;

}

function json_encoder($db, $query, $queryname, $jsonsave)
{

$result=$db->query($query);

$response = array();
$posts = array();
    
while ($row = $result->fetchArray()) 
{
$dataArray[] = $row;
$dir=str_replace('./load', '', $row['path']);
$title=clean($row['name']);
$year=$row['year'];
$timeupload=$row['timeupload'];
$rating=$row['rating'];
$posts[] = array('title'=> $title, 'dir'=> $dir, 'year'=> $year, 'timeupload'=> $timeupload, 'rating'=> $rating);
}

$response[''.$queryname.''] = $posts;


$total=count($posts);
if(!$total=='')
{
    
$fp = fopen('./jsondata/'.$jsonsave.'.json', 'w');
fwrite($fp, json_encode($response));
fclose($fp);

// echo 'json file Written as '.$jsonsave.'.json in "jsondata"<br/>';

$jsonfile='./jsondata/'.$jsonsave.'.json';

if(file_exists($jsonfile))
{
$string = file_get_contents($jsonfile);
$array  = json_decode($string, true);
// print_r($array);
}

}
}


function comma_separated_to_array($string, $separator = ', ')
{
  //Explode on comma
  $vals = explode($separator, $string);
 
  //Trim whitespace
  foreach($vals as $key => $val) {
    $vals[$key] = trim($val);
  }
  //Return empty array if no items found
  //http://php.net/manual/en/function.explode.php#114273
  return array_diff($vals, array(""));
}


function calculateFileSize($path)
{
      $bytes = sprintf('%u', filesize($path));

    if ($bytes > 0)
    {
        $unit = intval(log($bytes, 1024));
        $units = array('Bytes', 'Kb', 'Mb', 'Gb');

        if (array_key_exists($unit, $units) === true)
        {
            return sprintf('(%d %s)', $bytes / pow(1024, $unit), $units[$unit]);
        }
    }

    return $bytes;
}

function clean($string)
{
$string=ereg_replace('([0-9][0-9][0-9][0-9])', '', $string);
$string=ereg_replace('[0-9][0-9]_-_', '', $string);
$string=str_replace(')', '', $string);
$string=str_replace('-(', '', $string);
$string=str_replace('_(', '', $string);
$string=str_replace('*', '', $string);
$string=str_replace('-', ' ', $string);
$string=str_replace('_', ' ', $string);
return ucwords($string);
}



function grab_image($url,$saveto){
    $ch = curl_init ($url);
  $header[0] = "Accept: text/xml,application/xml,application/xhtml+xml,";
  $header[0] .= "text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5";
  $header[] = "Cache-Control: max-age=0";
  $header[] = "Connection: keep-alive";
  $header[] = "Keep-Alive: 300";
  $header[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
  $header[] = "Accept-Language: en-us,en;q=0.5";
  $header[] = "Pragma: "; // browsers keep this blank.

  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (iPhone; U; CPU iPhone OS 3_0 like Mac OS X; en-us) AppleWebKit/528.18 (KHTML, like Gecko) Version/4.0 Mobile/7A341 Safari/528.16');
  curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
  curl_setopt($ch, CURLOPT_REFERER, 'www.omdbapi.com');
  curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookiefile.txt');
  curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookiefile.txt'); 
  curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
  curl_setopt($ch, CURLOPT_AUTOREFERER, true);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
  curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $raw=curl_exec($ch);
    curl_close ($ch);
    
    
if($saveto!='0'){

    $fp = fopen($saveto,'x');
    fwrite($fp, $raw);
    fclose($fp);
}
else
{
return $raw;
}

}


function extract_numbers($string)

{
preg_match_all('/[(](19[3-9][0-9])[)]|[(](20[0-1][0-9][)])/', $string, $match);
return $match[0];
}



function r($f,$t=0)



{



//$r=explode('.',$f);



//return strtolower($r[count($r)-1-$t]);







return strtolower(substr(strrchr($f,'.'),1));



}







function translit($dir)



{



$dir=basename($dir);



$j=explode('.', $dir);



$r=$j[count($j)-1];



unset($j[count($j)-1]);



$dir=join('.',$j);



if(substr($dir,0, 1)=='!')



{



$trans2=array('Ё','Ж','Щ','Ш','Ч','Э','Ю','Я','ё','ж','щ','ш','ч','э','ю',



'я','А','Б','В','Г','Д','Е','З','И','Й','К','Л','М','Н','О','П','Р','С','Т',



'У','Ф','Х','Ц','Ь','Ы','а','б','в','г','д','е','з','и','й','к','л','м','н',



'о','п','р','с','т','у','ф','х','ц','Ъ','ь','ы');



$trans1= array('JO','ZH','SCH','SH','CH','JE','JY','JA','jo','zh','sch','sh','ch','je','jy',



'ja','A','B','V','G','D','E','Z','I','J','K','L','M','N','O','P','R','S','T',



'U','F','H','C',"'",'Y','a','b','v','g','d','e','z','i','j','k','l','m','n',



'o','p','r','s','t','u','f','h','c',"''","'",'y');



$dir=str_replace($trans1,$trans2,$dir);



$dir=substr($dir,1,strlen($dir));



}



$dir=str_replace('_', ' ', $dir);



return $dir.'.'.$r;



}







function transdir($dir)



{



$dir=str_replace('lib/', '', $dir);



$j=explode('/',$dir);



$countj=count($j);



for($i=0; $i<$countj; $i++)



{



if(substr($j[$i],0, 1)=='!')



{



$trans2=array('Ё','Ж','Щ','Ш','Ч','Э','Ю','Я','ё','ж','щ','ш','ч','э','ю',



'я','А','Б','В','Г','Д','Е','З','И','Й','К','Л','М','Н','О','П','Р','С','Т',



'У','Ф','Х','Ц','Ь','Ы','а','б','в','г','д','е','з','и','й','к','л','м','н',



'о','п','р','с','т','у','ф','х','ц','Ъ','ь','ы');



$trans1=array('JO','ZH','SCH','SH','CH','JE','JY','JA','jo','zh','sch','sh','ch','je','jy',



'ja','A','B','V','G','D','E','Z','I','J','K','L','M','N','O','P','R','S','T',



'U','F','H','C',"'",'Y','a','b','v','g','d','e','z','i','j','k','l','m','n',



'o','p','r','s','t','u','f','h','c',"''","'",'y');



$j[$i]=str_replace($trans1,$trans2,$j[$i]);



$j[$i]=substr($j[$i],1,strlen($j[$i]));



}







}







$dir=str_replace('_', ' ', join('/',$j));



return $dir;



}







function countf($f)



{



$f2 = str_replace('/', 'D',$f);







if(file_exists('count/'.$f2.'.dat'))



{



$j=explode('|',file_get_contents('count/'.$f2.'.dat'));



if($j[0]>time()-3600)



{return $j[1];}



}







$d[] = 'load/'.$f;



$sz = 0;



do



{



$dir = array_shift($d);



$h = opendir($dir);



while($f = readdir($h))



{



if($f != '.' && $f != '..' && $f != 'screen.jpg' && $f != 'folder.jpg' && $f != '1.txt'  && $f != '.no'  && $f != 'cover.jpg' && $f[0]!='.' && is_readable($dir.'/'.$f))
{



if(is_dir($dir.'/'.$f))



{$d[] = $dir.'/'.$f;}



else



{++$all;}



$sz += filesize($dir.'/'.$f);



}



}



closedir($h);



}



while(sizeof($d) > 0);







if($sz >= 1048576)
{$c = (int)$all.' </br>Size:- '.round($sz/1048576,1).' Mb';}

else
{$c = (int)$all.' </br>Size:- '.round($sz/1024,1).' Kb';}

file_put_contents('count/'.$f2.'.dat',time().'|'.$c);

return $c;
}






function nav_page($countstr,$page,$dir,$p,$sort,$main)



{



$list='<div class="head2">Page: '.($page+1).' Of '.$countstr.'</div>';



for($i=0; $i<3; $i++)



{



if($i>$countstr-1)



break;



if($page!=$i)



$list.='<a href="'.$main.'.php?dir='.$dir.'&amp;p='.$p.'&amp;page='.$i.'&amp;sort='.$sort.'">'.($i+1).'</a>|';



else



$list.=($i+1).'|';



}



if($countstr>3)



{



$list.='...';







for($n=$page-3; $n<$page+3; $n++)



{



if($n>$countstr-1)



break;



if($n<3)



continue;



if($page!=$n)



$list.='<a href="'.$main.'.php?dir='.$dir.'&amp;p='.$p.'&amp;page='.$n.'&amp;sort='.$sort.'">'.($n+1).'</a>|';



else



$list.=($n+1).'|';



}



$next=$n;



}



if(@$n<$countstr and isset($n))



{



$list.='..';



for($n=$countstr-3; $n<$countstr; $n++)



{



if($n<@$next)



continue;



if($page!=$n)



$list.='<a href="'.$main.'.php?dir='.$dir.'&amp;p='.$p.'&amp;page='.$n.'&amp;sort='.$sort.'">'.($n+1).'</a>|';



else



$list.=($n+1).'|';



}



}



return $list.'<br />';



}







function nav_pagetxt($countstr,$page,$dir,$p,$sort,$main)



{



$list='<div class="head2">Page '.($page+1).' Of '.$countstr.'</div>';



for($i=0; $i<3; $i++)



{



if($i>$countstr-1)



break;



if($page!=$i)



$list.='<a href="'.$main.'.php?file='.$dir.'&amp;p='.$p.'&amp;page='.($i+1).'&amp;sort='.$sort.'">'.($i+1).'</a>|';



else



$list.=($i+1).'|';



}



if($countstr>3)



{



$list.='...';







for($n=$page-3; $n<$page+3; $n++)



{



if($n>$countstr-1)



break;



if($n<3)



continue;



if($page!=$n)



$list.='<a href="'.$main.'.php?file='.$dir.'&amp;p='.$p.'&amp;page='.($n+1).'&amp;sort='.$sort.'">'.($n+1).'</a>|';



else



$list.=($n+1).'|';



}



$next=$n;



}



if(@$n<$countstr and isset($n))



{



$list.='..';



for($n=$countstr-3; $n<$countstr; $n++)



{



if($n<@$next)



continue;



if($page!=$n)



$list.='<a href="'.$main.'.php?file='.$dir.'&amp;p='.$p.'&amp;page='.($n+1).'&amp;sort='.$sort.'">'.($n+1).'</a>|';



else



$list.=($n+1).'|';



}



}



return $list.'<br />';



}







function sortnew($file1,$file2)



{



$time1=filectime($file1);



$time2=filectime($file2);



if($time1==$time2)



return 0;



elseif($time1>$time2)



return -1;



else



return 1;



}



?>
<?php
$PREFER_DEFLATE = false; // Если поддерживает 2 вида компрессии хватит одного или нет
$FORCE_COMPRESSION = false; // force compression even when client does not report support
//////////////////////////////////////
function compress_output_gzip($output) {
return gzencode($output);
}

function compress_output_deflate($output) {
return gzdeflate($output, 9);
}

function compress_output_x_gzip($output) {
return gzcompress($output, 9);
}

if(isset($_SERVER['HTTP_ACCEPT_ENCODING']))
{$AE = $_SERVER['HTTP_ACCEPT_ENCODING'];}
else
{$AE = $_SERVER['HTTP_TE'];}

$support_gzip = (strpos($AE, 'gzip') !== FALSE) || $FORCE_COMPRESSION;
$support_deflate = (strpos($AE, 'deflate') !== FALSE) || $FORCE_COMPRESSION;
$support_x_gzip = (strpos($AE, 'x-gzip') !== FALSE) || $FORCE_COMPRESSION;


if($support_gzip && $support_deflate) {
$support_deflate = $PREFER_DEFLATE;
}

if ($support_deflate) {
header('Content-Encoding: deflate');
ob_start('compress_output_deflate');
} else{
if($support_gzip){
header('Content-Encoding: gzip');
ob_start('compress_output_gzip');
} else {

if($support_x_gzip){
header('Content-Encoding: x_gzip');
ob_start('compress_output_x_gzip');	}
else {
ob_start();
$config_gzip='0';
}}}
if(!extension_loaded('zlib'))
{$config_gzip='0';}
function csites($string) {
global $config_c;
$string = str_replace('</div></body>',$config_c.'</div></body>',$string);
$string = str_replace('<br/></p></card>',$config_c.'</p></card>',$string);
return $string;
}
@ob_start(csites);
?>
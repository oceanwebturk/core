<?php

define("OceanWT_VERSION", "2.0");
define("REQUIRED_PHP_VERSION","7.4");

if(phpversion()<REQUIRED_PHP_VERSION){
 $message="Your PHP Version needs to be ".REQUIRED_PHP_VERSION." and above";
 $file=__FILE__;
 $line=__LINE__;
 include(__DIR__."/Views/layout-handler.php");
 exit;
}

function request_uri($path = __DIR__)
{
    $root = "";
    $dir = str_replace('\\', '/', realpath($path));
    if(!empty($_SERVER['CONTEXT_PREFIX'])) {
        $root .= $_SERVER['CONTEXT_PREFIX'];
        $root .= substr($dir, strlen($_SERVER['CONTEXT_DOCUMENT_ROOT']));
    } else {
        $root .= substr($dir, strlen($_SERVER['DOCUMENT_ROOT']));
    }
    return $root;
}
function minify($data, $st = true)
{
    if($st) {
        return preg_replace(array(
        '/ {2,}/',
        '/<!--.*?-->|\t|(?:\r?\n[ \t]*)+/s'
        ), array(' ',''), $data);
    } else {
        return $data;
    }
}

use OceanWT\Hook;
use OceanWT\Support\Translation\Lang;

if(!function_exists("view")){
 function view($name, $data = array()){
   \OceanWT\Import::view($name, $data);
 }
}
if(!function_exists("config")){
 function config($config){
  return \OceanWT\Config::get($config);
 }
}
if(!function_exists("do_action")){
 function do_action($name, $args = array()){
  Hook::trigger($name, $args);
 }
}
if(!function_exists("add_action")){
 function add_action($name, $callback){
    Hook::add($name, $callback);
 }
}
if(!function_exists("remove_action")){
 function remove_action($name, $callback){
     Hook::remove($name, $callback);
 }
}


if(!function_exists("lang")){
 function lang($name){
  return Lang::get($name);
 }
}

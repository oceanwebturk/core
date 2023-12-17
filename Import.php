<?php

namespace OceanWT;

class Import
{
  use Traits\Macro;
 /**
  * @param  string $file
  * @param  array  $data
  */
 public static function custom_view(string $file,array $data = [])
 {
     extract($data);
     include($file);
 }
 
 /**
  * @param  string $name
  * @param  array  $data
  */
 public static function view(string $name,array $data = [])
 {
   if(is_array(OceanWT::$configs['templateEngine'])){
    echo @call_user_func_array([OceanWT::$configs['templateEngine'][0],OceanWT::$configs['templateEngine'][1]],[$name,$data]);
   }else{
    echo call_user_func_array(OceanWT::$configs['templateEngine'],[$name,$data]);
   }
 }
}

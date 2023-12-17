<?php 

namespace OceanWT;

class Container
{
 use Traits\Macro; 
 /**
  * @var int|null
  */
 protected static $startTime=null;
 
 /**
  * @var string
  */
 protected static $application = null;

 /**
  * @var array
  */
 protected static $bindings=[],$aliases=[],$appNamespaces=[
  __NAMESPACE__.'\Application\\',
 ];

 public function __construct()
 {
  self::$startTime=microtime(true);
 }

 /**
  * @param  string $aliases
  * @param  array  $class
  */
 public static function alias(string $alias,string|array $class)
 {
   self::$aliases[$alias]=$class;
 }
 
 /**
  * @param  object $abstract 
  * @param  array  $callback
  */
 public static function bind(string|object $abstract,string|callable|array $callback)
 {
   self::$bindings[$abstract]=compact('callback');
 }
 
 /**
  * @param  object $name  
  * @param  array  $params
  * @return mixed
  */
 public static function make(string|object $name,array $params=[])
 {
  if(isset(self::$bindings[$name])){
   $arr=self::$bindings[$name]['callback'];
   if(is_object($arr)){
    echo call_user_func($arr);
   }
  }
 }

 /**
  * @param string $namespace
  */
 public static function addAppNamespace(string $namespace)
 {
  self::$appNamespaces[]=$namespace;
  return new self;
 }

 /**
  * @return array
  */
 public static function getAppNamespaces()
 {
  return self::$appNamespaces;
 }

 /**
  * @return object
  */
 public static function getApplication(...$params)
 {
  if(!is_null(self::$application)){
   foreach(self::getAppNamespaces()as$namespace){
    $class=$namespace.self::$application;
    if(class_exists($class)){
     return new $class(...$params);
    }else{
     return false;
    }
   }
  }
 }
}

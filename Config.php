<?php

namespace OceanWT;

class Config
{
     use \OceanWT\Support\Traits\Macro; 

    /**
     * @var string
     */
    public static $path = GET_DIRS['CONFIGS'];

    /**
     * @var string
     */
    public static $namespace = GET_NAMESPACES['CONFIGS'];

    /**
     * @param string $path
     */
    public static function setPath($path)
    {
        self::$path = $path;
        return new self();
    }

    /**
     * @param string $namespace
     */
    public static function setNamespace($namespace)
    {
        self::$namespace = $namespace;
        return new self();
    }

    /**
     * @param $class
     */
    public static function default($class)
    {
      if(class_exists($class)){
        return new $class();
      }
      return new self;
    }
    
    /**
     * @param  string $name
     */
    public static function get($name)
    {
        $class = self::$namespace.ucfirst($name);
        if(class_exists($name)) {
            return new $name();
        } elseif(class_exists($class)) {
            return new $class();
        }elseif(file_exists($name.".php")){
         if(is_array(include($name.".php"))) {
                return (Object) include($name.".php");
         } else {
                include($name.".php");
                return isset($$name) ? (Object) $$name : '';
         }
        }elseif(file_exists(self::$path.$name.".php")) {
            if(is_array(include(self::$path.$name.".php"))) {
                return (Object) include(self::$path.$name.".php");
            } else {
                include(self::$path.$name.".php");
                return isset($$name) ? (Object) $$name : '';
            }
        }
    }
}

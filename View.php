<?php

namespace OceanWT;

class View
{ 
    use Support\Traits\Macro;
    /**
     * @var array
     */
    public static $paths=[
     GET_DIRS["VIEWS"]
    ];
    
    /**
     * @var array
     */
    protected static $configs = [];

    public static function configs($configs)
    {
        self::$configs = $configs;
        return new self();
    }

    public function render($name, $data = [])
    {
     extract($data);
     if(strpos($name,'::')){
      $ex=explode("::",$name);
      include(self::$paths[$ex[0]].$ex[1].".php");
     }else{
      include(self::$paths[0].$name.".php");
     }
    }
    public static function fileExtension($name)
    {
        return $name.(isset(self::$configs['suffix']) ? self::$configs['suffix'] : '.php');
    }
}

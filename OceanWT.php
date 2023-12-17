<?php

namespace OceanWT;

class OceanWT extends Container
{
    /**
     * @var \OceanWT\Autoloader
     */
    public $autoloader;

    /**
     * @var \OceanWT\OceanWT
     */
    public static $app;

    /**
     * @var array
     */
    public static $configs = [],$defines = [],$namespaces = [];

    /**
     * @param string|null $rootDir
     */
    public function __construct(string $rootDir = null)
    {
       self::$app = $this;
       $this->autoloader = new Autoloader();
       if(!defined('REAL_BASE_DIR')) {
        define("REAL_BASE_DIR", $rootDir);
       }
       self::registerBaseBindings();
    }

    /**
     * @param  array $configs
     * @return \OceanWT\OceanWT
     */
    public static function configs(array $configs)
    {
        self::$configs = $configs;
        return new self();
    }

    /**
     * @param  array  $defines
     * @return \OceanWT\OceanWT
     */
    public static function defines(array $defines)
    {
        self::$defines = $defines;
        return new self();
    }

    /**
     * @param  array  $namespaces
     * @return \OceanWT\OceanWT
     */
    public static function namespaces(array $namespaces)
    {
        self::$namespaces = $namespaces;
        return new self();
    }

    /**
     * @param string $context
     */
    public static function setApplication(string $app)
    {
        self::$application = $app;
        return new self();
    }

    /**
     * @return void
     */
    protected static function registerBaseBindings()
    {
     ini_set("default_charset","UTF-8");
    }

    /**
     * @return void
     */
    public static function init()
    {
     define('GET_DIRS',self::getPaths());
     define('GET_NAMESPACES', self::getNamespaces());
     foreach(GET_NAMESPACES as$nKey => $nVal) {
      self::$app->autoloader->addNamespace($nKey, $nVal);
      if(isset(GET_DIRS[$nKey])) {
       self::$app->autoloader->addNamespace(GET_NAMESPACES[$nKey], GET_DIRS[$nKey]);
      }
     }
     self::$app->autoloader->register();
     if(Config::get("autoload")->composer==true && file_exists(Config::get("autoload")->composer_path."autoload.php")){
      include(Config::get("autoload")->composer_path."autoload.php");
     }
     if(Config::get("app")->mode=="development"){
      if(self::getApplication() && method_exists(self::getApplication(),"customHandler")){
       echo self::getApplication() ? call_user_func([self::getApplication(),'customHandler']) : '';
      }else{
       set_error_handler("\OceanWT\OceanWT::errorHandler");
       set_exception_handler("\OceanWT\OceanWT::exceptionHandler");
      }
     }
     do_action("system_init");
    }

    /**
     * @param  string|null $routeMode
     */
    public static function run(string $routeMode=null)
    {
     self::init();
     if(!Config::get("view")->default || Config::get("view")->default=="view"){
        self::templateEngine([(new View()),'render']);
     }elseif(Config::get("view")->engines[Config::get("view")->default]){
        self::templateEngine(Config::get("view")->engines[Config::get("view")->default]);
     }
     self::setLocale(Config::get("app")->lang);
     self::providerLists();
     ob_start();
     if(self::getApplication()){
      echo call_user_func([self::getApplication(),'execute']);
     }else{
      Http\Route::mode($routeMode)->run();
     }
     echo \minify(ob_get_clean(),(isset(self::getApplication()->minify) ? self::getApplication()->minify : Config::get("app")->minify));
    }

    /**
     * @param  string $methodName
     */
    protected static function providerLists(string $methodName = "boot")
    {
        for($i = 0;$i < count(Config::get("app")->providers);$i++) {
            $class = Config::get("app")->providers[$i];
            $class = new $class();
            if(method_exists($class, $methodName)) {
                echo $class->$methodName();
            }
        }
    }

    /**
     * @return array
     */
    public static function getPaths()
    {
       return self::$defines+[
         "DIRECTORY_ROOT" => "public/",
         "CONFIGS" => REAL_BASE_DIR."etc/",
         "SERVICES" => REAL_BASE_DIR."srv/",
         "LOGS" => REAL_BASE_DIR."var/logs/",
         "LANGS" => REAL_BASE_DIR."var/langs/",
         "VIEWS" => REAL_BASE_DIR."var/html/",
         "CACHES" => REAL_BASE_DIR."var/cache/",
         "CONTROLLERS" => REAL_BASE_DIR."app/Controllers",
        ]+(self::getApplication() && is_callable([self::getApplication(),"paths"]) ? call_user_func([self::getApplication(),"paths"]) : []);
    }

    /**
     * @return array
     */
    public static function getNamespaces()
    {
        return self::$namespaces+[
         "CONFIGS" => "Config\\",
         "SERVICES" => "Services\\",
         "App\\"=>REAL_BASE_DIR."app/",
         "CONTROLLERS" => "App\Controllers\\",
        ]+(self::getApplication() && is_callable([self::getApplication(),"namespaces"]) ? call_user_func([self::getApplication(),"namespaces"]) : []);
    }
    
    /**
     * @param  object $e
     * @return string
     */
    public static function exceptionHandler(object $e)
    {
      ob_start();
      $file=$e->getFile();
      $line=$e->getLine();
      $message=$e->getMessage();
      include(__DIR__.'/Views/layout-handler.php');
      echo minify(ob_get_clean()); 
      exit;
    }
    
    /**
     * @param  int    $no     
     * @param  string $message
     * @param  string $file   
     * @param  int    $line   
     * @return string
     */
    public static function errorHandler(int $no,string $message,string $file,int $line)
    {
      include(__DIR__.'/Views/layout-handler.php');
      exit;
    }
    
    /**
     * @param string|callable|array $engine
     */
    public static function templateEngine(string|callable|array $engine)
    {
     self::$configs['templateEngine']=$engine;
     return new self;
    }

    /**
     * @param  string $locale
     */
    public static function setLocale(string $locale)
    {
      Translation\Lang::$appLang=$locale;
    }

    /**
     * @param string $locale
     */
    public static function setSysLocale(string $locale)
    {
      Translation\Lang::$sysLang=$locale;
    }

}

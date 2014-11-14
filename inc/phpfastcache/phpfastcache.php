<?php
/*
 * khoaofgod@yahoo.com
 * Website: http://www.phpfastcache.com
 * Example at our website, any bugs, problems, please visit http://www.codehelper.io
 */

require_once(dirname(__FILE__)."/driver.php");

// short function
if(!function_exists("__c")) {
    function __c($storage = "", $option = array()) {
        return phpfastcache($storage, $option);
    }
}

// main function
if(!function_exists("phpFastCache")) {
    function phpFastCache($storage = "", $option = array()) {
        if(!isset(phpFastCache_instances::$instances[$storage])) {
            phpFastCache_instances::$instances[$storage] = new phpFastCache($storage, $option);
        }
        return phpFastCache_instances::$instances[$storage];
    }
}

class phpFastCache_instances {
    public static $instances = array();
}

// main class
class phpFastCache {

    public static $storage = "auto";
    public static $config = array(
            "storage"   =>  "auto",
            "fallback"  =>  array(
                                "example"   =>  "files",
            ),
            "securityKey"   =>  "auto",
            "htaccess"      => true,
            "path"      =>  "",

            "server"        =>  array(
                array("127.0.0.1",11211,1),
                //  array("new.host.ip",11211,1),
            ),

            "extensions"    =>  array(),

    );

    var $tmp = array();
    var  $checked = array(
        "path"  => false,
        "fallback"  => false,
        "hook"      => false,
    );
    var $is_driver = false;
    var $driver = NULL;

    // default options, this will be merge to Driver's Options
    var $option = array(
        "path"  =>  "", // path for cache folder
        "htaccess"  => null, // auto create htaccess
        "securityKey"   => null,  // Key Folder, Setup Per Domain will good.
        "system"        =>  array(),
        "storage"       =>  "",
        "cachePath"     =>  "",
    );

    /*
     * Basic Method
     */

    function set($keyword, $value = "", $time = 300, $option = array() ) {
        $object = array(
            "value" => $value,
            "write_time"  => @date("U"),
            "expired_in"  => $time,
            "expired_time"  => @date("U") + (Int)$time,
        );
        if($this->is_driver == true) {
            return $this->driver_set($keyword,$object,$time,$option);
        } else {
            return $this->driver->driver_set($keyword,$object,$time,$option);
        }

    }

    function get($keyword, $option = array()) {
        if($this->is_driver == true) {
            $object = $this->driver_get($keyword,$option);
        } else {
            $object = $this->driver->driver_get($keyword,$option);
        }

        if($object == null) {
            return null;
        }
        return $object['value'];
    }

    function getInfo($keyword, $option = array()) {
        if($this->is_driver == true) {
            $object = $this->driver_get($keyword,$option);
        } else {
            $object = $this->driver->driver_get($keyword,$option);
        }

        if($object == null) {
            return null;
        }
        return $object;
    }

    function delete($keyword, $option = array()) {
        if($this->is_driver == true) {
            return $this->driver_delete($keyword,$option);
        } else {
            return $this->driver->driver_delete($keyword,$option);
        }

    }

    function stats($option = array()) {
        if($this->is_driver == true) {
            return $this->driver_stats($option);
        } else {
            return $this->driver->driver_stats($option);
        }

    }

    function clean($option = array()) {
        if($this->is_driver == true) {
            return $this->driver_clean($option);
        } else {
            return $this->driver->driver_clean($option);
        }

    }

    function isExisting($keyword) {
        if($this->is_driver == true) {
            $object = $this->driver_get($keyword);
        } else {
            $object = $this->driver->driver_get($keyword);
        }

        if($object == null || empty($object['value'])) {
            return false;
        } else {
            return true;
        }
    }

    function increment($keyword, $step = 1 , $option = array()) {
        $object = $this->get($keyword);
        if($object == null) {
            return false;
        } else {
            $value = (Int)$object['value'] + (Int)$step;
            $time = $object['expired_time'] - @date("U");
            $this->set($keyword,$value, $time, $option);
            return true;
        }
    }

    function decrement($keyword, $step = 1 , $option = array()) {
        $object = $this->get($keyword);
        if($object == null) {
            return false;
        } else {
            $value = (Int)$object['value'] - (Int)$step;
            $time = $object['expired_time'] - @date("U");
            $this->set($keyword,$value, $time, $option);
            return true;
        }
    }
    /*
     * Extend more time
     */
    function touch($keyword, $time = 300, $option = array()) {
        $object = $this->get($keyword);
        if($object == null) {
            return false;
        } else {
            $value = $object['value'];
            $time = $object['expired_time'] - @date("U") + $time;
            $this->set($keyword, $value,$time, $option);
            return true;
        }
    }

    /*
    * Other Functions Built-int for phpFastCache since 1.3
    */

    public function setMulti($list = array()) {
        foreach($list as $array) {
            $this->set($array[0], isset($array[1]) ? $array[1] : 300, isset($array[2]) ? $array[2] : array());
        }
    }

    public function getMulti($list = array()) {
        $res = array();
        foreach($list as $array) {
            $name = $array[0];
            $res[$name] = $this->get($name, isset($array[1]) ? $array[1] : array());
        }
        return $res;
    }

    public function getInfoMulti($list = array()) {
        $res = array();
        foreach($list as $array) {
            $name = $array[0];
            $res[$name] = $this->getInfo($name, isset($array[1]) ? $array[1] : array());
        }
        return $res;
    }

    public function deleteMulti($list = array()) {
        foreach($list as $array) {
            $this->delete($array[0], isset($array[1]) ? $array[1] : array());
        }
    }

    public function isExistingMulti($list = array()) {
        $res = array();
        foreach($list as $array) {
            $name = $array[0];
            $res[$name] = $this->isExisting($name);
        }
        return $res;
    }

    public function incrementMulti($list = array()) {
        $res = array();
        foreach($list as $array) {
            $name = $array[0];
            $res[$name] = $this->increment($name, $array[1], isset($array[2]) ? $array[2] : array());
        }
        return $res;
    }

    public function decrementMulti($list = array()) {
        $res = array();
        foreach($list as $array) {
            $name = $array[0];
            $res[$name] = $this->decrement($name, $array[1], isset($array[2]) ? $array[2] : array());
        }
        return $res;
    }

    public function touchMulti($list = array()) {
        $res = array();
        foreach($list as $array) {
            $name = $array[0];
            $res[$name] = $this->touch($name, $array[1], isset($array[2]) ? $array[2] : array());
        }
        return $res;
    }

    /*
     * Begin Parent Classes;
     */
    public static function setup($name,$value = "") {
        if(!is_array($name)) {
            if($name == "storage") {
                self::$storage = $value;
            }

            self::$config[$name] = $value;
        } else {
            foreach($name as $n=>$value) {
                self::setup($n,$value);
            }
        }

    }

    function __construct($storage = "", $option = array()) {
        if(isset(self::$config['fallback'][$storage])) {
            $storage = self::$config['fallback'][$storage];
        }

        if($storage == "") {
            $storage = self::$storage;
            self::option("storage", $storage);

        } else {
            self::$storage = $storage;
        }

        $this->tmp['storage'] = $storage;

        $this->option = array_merge($this->option, self::$config, $option);

        if($storage!="auto" && $storage!="" && $this->isExistingDriver($storage)) {
            $driver = "phpfastcache_".$storage;
        } else {
            $storage = $this->autoDriver();
            self::$storage = $storage;
            $driver = "phpfastcache_".$storage;
        }
        require_once(dirname(__FILE__)."/drivers/".$storage.".php");

        $this->option("storage",$storage);

        if($this->option['securityKey'] == "auto" || $this->option['securityKey'] == "") {
            $suffix = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : get_current_user();
            $this->option['securityKey'] = "cache.storage.".$suffix;
        }

        $this->option['securityKey_mcrypt'] = $this->option['securityKey'];
        $this->option['securityKey'] = sha1($this->option['securityKey']);

        $this->driver = new $driver($this->option);
        $this->driver->is_driver = true;
    }

    /*
     * For Auto Driver
     *
     */
    function autoDriver() {
        $driver = "files";
        if(extension_loaded('apc') && ini_get('apc.enabled') && strpos(PHP_SAPI,"CGI") === false)
            $driver = "apc";
        elseif(extension_loaded('pdo_sqlite') && is_writeable($this->getPath()))
            $driver = "sqlite";
        elseif(is_writeable($this->getPath()))
            $driver = "files";
        else if(class_exists("memcached"))
            $driver = "memcached";
        elseif(extension_loaded('wincache') && function_exists("wincache_ucache_set"))
            $driver = "wincache";
        elseif(extension_loaded('xcache') && function_exists("xcache_get"))
            $driver = "xcache";
        else if(function_exists("memcache_connect"))
            $driver = "memcache";
        else {
            $path = dirname(__FILE__)."/drivers";
            $dir = opendir($path);
            while($file = readdir($dir)) {
                if($file!="." && $file!=".." && strpos($file,".php") !== false) {
                    require_once($path."/".$file);
                    $namex = str_replace(".php","",$file);
                    $class = "phpfastcache_".$namex;
                    $option = $this->option;
                    $option['skipError'] = true;
                    $driver = new $class($option);
                    $driver->option = $option;
                    if($driver->checkdriver()) {
                        $driver = $namex;
                    }
                }
            }
        }
        return $driver;
    }

    function option($name, $value = null) {
        if($value == null) {
            if(isset($this->option[$name])) {
                return $this->option[$name];
            } else {
                return null;
            }
        } else {

            if($name == "path") {
                $this->checked['path'] = false;
                $this->driver->checked['path'] = false;
            }

            self::$config[$name] = $value;
            $this->option[$name] = $value;
            $this->driver->option[$name] = $this->option[$name];

            return $this;
        }
    }

    public function setOption($option = array()) {
        $this->option = array_merge($this->option, self::$config, $option);
        $this->checked['path'] = false;
    }

    function __get($name) {
        $this->driver->option = $this->option;
        return $this->driver->get($name);
    }

    function __set($name, $v) {
        $this->driver->option = $this->option;
        if(isset($v[1]) && is_numeric($v[1])) {
            return $this->driver->set($name,$v[0],$v[1], isset($v[2]) ? $v[2] : array() );
        } else {
            throw new Exception("Example ->$name = array('VALUE', 300);",98);
        }
    }

    /*
     * Only require_once for the class u use.
     * Not use autoload default of PHP and don't need to load all classes as default
     */
    private function isExistingDriver($class) {
        if(file_exists(dirname(__FILE__)."/drivers/".$class.".php")) {
            require_once(dirname(__FILE__)."/drivers/".$class.".php");
            if(class_exists("phpfastcache_".$class)) {
                return true;
            }
        }

        return false;
    }

    function encode($data,$binary=false,$hex=false) {
        return session::encode($data,$this->option['securityKey_mcrypt'],$binary,$hex);
    }

    function decode($data,$binary=false,$hex=false) {
        return session::decode($data,$this->option['securityKey_mcrypt'],$binary,$hex);
    }

    /*
     * Auto Create .htaccess to protect cache folder
     */
    public function htaccessGen($path = "") {
        global $config_cache;
        if($this->option("htaccess") == true && $config_cache['use_cache']) {
            if(!file_exists($path."/.htaccess")) {
                $html = "order deny, allow \r\ndeny from all \r\nallow from 127.0.0.1";
                if(!file_put_contents($path."/.htaccess", $html)) {
                    DebugConsole::insert_warning('phpFastCache::htaccessGen()', "Can't create .htaccess");
                    $config_cache['use_cache'] = false;
                }
            }
        }
    }

    /*
    * Check phpModules or CGI
    */
    public function isPHPModule() {
        if(PHP_SAPI == "apache2handler") {
            return true;
        } else {
            if(strpos(PHP_SAPI,"handler") !== false) {
                return true;
            }
        }
        return false;
    }

    /*
     * return PATH for Files & PDO only
     */
    public function getPath($create_path = false) {
        if($this->option['path'] == "" && self::$config['path']!="") {
            $this->option("path", self::$config['path']);
        }

        if ($this->option['path'] =='') {
            if($this->isPHPModule()) {
                $tmp_dir = ini_get('upload_tmp_dir') ? ini_get('upload_tmp_dir') : sys_get_temp_dir();
                $this->option("path",$tmp_dir);
            } else {
                $this->option("path", dirname(__FILE__));
            }

            if(self::$config['path'] == "") {
                self::$config['path']=  $this->option("path");
            }
        }

        $full_path = $this->option("path")."/".$this->option("securityKey")."/";
        if($create_path == false && $this->checked['path'] == false) {

            if(!file_exists($full_path) || !is_writable($full_path)) {
                if(!file_exists($full_path)) {
                    @mkdir($full_path,0777);
                }
                if(!is_writable($full_path)) {
                    @chmod($full_path,0777);
                }
                if(!file_exists($full_path) || !is_writable($full_path)) {
                    DebugConsole::insert_warning('phpFastCache::getPath()', "Sorry, Please create ".$this->option("path")."/".$this->option("securityKey")."/ and SET Mode 0777 or any Writable Permission!");
                    $config_cache['use_cache'] = false;
                }
            }

            $this->checked['path'] = true;
            $this->htaccessGen($full_path);
        }

        $this->option['cachePath'] = $full_path;
        return $this->option['cachePath'];
    }
}
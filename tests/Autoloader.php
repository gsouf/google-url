<?php


class AutoLoader {
 
    static public $classNames = array();
    static public $directory = array();
 

    public static function registerDirectory($dirName) {
 
        AutoLoader::$directory=$dirName;
    }

 
    public static function loadClass($className) {
        $file=dirname(__FILE__) . DIRECTORY_SEPARATOR . AutoLoader::$directory.  str_replace("\\", DIRECTORY_SEPARATOR, $className.".php");
        if(file_exists($file))
            require_once($file);
     }
 
}
 
spl_autoload_register(array('AutoLoader', 'loadClass'));
?>

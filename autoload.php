<?php


class GoogleUrlAutoLoader {

    public static function loadClass($className) {
        $parts = explode('\\', ltrim($className,"\\"));
        if($parts[0]==="GoogleUrl"){
            
            $full = __DIR__ . "/src/" . implode("/", $parts) . ".php";
            
            
            if(file_exists($full)){
                require_once $full;
            }
            
        }
     }
 
}
 
spl_autoload_register(array('GoogleUrlAutoLoader', 'loadClass'));

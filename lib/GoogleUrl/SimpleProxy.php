<?php

namespace GoogleUrl;

/**
 * Description of SimpleProxy
 *
 * @author bob
 */
class SimpleProxy implements \GoogleUrl\SimpleProxyInterface{
   
    public $ip;
    public $port;
    
    function __construct($ip, $port) {
        $this->ip = $ip;
        $this->port = $port;
    }
    
    public function getIp() {
        return $this->ip;
    }

    public function getPort() {
        return $this->port;
    }

    public function setIp($ip) {
        $this->ip = $ip;
    }

    public function setPort($port) {
        $this->port = $port;
    }



    
}

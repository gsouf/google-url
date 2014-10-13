<?php

namespace GoogleUrl;

use GoogleUrl\Exception;

/**
 * Description of SimpleProxy
 *
 * @author bob
 */
class ProxyString implements \GoogleUrl\SimpleProxyInterface{
   
    public $ip;
    public $port;
    public $login;
    public $password;
    public $proxyType;
            
    function __construct($proxy) {
        
        $proxyPieces = explode("@", $proxy);
        
        if(count($proxyPieces) == 2){
            
            $authPieces = explode(":", $proxyPieces[0]);
            
            if(count($authPieces)>2)
                throw new Exception("Bad proxy string. Format : [user[:passsword]@]ip:port");
            
            if(!isset($authPieces[1]))
                $authPieces[1] = null;
            
            $hostPieces = explode(":", $proxyPieces[1]);
            if(count($hostPieces) !== 2){
                throw new Exception("Bad proxy string. Format : [user[:passsword]@]ip:port");
            }
            
        }else if(count($proxyPieces) == 1){
            
            $authPieces = [null,null];
            $hostPieces = explode(":", $proxyPieces[0]);
            
        }else{
            throw new Exception("Bad proxy string. Format : [user[:passsword]@]ip:port");
        }
        
        $this->ip        = $hostPieces[0];
        $this->port      = $hostPieces[1];
        $this->login     = $authPieces[0];
        $this->password  = $authPieces[1];
        
        $this->proxyType = CURLPROXY_HTTP;
    }
    
    public function getIp() {
        return $this->ip;
    }

    public function getPort() {
        return $this->port;
    }


    public function getLogin() {
        return $this->login;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setLogin($login) {
        $this->login = $login;
    }
 
    public function getProxyType() {
        return $this->proxyType;
    }

    public function setProxyType($proxyType) {
        $this->proxyType = $proxyType;
    }
    
}

<?php

namespace GoogleUrl\Proxy;

/**
 * Description of BaseProxy
 *
 * @author sghzal
 */
class StdProxy implements \GoogleUrl\ProxyInterface,  \GoogleUrl\ProxyDelayedInterface, \GoogleUrl\SimpleProxyInterface{
   
    protected $ip;
    protected $port;
    protected $lastRun;
    protected $nextDelay;
    
    protected $login;
    protected $password;
    protected $proxyType;

    
    protected $delays;
    protected $delayCount;
    protected $locked;
    
    public function __construct($ip, $port,$login,$password,$proxyType, $lastRun, $nextDelay,$delayCount,$locked) {
        $this->ip=$ip;
        $this->port=$port;
        $this->lastUse=$lastRun;
        $this->locked = $locked;
        $this->nextDelay=$nextDelay;
        $this->delaysCount = $delayCount;
        
        $this->login = $login;
        $this->password = $password;
        $this->proxyType = $proxyType;
        
    }

    public function getDelayCount() {
        return $this->delayCount;
    }

    public function getLocked() {
        return $this->getLocked();
    }

    
    public function getIp() {
        return $this->ip;
    }

    public function getPort() {
        return $this->port;
    }

    public function getTimeToAvailability() {
        return $this->lastRun + $this->nextDelay - time();
    }

    public function isAvailable() {
        return !$this->locked && ($this->getTimeToAvailability()<=0);
    }
    
    public function getDelays() {
        return $this->delays;
    }

    public function setDelays($delays) {
        $this->delays = $delays;
    }

        public function getLogin() {
        return $this->login;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getProxyType() {
        return $this->proxyType;
    }

    public function setLogin($login) {
        $this->login = $login;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function setProxyType($proxyType) {
        $this->proxyType = $proxyType;
    }

    
}

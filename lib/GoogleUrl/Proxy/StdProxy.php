<?php

namespace GoogleUrl\Proxy;

/**
 * Description of BaseProxy
 *
 * @author sghzal
 */
class StdProxy implements \GoogleUrl\ProxyInterface,  \GoogleUrl\ProxyDelayedInterface{
   
    protected $ip;
    protected $port;
    protected $lastRun;
    protected $nextDelay;
    
    protected $delays;
    protected $delayCount;
    protected $locked;
            
    function __construct($ip, $port, $lastRun, $nextDelay,$delayCount,$locked) {
        $this->ip = $ip;
        $this->port = $port;
        $this->nextDelay = $nextDelay;
        $this->lastRun = $lastRun;
        $this->delayCount = $delayCount;
        $this->locked = $locked;
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


    
}

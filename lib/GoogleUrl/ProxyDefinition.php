<?php

namespace GoogleUrl;

/**
 * Description of ProxyDefinition
 *
 * @author bob
 */
class ProxyDefinition implements ProxyInterface{

    
    protected $ip;
    protected $port;
    
    protected $lastUse;
    
    protected $delays;
    protected $nextDelay;
    protected $delaysCount;
    
    protected $cycle;
    
    protected $locked;
    
    public function getNextDelay() {
        return $this->nextDelay;
    }

    public function setNextDelay($nextDelay) {
        $this->nextDelay = $nextDelay;
    }

    public function getLocked() {
        return $this->locked;
    }

    public function setLocked($locked) {
        $this->locked = $locked;
    }

    

    public function addDelay($count,$min,$max){
        $this->delays[$count] = $min <= $max ? array($min,$max) : array($max,$min);
        ksort($this->delays);
    }
    
    public function prepareNextDelay(){
        return $this->__prepareNextDelay(true);
    }
    
    
    public function __toString() {
        return $this->ip . ":" . $this->port;
    }
    
    
    private function __prepareNextDelay($first=false){
        foreach($this->delays as $count => $range){
            if($this->delaysCount <= $count){
                $this->nextDelay = rand($range[0], $range[1]);
                $this->increaseDelayCount();
                return $this->nextDelay;
            }
        }
        
        if($first){
            $this->setDelaysCount(0);
            $this->increaseCycle();
            return $this->__prepareNextDelay();
        }else{
            throw new \Exception("Preventing endless loop : proxy delays are not correctly configured");
        }
    }
    
    public function isAvailable(){
        return $this->lastUse + $this->nextDelay <= time();
    }
    
    public function getTimeToAvailability(){
        return ($this->lastUse + $this->nextDelay) - time();
    }
    
    public function getIp() {
        return $this->ip;
    }

    public function setIp($ip) {
        $this->ip = $ip;
    }

    public function getPort() {
        return $this->port;
    }

    public function setPort($port) {
        $this->port = $port;
    }

    public function getLastUse() {
        return $this->lastUse;
    }

    public function setLastUse($lastUse) {
        $this->lastUse = $lastUse;
    }

    public function getDelays() {
        return $this->delays;
    }

    public function setDelays($delays) {
        $this->delays = $delays;
    }

    public function getDelaysCount() {
        return $this->delaysCount;
    }

    public function setDelaysCount($delaysCount) {
        $this->delaysCount = $delaysCount;
    }
    
    public function increaseDelayCount(){
        $this->delaysCount++;
    }

    public function getCycle() {
        return $this->cycle;
    }

    public function setCycle($cycle) {
        $this->cycle = $cycle;
    }

    public function increaseCycle(){
        $this->cycle++;
    }
    
    
    
}

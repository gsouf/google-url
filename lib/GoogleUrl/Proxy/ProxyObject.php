<?php

namespace GoogleUrl\Proxy;

/**
 * Description of ProxyDefinition
 *
 * @author bob
 */
class ProxyObject implements \GoogleUrl\ProxyInterface, \GoogleUrl\ProxyDelayedInterface, \GoogleUrl\SimpleProxyInterface{

    

    protected $ip;
    protected $port;
    
    protected $login;
    protected $password;
    protected $proxyType;


    protected $lastUse;
    
    protected $delays;
    protected $nextDelay;
    protected $delaysCount;
    
    protected $cycle;
    
    protected $locked;
    
    public static function fromSimpleProxy(\GoogleUrl\SimpleProxyInterface $proxy,$lastRun = 0,$nextDelay=0,$delayCount=0,$locked=false){
        
        return new static($proxy->getIp(),$proxy->getPort(),$proxy->getLogin(),$proxy->getPassword(),$proxy->getProxyType(),$lastRun, $nextDelay,$delayCount,$locked);
        
    }
    
    
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

    
    
    public function prepareNextDelay($delays){
        return $this->__prepareNextDelay($delays,true);
    }
    
    
    public function __toString() {
        return $this->ip . ":" . $this->port;
    }
    
    
    private function __prepareNextDelay($delaysDefault,$first=false){
        
        if(!$this->delays)
            $delays = $delaysDefault;
        else
            $delays = $this->delays;
        
        foreach($delays as $count => $range){
            if($count < 0 ||  $this->delaysCount <= $count){
                $this->nextDelay = rand($range[0], $range[1]);
                $this->increaseDelayCount();
                return $this->nextDelay;
            }
        }
        
        if($first){
            $this->setDelaysCount(0);
            $this->increaseCycle();
            return $this->__prepareNextDelay($delaysDefault);
        }else{
            throw new \Exception("Preventing endless loop : proxy delays are not correctly configured");
        }
    }
    
    public function getDelayCount() {
        return $this->getDelaysCount();
    }

    
    public function isAvailable(){
        return $this->lastUse + $this->nextDelay <= time();
    }
    
    public function getTimeToAvailability(){
        
        $next = ($this->lastUse + $this->nextDelay) - time();
        
        return $next > 0 ? $next : 0;
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

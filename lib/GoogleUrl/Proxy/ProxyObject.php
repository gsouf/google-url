<?php

namespace GoogleUrl\Proxy;
use GoogleUrl\SimpleProxyInterface;
use GoogleUrl\ProxyInterface;
use GoogleUrl\ProxyDelayedInterface;

/**
 * Class ProxyObject
 * @author bob
 * @package GoogleUrl\Proxy
 */
class ProxyObject implements ProxyInterface, ProxyDelayedInterface, SimpleProxyInterface {


    /**
     * @var
     */
    protected $ip;
    /**
     * @var
     */
    protected $port;

    /**
     * @var
     */
    protected $login;
    /**
     * @var
     */
    protected $password;
    /**
     * @var
     */
    protected $proxyType;


    /**
     * @var
     */
    protected $lastUse;

    /**
     * @var
     */
    protected $delays;
    /**
     * @var
     */
    protected $nextDelay;
    /**
     * @var
     */
    protected $delaysCount;

    /**
     * @var
     */
    protected $cycle;

    /**
     * @var
     */
    protected $locked;

    /**
     * @param SimpleProxyInterface $proxy
     * @param int $lastRun
     * @param int $nextDelay
     * @param int $delayCount
     * @param bool $locked
     * @return static
     */
    public static function fromSimpleProxy(SimpleProxyInterface $proxy,$lastRun = 0,$nextDelay=0,$delayCount=0,$locked=false){
        
        return new static($proxy->getIp(),$proxy->getPort(),$proxy->getLogin(),$proxy->getPassword(),$proxy->getProxyType(),$lastRun, $nextDelay,$delayCount,$locked);
        
    }

    /**
     * @param $ip
     * @param $port
     * @param $login
     * @param $password
     * @param $proxyType
     * @param $lastRun
     * @param $nextDelay
     * @param $delayCount
     * @param $locked
     */
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


    /**
     * @return mixed
     */
    public function getNextDelay() {
        return $this->nextDelay;
    }

    /**
     * @param $nextDelay
     */
    public function setNextDelay($nextDelay) {
        $this->nextDelay = $nextDelay;
    }

    /**
     * @return mixed
     */
    public function getLocked() {
        return $this->locked;
    }

    /**
     * @param $locked
     */
    public function setLocked($locked) {
        $this->locked = $locked;
    }

    /**
     * @param $delays
     * @return int
     * @throws \Exception
     */
    public function prepareNextDelay($delays){
        return $this->__prepareNextDelay($delays,true);
    }


    /**
     * @return string
     */
    public function __toString() {
        return $this->ip . ":" . $this->port;
    }


    /**
     * @param $delaysDefault
     * @param bool $first
     * @return int
     * @throws \Exception
     */
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

    /**
     * @return mixed
     */
    public function getDelayCount() {
        return $this->getDelaysCount();
    }

    /**
     * @return bool
     */
    public function isAvailable(){
        return $this->lastUse + $this->nextDelay <= time();
    }

    /**
     * @return int
     */
    public function getTimeToAvailability(){
        
        $next = ($this->lastUse + $this->nextDelay) - time();
        
        return $next > 0 ? $next : 0;
    }

    /**
     * @return mixed
     */
    public function getIp() {
        return $this->ip;
    }

    /**
     * @param $ip
     */
    public function setIp($ip) {
        $this->ip = $ip;
    }

    /**
     * @return mixed
     */
    public function getPort() {
        return $this->port;
    }

    /**
     * @param $port
     */
    public function setPort($port) {
        $this->port = $port;
    }

    /**
     * @return mixed
     */
    public function getLogin() {
        return $this->login;
    }

    /**
     * @return mixed
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * @return mixed
     */
    public function getProxyType() {
        return $this->proxyType;
    }

    /**
     * @param $login
     */
    public function setLogin($login) {
        $this->login = $login;
    }

    /**
     * @param $password
     */
    public function setPassword($password) {
        $this->password = $password;
    }

    /**
     * @param $proxyType
     */
    public function setProxyType($proxyType) {
        $this->proxyType = $proxyType;
    }


    /**
     * @return mixed
     */
    public function getLastUse() {
        return $this->lastUse;
    }

    /**
     * @param $lastUse
     */
    public function setLastUse($lastUse) {
        $this->lastUse = $lastUse;
    }

    /**
     * @return mixed
     */
    public function getDelays() {
        return $this->delays;
    }

    /**
     * @param $delays
     */
    public function setDelays($delays) {
        $this->delays = $delays;
    }

    /**
     * @return mixed
     */
    public function getDelaysCount() {
        return $this->delaysCount;
    }

    /**
     * @param $delaysCount
     */
    public function setDelaysCount($delaysCount) {
        $this->delaysCount = $delaysCount;
    }

    /**
     * Increasing Delay Cycle
     */
    public function increaseDelayCount(){
        $this->delaysCount++;
    }

    /**
     * @return mixed
     */
    public function getCycle() {
        return $this->cycle;
    }

    /**
     * @param $cycle
     */
    public function setCycle($cycle) {
        $this->cycle = $cycle;
    }

    /**
     * Increase Cycle
     */
    public function increaseCycle(){
        $this->cycle++;
    }
}

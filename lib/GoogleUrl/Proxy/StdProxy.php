<?php

namespace GoogleUrl\Proxy;

use GoogleUrl\ProxyInterface;
use GoogleUrl\ProxyDelayedInterface;
use GoogleUrl\SimpleProxyInterface;

/**
 * Description of BaseProxy
 *
 * Class StdProxy
 * @author sghzal
 * @package GoogleUrl\Proxy
 */
class StdProxy implements ProxyInterface,  ProxyDelayedInterface, SimpleProxyInterface{

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
    protected $lastRun;
    /**
     * @var
     */
    protected $nextDelay;

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
    protected $delays;
    /**
     * @var
     */
    protected $delayCount;
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
    public function getDelayCount() {
        return $this->delayCount;
    }

    /**
     * @return mixed
     */
    public function getLocked() {
        return $this->getLocked();
    }


    /**
     * @return mixed
     */
    public function getIp() {
        return $this->ip;
    }

    /**
     * @return mixed
     */
    public function getPort() {
        return $this->port;
    }

    /**
     * @return int
     */
    public function getTimeToAvailability() {
        $next = ($this->lastUse + $this->nextDelay) - time();
        
        return $next > 0 ? $next : 0;
    }

    /**
     * @return bool
     */
    public function isAvailable() {
        return !$this->locked && ($this->getTimeToAvailability()<=0);
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
}

<?php

namespace GoogleUrl;

/**
 * Description of ProxyPool
 *
 * @author bob
 */
class ProxyPool implements ProxyAccessAdapter {
    
    /**
     *
     * @var ProxyDefinition[]
     */
    protected $proxys;
    
    protected $delays;
    
    /**
     * the delays to use by default if the proxy doesnt have
     * @param array $delays
     */
    function __construct($delays) {
        $this->delays = $delays;
    }

    
    /**
     * Add a proxy. It must be an instance of {@see ProxyDefinition}
     * @param \GoogleUrl\ProxyInterface $p
     * @throws \Exception
     */
    public function setProxy(ProxyInterface $p){
        
        if(!$p instanceof Proxy\ProxyObject){
            throw new \Exception("ProxyPool::addProxy() first parameter must be an instance of GoogleUrl\Proxy\ProxyObject." . get_class($p) . " used instead.");
        }
        
        $this->proxys[$p->__toString()] = $p;
    }
    
    
    /**
     * check if the given proxy exists
     * @param \GoogleUrl\SimpleProxyInterface $p
     */
    public function hasProxy(SimpleProxyInterface $p) {
        
        return isset($this->proxys[$p->getIp() . ":" . $p->getPort()]);
        
    }

    

    public function removeProxy(ProxyInterface $proxy) {
    
        $string = $proxy->getIp() . ":" . $proxy->getPort();
        
        if(isset($this->proxys[$string])){
            unset($this->proxys[$string]);
        }
    }

    
    public function findShortestTimeProxy(){
        $finalProxy = null;
        foreach($this->proxys as $p){
            if(!$finalProxy){
                $finalProxy = $p;
            }else{
                if($p->getTimeToAvailability() < $finalProxy->getTimeToAvailability()){
                    $finalProxy = $p;
                }
            }
        }
        return $finalProxy;
    }
    
    public function findAvailableProxy() {
        foreach($this->proxys as $p){
            $avail = $p->isAvailable();
            if($avail){
                return $p;
            }
        }
        return null;
    }

    public function proxyUsed(ProxyInterface $proxy) {
        $string = $proxy->getIp() . ":" . $proxy->getPort();
        
        
        if(isset($this->proxys[$string])){
           $p = $this->proxys[$string];
           $p->setLastUse(time());
           $p->prepareNextDelay($this->delays);
        echo "updating proxy : $proxy. Setting count : " . $p->getDelaysCount() ;
        echo " cycle : " . $p->getCycle() ;
        echo " nextDelay : " . $p->getNextDelay() ;
           
        }
    }

    public function acquireProxyLock(ProxyInterface $proxy) {
        $string = $proxy->getIp() . ":" . $proxy->getPort();
        
        if(isset($this->proxys[$string])){
           $p = $this->proxys[$string];
           $p->setLocked(true);
        }
    }

    public function releaseProxyLock(ProxyInterface $proxy) {
        $string = $proxy->getIp() . ":" . $proxy->getPort();
        
        if(isset($this->proxys[$string])){
           $p = $this->proxys[$string];
           $p->setLocked(false);
        }
    }

    
}

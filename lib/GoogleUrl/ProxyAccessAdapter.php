<?php

namespace GoogleUrl;

use GoogleUrl\ProxyInterface;

/**
 * Description of ProxyPool
 *
 * @author bob
 */
interface ProxyAccessAdapter {
    
    /**
     * Find the proxy with the shortest time for next usage
     * 
     */
    public function findShortestTimeProxy();
    
    /**
     * Find a proxy that is available for querying now
     */
    public function findAvailableProxy();
    
    /**
     * 
     * @param \GoogleUrl\ProxyInterface $proxy
     */
    public function acquireProxyLock(ProxyInterface $proxy);
    public function releaseProxyLock(ProxyInterface $proxy);
    public function proxyUsed(ProxyInterface $proxy);
    
    public function setProxy(ProxyInterface $proxy);
    public function removeProxy(ProxyInterface $proxy);
    
    public function hasProxy(SimpleProxyInterface $p);
    
}

<?php

namespace GoogleUrl;

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

    /**
     * @param ProxyInterface $proxy
     * @return mixed
     */
    public function releaseProxyLock(ProxyInterface $proxy);

    /**
     * @param ProxyInterface $proxy
     * @return mixed
     */
    public function proxyUsed(ProxyInterface $proxy);

    /**
     * @param ProxyInterface $proxy
     * @return mixed
     */
    public function setProxy(ProxyInterface $proxy);

    /**
     * @param ProxyInterface $proxy
     * @return mixed
     */
    public function removeProxy(ProxyInterface $proxy);

    /**
     * @param SimpleProxyInterface $p
     * @return mixed
     */
    public function hasProxy(SimpleProxyInterface $p);
    
}

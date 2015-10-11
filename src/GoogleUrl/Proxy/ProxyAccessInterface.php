<?php

namespace GoogleUrl\Proxy;

use GoogleUrl\Proxy\ProxyInterface;

/**
 * Defines how to write and read proxies, how to lock them, etc...
 *
 * It aims to be implemented to store the stats in file, in database, in memory... to make proxies sharable
 * between many scripts
 */
interface ProxyAccessInterface
{
    
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
     * Lock a proxy. A locked proxy wont be available for findAvailableProxy() method
     *
     * Locking a proxy is necessary to avoid two request to be done at the same time with a proxy
     * @param ProxyInterface $proxy
     */
    public function acquireProxyLock(ProxyInterface $proxy);

    /**
     * Release the lock of the given proxy
     * @param ProxyInterface $proxy
     * @return mixed
     */
    public function releaseProxyLock(ProxyInterface $proxy);

    /**
     * Register an usage for the proxy
     *
     * That basically sets the time it was used for the last time in order to know when the delay
     * to use it again will be passed
     *
     * @param ProxyInterface $proxy
     * @return mixed
     */
    public function proxyRegisterUsage(ProxyInterface $proxy);

    /**
     * Add or update a proxy to the list
     * @param ProxyInterface $proxy
     * @return mixed
     */
    public function setProxy(ProxyInterface $proxy);

    /**
     * Remove a proxy
     * @param ProxyInterface $proxy
     * @return mixed
     */
    public function removeProxy(ProxyInterface $proxy);

    /**
     * Checks if the proxy exists
     * @param SimpleProxyInterface $p
     * @return mixed
     */
    public function hasProxy(SimpleProxyInterface $p);

    /**
     * Get all the proxies
     * @return ProxyInterface[]
     */
    public function getProxies();

    /**
     * get a proxy by either its id (ip:port) or from a proxy instance
     * @param String|SimpleProxyInterface $proxy
     * @return ProxyInterface
     */
    public function getProxy($proxy);
}

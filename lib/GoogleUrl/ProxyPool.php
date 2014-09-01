<?php

namespace GoogleUrl;

/**
 * Description of ProxyPool
 *
 * @author bob
 */
class ProxyPool {
    
    /**
     *
     * @var ProxyAccessAdapter
     */
    protected $proxyAccessAdapter;
    
    function __construct(ProxyAccessAdapter $proxyAccessAdapter) {
        $this->proxyAccessAdapter = $proxyAccessAdapter;
    }

    
    
}

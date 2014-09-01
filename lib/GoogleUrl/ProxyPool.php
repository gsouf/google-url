<?php

namespace GoogleUrl;

/**
 * Description of ProxyPool
 *
 * @author bob
 */
class ProxyPool implements ProxyAccessAdapter {
    
    protected $proxys;

    /**
     *
     * @var ProxyAccessAdapter
     */
    protected $proxyAccessAdapter;
    
    function __construct(ProxyAccessAdapter $proxyAccessAdapter) {
        $this->proxyAccessAdapter = $proxyAccessAdapter;
    }

    public function findShortestTimeProxy(){
        
    }
    
    
}

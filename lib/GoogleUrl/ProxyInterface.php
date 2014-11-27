<?php

/**
 * @copyright (c) Rock A Gogo VPC
 */

namespace GoogleUrl;

/**
 * ProxyInterface
 *
 * @author sghzal
 */
interface ProxyInterface {
    
    function __construct($ip, $port,$login,$password,$proxyType, $lastRun, $nextDelay,$delayCount,$locked);
    
    public function getIp();
    public function getPort();
    public function getDelayCount();
    public function getLocked();
    public function getTimeToAvailability();
    public function isAvailable();
    
    public function getLogin();
    public function getPassword();
    
    public function getProxyType();
    
}
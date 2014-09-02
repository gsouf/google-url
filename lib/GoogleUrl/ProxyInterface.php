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
    
    public function getIp();
    public function getPort();
    public function getTimeToAvailability();
    public function isAvailable();
    
}
<?php

/**
 * @copyright (c) Rock A Gogo VPC
 */

namespace GoogleUrl\Proxy;

/**
 * This extends SimpleProxy to add stateful informations to the proxy (proxy is locked,  last proxy usage...)
 */
interface ProxyInterface extends SimpleProxyInterface
{

    public function getDelayCount();
    public function setLocked($locked);
    public function setLastUse($time);
    public function getLocked();
    public function getTimeToAvailability();
    public function isAvailable();

    /**
     * the proxy will find the next delay to apply
     */
    public function prepareNextDelay($delays);
}

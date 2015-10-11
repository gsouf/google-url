<?php
/**
 * @license see LICENSE
 */

namespace GoogleUrl\Proxy;


interface ProxyDelayedInterface {

    public function getDelays();
    public function setDelays($delays);

}
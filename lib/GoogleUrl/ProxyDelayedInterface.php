<?php

namespace GoogleUrl;

/**
 * Interface ProxyDelayedInterface
 * @package GoogleUrl
 * @author bob
 */
interface ProxyDelayedInterface {

    /**
     * @return mixed
     */
    public function getDelays();

    /**
     * @param $delays
     * @return mixed
     */
    public function setDelays($delays);
    
}

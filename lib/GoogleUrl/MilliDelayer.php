<?php

namespace GoogleUrl;

/**
 * 
 * When @see Delayer will wait for seconds, MicroDelayer will wait for milliseconds
 * 
 * @author sghzal
 * 
 */
class MilliDelayer extends Delayer {
    
    protected function _wait($time) {
        usleep($time*1000);
    }

    
}
<?php

namespace GoogleUrl;

/**
 * When @see Delayer will wait for seconds, MicroDelayer will wait for milliseconds
 *
 * Class MilliDelayer
 * @package GoogleUrl
 * @author sghzal
 */
class MilliDelayer extends Delayer {

    /**
     * @param int $time
     */
    protected function _wait($time) {
        usleep($time*1000);
    }

}
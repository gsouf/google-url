<?php

namespace GoogleUrl;


use Peek\Time\MilliDelayer;

/**
 * Allows to do google searches with safe delays
 *
 * You can use it as a GoogleUrl, but think that a delay is set between each google request that will extend the script run time
 *
 *
 * Class DelayedQuery
 * @package GoogleURL
 */
class DelayedQuery extends \GoogleUrl {

    protected static $delayer=null;

    public function __construct(){
        parent::__construct();
        if(self::$delayer==null)
            self::$delayer = new MilliDelayer(300,300,800,300,800,1000,800,1000,1500,800,1000,1500,500,800,2000,800,1000,1500,500,800,2000,8000,800,300,1500,300,500,10000);
    }

    public function search($searchTerm = null)
    {
        self::$delayer->wait();
        return parent::search($searchTerm);
    }


}
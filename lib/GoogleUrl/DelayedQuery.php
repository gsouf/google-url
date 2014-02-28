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
            self::$delayer = new MilliDelayer(20000,50000,60000,20000,120000,20000,40000,60000,20000,180000);
    }

    public function search($searchTerm = null)
    {
        self::$delayer->wait();
        try{
            return parent::search($searchTerm);
        }catch (\Exception $e){
            throw $e;
        }
    }


}
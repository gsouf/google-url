<?php

namespace GoogleUrl;


use GoogleUrl\MilliDelayer;

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
    protected $started=false;

    public function __construct(){
        parent::__construct();
        if(self::$delayer==null)
            self::$delayer = new MilliDelayer(50020,70023,80084,40096,150032,50150,70007,180000);
    }

    public function search($searchTerm = null )
    {
        // dont wait for the first iteration
        if($this->started)
            self::$delayer->wait();
        else{
            $this->started = true;
        }

        try{
            return parent::search($searchTerm);
        }catch (\Exception $e){
            throw $e;
        }
    }


}

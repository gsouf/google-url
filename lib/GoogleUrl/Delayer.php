<?php

namespace GoogleUrl;

/**
 * Delayer allow to define one or more delays.
 * When you wall wait method intenral iterot will move to next delay and wait for this delay
 * 
 * Usefull cases :
 *  + You need to query a lot on a server, but you dont want to be considered as a bot, then set random delays beetwin each query
 *  + You need to send mails but you dont want to be blacklisted for spam, set delays beetwin each.
 *  
 *  
 * 
 * @author sghzal
 * 
 */
class Delayer {
    
    /**
     * iterator containing the time delays
     * @var \ArrayIterator
     */
    protected $it;
    

    /**
     * construct a new delayer with the given delays
     * @param int[] $delays list of delays. Can also give directly  a list of int : new Delayer(1,1,2,1,3)
     */
    public function __construct($delays) {
        $this->it=new \ArrayIterator();
        
        if(!is_array($delays))
            $delays=func_get_args();
        
        $this->setDelays($delays);
    }
    
    /**
     * change the delays of the delayer with the given delays
     * @param int[] $delays list of delays. Can also give directly  a list of int : new Delayer(1,1,2,1,3)
     * @throws Exception
     */
    public function setDelays($delays){
        
        if(!is_array($delays))
            $delays=func_get_args();
        
        if(0 === count($delays))
            throw new Exception ("Any delay given. If you want no delay, you can use \$delayer->setDelays(0) ");


        foreach($delays as $time){
            if(!ctype_digit($time) || 0 > intval($time))
                throw new Exception("Given time : '".$time."' is not a valid, positive integer");      
        }
        
        $this->it=new \ArrayIterator($delays);
    }
    
    /**
     * wait for the current delay then move to the next delay. Comes back to the first delay when end is reached.
     * 
     * The following exemple will wait 1s, 2s, 1s, 3s, comes back at early 1s, 2s....  :
     * 
     * $delayer=new Delayer(1,2,1,3);
     * while(true)
     *      $delayer->wait();
     */
    public function wait(){
        
        $it=$this->it;

        $this->_wait($it->current());
        $it->next();
        
        if(!$it->valid())
            $it->rewind();
        
    }
    
    /**
     * how to wait. Usefull for implementation. e.g. for microwait in MicroDelayer
     * @param int $time time to wait
     */
    protected function _wait($time){
        sleep($time);
    }
    
}
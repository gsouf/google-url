<?php


class ProxyTest  extends PHPUnit_Framework_TestCase{
    
   
    /**
     * @group proxy
     */
    public function testProxyQuery(){
        
        $delays = array(
            
            2 => array(1,1),
            3 => array(2,3)
            
        );
        
        $proxyDefinition1 = new GoogleUrl\ProxyDefinition();
        $proxyDefinition1->setDelays($delays);
        $proxyDefinition1->setCycle(0);
        $proxyDefinition1->setDelaysCount(0);
        $proxyDefinition1->setIp("23.21.183.183");
        $proxyDefinition1->setPort(80);
        $proxyDefinition1->setLastUse(0);
        
        $proxyDefinition2 = new GoogleUrl\ProxyDefinition();
        $proxyDefinition2->setDelays($delays);
        $proxyDefinition2->setCycle(0);
        $proxyDefinition2->setDelaysCount(0);
        $proxyDefinition2->setIp("23.21.183.183");
        $proxyDefinition2->setPort(82);
        $proxyDefinition2->setLastUse(0);
        
        
        $pool = new \GoogleUrl\ProxyPool();
        $pool->addProxy($proxyDefinition1);
        $pool->addProxy($proxyDefinition2);

        
        for($i=0;$i<20;$i++){

            $proxy = $pool->findAvailableProxy();

            echo PHP_EOL;
            echo PHP_EOL;
            echo 'Search an available proxy' . PHP_EOL;
            if(!$proxy){

                echo "== no proxy available for now. Searching the lowest delay..." . PHP_EOL;

                $proxy = $pool->findShortestTimeProxy();

                if(!$proxy){
                    throw new \Exception("No proxy");
                }

                $time = $proxy->getTimeToAvailability();
                
                sleep($time);

                echo " found ! Sleeping for $time seconds" . PHP_EOL;

            }

            echo "Querying with proxy : $proxy" . PHP_EOL;

            $pool->acquireProxyLock($proxy);
            // $googleUrl->search($kw,$proxy);
            $pool->releaseProxyLock($proxy);
            $pool->proxyUsed($proxy);
        }
        
        
        
        
    }
    
}
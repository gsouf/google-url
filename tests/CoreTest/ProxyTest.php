<?php


class ProxyTest  extends PHPUnit_Framework_TestCase{
    

    public function testProxyQuery(){
        
        $delays = array(
            
            2 => array(1,1),
            3 => array(2,3)
            
        );
        
        $proxyDefinition1 = new GoogleUrl\Proxy\ProxyObject("23.21.183.183", 82 , null, null ,null, 0, 0,0,false);
        $proxyDefinition1->setDelays($delays);
        
        $proxyDefinition2 = new GoogleUrl\Proxy\ProxyObject("23.21.183.183", 80, null, null ,null, 0, 0,0,false);
        $proxyDefinition2->setDelays($delays);
        
        
        $pool = new \GoogleUrl\ProxyPool([-1=>array(0,1)]);
        $pool->setProxy($proxyDefinition1);
        $pool->setProxy($proxyDefinition2);

        
        for($i=0;$i<20;$i++){

            $proxy = $pool->findAvailableProxy();

            echo PHP_EOL;
            echo PHP_EOL;
            echo 'Searching an available proxy' . PHP_EOL;
            if(!$proxy){

                echo "== no proxy available for now. Searching the lowest delay..." . PHP_EOL;

                $proxy = $pool->findShortestTimeProxy();

                if(!$proxy){
                    throw new \Exception("No proxy");
                }

                $time = $proxy->getTimeToAvailability();
                
                usleep($time);

                echo " found ! Sleeping for $time seconds" . PHP_EOL;

            }

            echo "Querying with proxy : $proxy" . PHP_EOL;

            $pool->acquireProxyLock($proxy);
            // $googleUrl->search($kw,$proxy);
            $pool->releaseProxyLock($proxy);
            $pool->proxyUsed($proxy);
        }
    }
    
    public function testProxyString(){
        
        $p = new GoogleUrl\ProxyString("192.168.1.2:2222");
        
        $this->assertEquals("192.168.1.2",$p->getIp());
        $this->assertEquals("2222",$p->getPort());
        
        
        $p = new GoogleUrl\ProxyString("user@192.168.1.2:2222");
        
        $this->assertEquals("192.168.1.2",$p->getIp());
        $this->assertEquals("2222",$p->getPort());
        $this->assertEquals("user",$p->getLogin());
        
        
        $p = new GoogleUrl\ProxyString("user:pswd@192.168.1.2:2222");
        
        $this->assertEquals("192.168.1.2",$p->getIp());
        $this->assertEquals("2222",$p->getPort());
        $this->assertEquals("user",$p->getLogin());
        $this->assertEquals("pswd",$p->getPassword());
        
    }


    public function testProxyFile(){
        
        echo PHP_EOL;
        echo PHP_EOL;
        echo PHP_EOL;
        echo PHP_EOL;
        echo PHP_EOL;
        echo PHP_EOL;
        
        $delays = array(
            
            2 => array(1,1),
            3 => array(2,3)
            
        );
        
        $proxyDefinition1 = new GoogleUrl\Proxy\StdProxy("23.21.183.183", 82,null, null ,null, 0, 0,0,false);
        $proxyDefinition1->setDelays($delays);
        
        $proxyDefinition2 = new GoogleUrl\Proxy\StdProxy("23.21.183.183", 80,null, null ,null, 0, 0,0,false);
        $proxyDefinition2->setDelays($delays);
        
        
        $pool = new GoogleUrl\ProxyPool\File(__DIR__ . "/../data/proxies.json",$delays);
        $pool->setProxy($proxyDefinition1);
        $pool->setProxy($proxyDefinition2);

        
        for($i=0;$i<20;$i++){

            $proxy = $pool->findAvailableProxy();

            echo PHP_EOL;
            echo PHP_EOL;
            echo 'Searching an available proxy' . PHP_EOL;
            if(!$proxy){

                echo "== no proxy available for now. Searching the lowest delay..." . PHP_EOL;

                $proxy = $pool->findShortestTimeProxy();

                if(!$proxy){
                    throw new \Exception("No proxy");
                }

                $time = $proxy->getTimeToAvailability();
                
                usleep($time);

                echo " found ! Sleeping for $time seconds" . PHP_EOL;

            }

            echo "Querying with proxy : " . $proxy->getIp() . ":" . $proxy->getPort() . PHP_EOL;

            $pool->acquireProxyLock($proxy);
            // $googleUrl->search($kw,$proxy);
            $pool->releaseProxyLock($proxy);
            $pool->proxyUsed($proxy);
        }
        

    }
    
}
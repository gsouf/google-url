Proxy Usage
===========

Basic proxy usage
-----------------


Basically we just have to passe a proxy to the query to use it :

```php

    <?php

    include __DIR__ . "/autoload.php";


    // Create 2 proxy with ip/address and port
    $proxy1 = new \GoogleUrl\SimpleProxy("localhost", "3128");
    $proxy2 = new \GoogleUrl\SimpleProxy("someproxyAddress", "8080");

    // use them for the query
    $googleUrl=new \GoogleUrl();
    $googleUrl->setLang('fr')->setNumberResults(10)->search("simpson",$proxy1);
    $googleUrl->setLang('fr')->setNumberResults(10)->search("simpsons",$proxy2);

```

This is the most basic case of using a proxy.


Proxy Rotation
--------------

What we are interested in is to rotate between **many proxys**.
The following example shows how it is possible with ``googleUrl's ProxyPool``.

The following example shows a basical use of the proxy. You may modify it to use your **keywords**
(from database ?), choosing how to **handle errors** and how to **store results**.


```php

    <?php

    include __DIR__ . "/../autoload.php";


    // create default delays for the proxypool
    $defaultDelays = array(
        -1 => array(60,120) // every request will wait randomly from 60 to 120 seconds
    );


    // create a proxy pool with default delays for the proxys
    $proxyPool = new \GoogleUrl\ProxyPool($defaultDelays);

    // creates a proxy with options :
    // - ip     : 20.20.183.183
    // - port   : 80
    // - last use (last time the proxy has been used) : 0 (unix timestamp)
    // - next delay (time to wait before next query) : 0 seconds (we wont wait before next query)  | It is used to know if the proxy can be run since the last run
    // - request count (the number of request that the proxy as already performed) : 0             | It is used to know what delay to apply from the delays array
    // - locked : false     (the proxy mus tbe locked when a request is being performed with it)
    //
    // We dont define any delays for this proxy. Then it is going to use default delays of the ProxyPool
    //
    $proxy1 = new GoogleUrl\Proxy\ProxyObject("20.20.183.183", 82, 0, 0,0,false);

    // creates a 2d proxy on the ip 20.20.183.184 port 8080
    $proxy2 = new GoogleUrl\Proxy\ProxyObject("20.20.183.184", 8080, 0, 0,0,false);

    // set delays for this 2d proxy. Thus it will use theses delays instead of the one set in the proxyPool
    $proxy2->setDelays(array(

        2   => array(5,10),  // the requests 0->2 with this proxy will wait randomly from 5 to 10 sec (delays are 0 indexed)
        5   => array(15,20), // the requests 3->5 with this proxy will wait randomly from 15 to 20 sec
        10  => array(25,35), // the requests 6->10 with this proxy will wait randomly from 25 to 35 sec
        20  => array(40,55), // the requests 11->20 with this proxy will wait randomly from 40 to 55 sec
        40  => array(60,80), // the requests 21->40 with this proxy will wait randomly from 60 to 80 sec
        100 => array(60,120) // the requests 41->100 with this proxy will wait randomly from 60 to 120 sec
        // then we reset the counter and we go back to 0
        // instead we could have used    -1 => array(60,120)    for no counter reset
        // -1 is a kind of endless counter

    ));


    // add the 2 proxys to the proxy pool
    $proxyPool->setProxy($proxy1);
    $proxyPool->setProxy($proxy2);


    // this is the searcher we will use with a proxy
    $searcher = new GoogleUrl();
    $searcher->setLang('fr')->setNumberResults(10);

    // list of keywords that we want to parse
    $keywords = array("simpson","tshirt simpson","homer");


    // we loop until every keywords are parsed
    do{

        // the keyword to search
        $keyword = current($keywords);


        echo "Searching an available proxy" . PHP_EOL;

        // we search an available proxy (a proxy that is neither locked nor waiting for a delay)
        $proxy = $proxyPool->findAvailableProxy();

        // this case mean that all proxy are locked or under delays, then we are going to wait
        if( !$proxy ){
            echo "No proxy available. Searching for the one with the lowest delay" . PHP_EOL;

            // we search the proxy with the shortest delay for the next use
            // locked proxy are excluded from this search
            $proxy = $proxyPool->findShortestTimeProxy();

            if( !$proxy ){ 
                // there is no available proxy
                throw new \Exception("No proxy available");
            }

            echo "Proxy found : " . $proxy->getIp() . ":" . $proxy->getPort() . PHP_EOL;

            // we find the time that the proxy must wait for the next query
            $time = $proxy->getTimeToAvailability();

            // we lock the proxy for the time of the request
            // I explain latter why this is usefull
            $proxyPool->acquireProxyLock($proxy);
            
            echo "Waiting for $time seconds for the proxy to be available" . PHP_EOL;
            // we sleep to wait for the proxy to be available
            sleep($time);       
            
        }

        try{
            
                

                echo "Querying keyword $keyword with the proxy " . $proxy->getIp() . ":" . $proxy->getPort() . PHP_EOL;

                // start the search
                $searchResult = $searcher->search($keyword,$proxy);

                // unlock the proxy
                $proxyPool->releaseProxyLock($proxy);

                // register the use of the proxy 
                // This increases the count, assign the next delays etc...
                $proxyPool->proxyUsed($proxy);

                echo "Parsing Search resulsts" . PHP_EOL;

                // Do some actions with $searchResult
                $positions = $searchResult->getPositions();
                // ...
                // ...
                // ...
                // ...
                // ...
                // ...
                // ...

                // go to the next keyword
                next($keywords);

        } 

        // the proxy is badly configured
        catch (\GoogleUrl\Exception\ProxyException $ex) {

            echo $ex->getMessage();

            // remove the proxy from the pool
            $proxyPool->removeProxy($proxy);

        } 

        // We have met the google captcha. We will have to update delays
        catch (\GoogleUrl\Exception\CaptchaException $ex){

            echo $ex->getMessage();
            // we may remove the proxy and create an alert
            // unlock the proxy
            $proxyPool->releaseProxyLock($proxy);

        } 

        // there was a network error, maybe the network is down ?
        catch (\GoogleUrl\Exception\CurlException $ex){

            echo $ex->getMessage();
            // we may remove the proxy and create an alert
            // unlock the proxy
            $proxyPool->releaseProxyLock($proxy);

        }

        echo PHP_EOL . PHP_EOL;

    }while($keyword !== false);

```
    


This examples is quite a few complet because it **locks proxys** with ``$proxypool->acquireProxyLock($proxy)``. 

In the previous example it is useless because proxy state are **not stored**. In the next example proxy states are **stored into a file**.

Why is it usefull :

 * If your script **crashes** during the execution, you can start it again and because the state of proxies are stored in a file then it is easy to restore it.
 * You can ask the file to know the state of the proxies **without stopping the script execution**,
 * You can start **many scripts with same proxies** and the proxy will **never collide**
 * Using it in a multithreaded application
 


Using a file to store proxy state
---------------------------------

In this example we are using a **file to store proxy** and make proxy state tolerant to crashes or multiscript usage.

This exemples contains 2 scripts. First will init proxies. Second will rotate over the proxies

These example are close enough from the previous one. Only new actions are documented

**init.php**

> it sets the basic stat of the proxies

```php
   
    <?php 
    include __DIR__ . "/../autoload.php";


    $defaultDelays = array(
        -1 => array(60,120) // every request will wait randomly from 60 to 120 seconds
    );

    $proxy1 = new GoogleUrl\Proxy\ProxyObject("20.20.183.183", 82, 0, 0,0,false);
    $proxy2 = new GoogleUrl\Proxy\ProxyObject("20.20.183.184", 8080, 0, 0,0,false);

    // instead of using ProxyPool we use \GoogleUrl\ProxyPool\File the usage are identical what changes :
    // - Constructor are different : \GoogleUrl\ProxyPool\File takes path to an existing and writable file.
    // - The \GoogleUrl\ProxyPool\File will read/write the file in order to store/restore states of proxies
    // Be aware that the file you give will be read and written. It will erase its content
    $proxyPool = new \GoogleUrl\ProxyPool\File(__DIR__ . "/path/to/file.json",$defaultDelays);
    
    // This adds the proxies to the pool and write them into the file
    $proxyPool->setProxy($proxy1);
    $proxyPool->setProxy($proxy2);
    
```
    

**proxy-rotation.php**

> it rotates with the previously created proxies

```php
   
    <?php 
    include __DIR__ . "/../autoload.php";

    // this pool starts with the prvious states of the proxies. No matters when you start the script.
    $proxyPool = new \GoogleUrl\ProxyPool\File(__DIR__ . "/path/to/file.json",$defaultDelays);

    $searcher = new GoogleUrl();
    $searcher->setLang('fr')->setNumberResults(10);

    $keywords = array("simpson","tshirt simpson","homer");

    do{

        $keyword = current($keywords);

        echo "Searching an available proxy" . PHP_EOL;

        // this reads the file to find an available proxy
        $proxy = $proxyPool->findAvailableProxy();

        if( !$proxy ){
            echo "No proxy available. Searching for the one with the lowest delay" . PHP_EOL;

            // This reads the file to find the proxy with shortest time
            $proxy = $proxyPool->findShortestTimeProxy();

            if( !$proxy ){ 
                throw new \Exception("No proxy available");
            }

            echo "Proxy found : " . $proxy->getIp() . ":" . $proxy->getPort() . PHP_EOL;

            $time = $proxy->getTimeToAvailability();

            // this writes the file to lock the proxy
            $proxyPool->acquireProxyLock($proxy);
            
            echo "Waiting for $time seconds for the proxy to be available" . PHP_EOL;
            sleep($time);       
            
        }

        try{
            
                

                echo "Querying keyword $keyword with the proxy " . $proxy->getIp() . ":" . $proxy->getPort() . PHP_EOL;


                $searchResult = $searcher->search($keyword,$proxy);

                // this writes unlock the proxy
                $proxyPool->releaseProxyLock($proxy);

                // This write the file to add to update current count and next delay
                $proxyPool->proxyUsed($proxy);

                echo "Parsing Search resulsts" . PHP_EOL;

                // Do some actions with $searchResult
                $positions = $searchResult->getPositions();
                // ...
                // ...
                // ...
                // ...
                // ...
                // ...
                // ...

                next($keywords);

        } 

        catch (\GoogleUrl\Exception\ProxyException $ex) {

            echo $ex->getMessage();

            // this write the file to delete the proxy
            $proxyPool->removeProxy($proxy);

        } 

        catch (\GoogleUrl\Exception\CaptchaException $ex){

            echo $ex->getMessage();
            // this writes unlock the proxy
            $proxyPool->releaseProxyLock($proxy);

        } 

        // there was a network error, maybe the network is down ?
        catch (\GoogleUrl\Exception\CurlException $ex){

            echo $ex->getMessage();
            // this writes unlock the proxy
            $proxyPool->releaseProxyLock($proxy);

        }

        echo PHP_EOL . PHP_EOL;

    }while($keyword !== false);
    
 
```
    


Usefull Informations
--------------------

* Proxies are identified by ip:port. You can't use two proxy with the same identity in a proxyPool. The latest will replace the oldes.



More Imeplementation
--------------------

Right now only ProxyPool (that stores proxies localy in the script) and ProxyPool\File (that stores proxies in a file) are implemented. More are implementable and will be implemented (e.g. mongo)
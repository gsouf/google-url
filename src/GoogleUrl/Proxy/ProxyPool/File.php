<?php

namespace GoogleUrl\Proxy\ProxyPool;

use GoogleUrl\Exception;
use GoogleUrl\Proxy\ProxyDelayedInterface;
use GoogleUrl\Proxy\ProxyObject;
use GoogleUrl\Proxy\ProxyInterface;
use GoogleUrl\Proxy\ProxyAccessInterface;
use GoogleUrl\Proxy\SimpleProxyInterface;

/**
 * Description of File
 *
 * @author sghzal
 */
class File implements ProxyAccessInterface
{
    
    protected $fileName;
    protected $delays;
            
    function __construct($fileName, $delays)
    {
        if (is_writable($fileName)) {
            $this->fileName = $fileName;
        } else {
            throw new Exception("File not writtable : $fileName");
        }
        $this->delays = $delays;
    }
    
    public function getFile()
    {
        return json_decode(file_get_contents($this->fileName), true);
    }
    
    public function writeFile($data)
    {
        file_put_contents($this->fileName, json_encode($data));
    }
    
    /**
     * returns the proxy string identifier
     */
    private function __id(SimpleProxyInterface $proxy)
    {
        return $proxy->getIp() . ":" . $proxy->getPort();
    }

    /////////////////
    // IMPLEMENT
    
    

    public function setProxy(ProxyInterface $proxy)
    {
        
        $availTime = $proxy->getTimeToAvailability();
        
        $raw = [
            "last-run" => 0,
            "next-delay" => $availTime > 0 ? $availTime : 0 ,
            "ip" => $proxy->getIp(),
            "login" => $proxy->getLogin(),
            "password" => $proxy->getPassword(),
            "proxyType" => $proxy->getProxyType(),
            "port" => $proxy->getPort(),
            "locked" => false,
            "count" => 0,
            "delays" => null
        ];
        
        if ($proxy instanceof ProxyDelayedInterface && $proxy->getDelays()) {
            $raw["delays"] = $proxy->getDelays();
        }
        
        $id = $this->__id($proxy);
        
        $proxys = $this->getFile();
        $proxys["proxy"][$id] = $raw;
        $this->writeFile($proxys);
    }

    /**
     * check if the given proxy exists
     * @param SimpleProxyInterface $p
     * @return bool
     */
    public function hasProxy(SimpleProxyInterface $p)
    {
        $id = $this->__id($p);
        $proxys = $this->getFile();
        return isset($proxys["proxy"][$id]);
    }
    
    public function findAvailableProxy()
    {
        $proxies = $this->getProxies();
        foreach ($proxies as $p) {
            if (!$p->getLocked() && ($p->getLastUse() + $p->getNextDelay() <= time())) {
                return $p;
            }
        }
        return null;
    }

    /**
     * @return ProxyInterface[]
     */
    public function getProxies(){
        $proxyFile = $this->getFile();
        $proxies = [];
        foreach ($proxyFile["proxy"] as $p) {
            $proxies[] = $this->__constructProxy($p);
        }
        return $proxies;
    }

    public function getProxy($proxy){
        if(is_string($proxy)){
            $id = $proxy;
        }else if($proxy instanceof SimpleProxyInterface){
            $id = $this->__id($proxy);
        }else{
            throw new \InvalidArgumentException("Invalid argument, must be a proxy string id or a proxy instance");
        }

        $proxys = $this->getFile();
        if(isset($proxys["proxy"][$id])){
            return $this->__constructProxy($proxys["proxy"][$id]);
        }else{
            throw new Exception("proxy $id does not exist");
        }
    }

    private function __constructProxy($data)
    {
        $p = new ProxyObject($data["ip"], $data["port"], $data);
        if ($p instanceof ProxyDelayedInterface && isset($data["delays"])) {
            $p->setDelays($data["delays"]);
        }
        return $p;
    }

    public function findShortestTimeProxy()
    {
        
        $shortest = null;
        $shortestTime = null;
        
        $proxys = $this->getFile();
        foreach ($proxys["proxy"] as $p) {
            if (!$p["locked"]) {
                $time = $p["last-run"] + $p["next-delay"];
                
                if (!$shortest || $shortestTime >  $time) {
                    $shortest=$p;
                    $shortestTime = $time;
                }
                
            }
        }
        
        if ($shortest) {
            return $this->__constructProxy($p);
        } else {
            return null;
        }
    }

    public function proxyRegisterUsage(ProxyInterface $proxy)
    {
        $id = $this->__id($proxy);
        $proxys = $this->getFile();
        if (isset($proxys["proxy"][$id])) {
            $proxys["proxy"][$id]["last-run"] = time();
            
            
            $delays = $this->__getNextDelay($proxys["proxy"][$id]["delays"]);
            
            if ($delays["reset-count"]) {
                $proxys["proxy"][$id]["count"]=1;
            } else {
                $proxys["proxy"][$id]["count"]++;
            }
            
            $proxys["proxy"][$id]["next-delay"] = $delays["next-delay"];
            $this->writeFile($proxys);
        }
    }
    
    private function __getNextDelay($data)
    {
        
        if (isset($data["delays"]) && is_array($data["delays"])) {
            $delays = $data["delays"];
        } else {
            $delays = $this->delays;
        }

        $count = isset($data["count"]) ? $data["count"] : 0;
        
        return $this->__prepareNextDelay($delays, $count, true);
    }

    
    private function __prepareNextDelay($delays, $actualCount, $first = false)
    {
        foreach ($delays as $count => $range) {
            if ($actualCount <= $count) {
                return ["next-delay"=>rand($range[0], $range[1]) , "reset-count" => false ];
            }
        }
        
        if ($first) {
            $ret = $this->__prepareNextDelay($delays, 0, false);
            $ret["reset-count"] = true;
            return $ret;
        } else {
            throw new \Exception("Preventing endless loop : proxy delays are not correctly configured");
        }
    }
    
    
    
    
    public function acquireProxyLock(ProxyInterface $proxy)
    {
        $id = $this->__id($proxy);
        $proxys = $this->getFile();
        if (isset($proxys["proxy"][$id])) {
            $proxys["proxy"][$id]["locked"] = true;
        }
        $this->writeFile($proxys);
    }
    
    public function releaseProxyLock(ProxyInterface $proxy)
    {
        $id = $this->__id($proxy);
        $proxys = $this->getFile();
        if (isset($proxys["proxy"][$id])) {
            $proxys["proxy"][$id]["locked"] = false;
        }
        $this->writeFile($proxys);
    }

    public function removeProxy(ProxyInterface $proxy)
    {
        $id = $this->__id($proxy);
        $proxys = $this->getFile();
        if (isset($proxys["proxy"][$id])) {
            unset($proxys["proxy"][$id]);
        }
        $this->writeFile($proxys);
    }
}

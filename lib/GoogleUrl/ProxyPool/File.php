<?php

namespace GoogleUrl\ProxyPool;

use GoogleUrl\ProxyInterface;

/**
 * Description of File
 *
 * @author sghzal
 */
class File implements \GoogleUrl\ProxyAccessAdapter {
    
    protected $fileName;
    protected $delays;
            
    function __construct($fileName,$delays) {
        if(is_writable($fileName)){
            $this->fileName = $fileName;
        }else{
            throw new \GoogleUrl\Exception("File not writtable : $fileName");
        }
        
        $this->delays = $delays;
    }

    
    
    public function getFile(){
        return json_decode(file_get_contents($this->fileName),true);
    }
    
    public  function writeFile($data) {
        file_put_contents($this->fileName,json_encode($data));
    }
    
    /**
     * returns the proxy string identifier
     */
    private function __id(\GoogleUrl\SimpleProxyInterface $proxy){
        return $proxy->getIp() . ":" . $proxy->getPort();
    }

    /////////////////
    // IMPLEMENT
    
    

    public function setProxy(ProxyInterface $proxy) {
        
        $availTime = $proxy->getTimeToAvailability();
        
        $raw = array(
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
        );
        
        if($proxy instanceof ProxyDelayedInterface && $proxy->getDelais()){
            $raw["delays"] = $proxy->getDelays();
        }
        
        $id = $this->__id($proxy);
        
        $proxys = $this->getFile();
        $proxys["proxy"][$id] = $raw;
        $this->writeFile($proxys);
    }

    /**
     * check if the given proxy exists
     * @param \GoogleUrl\SimpleProxyInterface $p
     */
    public function hasProxy(\GoogleUrl\SimpleProxyInterface $p) {
        
        $id = $this->__id($p);
        
        $proxys = $this->getFile();
        
        return isset($proxys["proxy"][$id]);
        
    }
    
    public function findAvailableProxy() {
        $proxys = $this->getFile();
        foreach($proxys["proxy"] as $p){
            if(  !$p["locked"] && ($p["last-run"] + $p["next-delay"] <= time()) ){
                return $this->__constructProxy($p);
            }
        }
        return null;
    }
    
    private function __constructProxy($data){
        $p = new \GoogleUrl\Proxy\StdProxy($data["ip"], $data["port"],$data["login"],$data["password"],$data["proxyType"], $data["last-run"], $data["next-delay"], $data["count"], $data["locked"]);
        
        if($p instanceof ProxyDelayedInterface){
            $p->setDelays($data["delays"]);
        }
        
        return $p;
    }

    public function findShortestTimeProxy() {
        
        $shortest = null;
        $shortestTime = null;
        
        $proxys = $this->getFile();
        foreach($proxys["proxy"] as $p){
            
            if(!$p["locked"]){
            
                $time = $p["last-run"] + $p["next-delay"];
                
                if(!$shortest || $shortestTime >  $time){
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

    public function proxyUsed(ProxyInterface $proxy) {
        $id = $this->__id($proxy);
        $proxys = $this->getFile();
        if(isset($proxys["proxy"][$id])){
            
            $proxys["proxy"][$id]["last-run"] = time();
            
            
            $delays = $this->__getNextDelay($proxys["proxy"][$id]["delays"]);
            
            if($delays["reset-count"]){
                $proxys["proxy"][$id]["count"]=1;
            }else{
                $proxys["proxy"][$id]["count"]++;
            }
            
            $proxys["proxy"][$id]["next-delay"] = $delays["next-delay"];
            $this->writeFile($proxys);
        }
    }
    
    private function __getNextDelay($data){
        
        if( is_array($data["delays"])){
            $delays = $data["delays"];
        }else{
            $delays = $this->delays;
        }
        
        return $this->__prepareNextDelay($delays,$data["count"],true);
    }

    
    private function __prepareNextDelay($delays,$actualCount,$first=false){
        foreach($delays as $count => $range){
            if($actualCount <= $count){
                return array("next-delay"=>rand($range[0], $range[1]) , "reset-count" => false );
            }
        }
        
        if($first){
            $ret = $this->__prepareNextDelay($delays,0,false);
            $ret["reset-count"] = true;
            return $ret;
        }else{
            throw new \Exception("Preventing endless loop : proxy delays are not correctly configured");
        }
    }
    
    
    
    
    public function acquireProxyLock(ProxyInterface $proxy) {
        $id = $this->__id($proxy);
        $proxys = $this->getFile();
        if(isset($proxys["proxy"][$id])){
            $proxys["proxy"][$id]["locked"] = true;
        }
        $this->writeFile($proxys);
    }
    
    public function releaseProxyLock(ProxyInterface $proxy) {
        $id = $this->__id($proxy);
        $proxys = $this->getFile();
        if(isset($proxys["proxy"][$id])){
            $proxys["proxy"][$id]["locked"] = false;
        }
        $this->writeFile($proxys);
    }

    public function removeProxy(ProxyInterface $proxy) {
        $id = $this->__id($proxy);
        $proxys = $this->getFile();
        if(isset($proxys["proxy"][$id])){
            unset($proxys["proxy"][$id]);
        }
        $this->writeFile($proxys);
    }

    
}

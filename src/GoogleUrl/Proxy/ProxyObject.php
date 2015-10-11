<?php

namespace GoogleUrl\Proxy;

use GoogleUrl\Exception;
use GoogleUrl\Proxy\ProxyInterface;
use GoogleUrl\Proxy\ProxyDelayedInterface;
use GoogleUrl\Proxy\SimpleProxyInterface;

/**
 * A basic proxy object
 */
class ProxyObject extends SimpleProxy implements ProxyInterface, ProxyDelayedInterface
{


    protected $lastUse;
    
    protected $delays;
    protected $nextDelay;
    protected $delaysCount;
    
    protected $cycle;
    
    protected $locked;
    
    public static function fromSimpleProxy(SimpleProxyInterface $proxy, array $options = [])
    {

        $options["login"] = $proxy->getLogin();
        $options["password"] = $proxy->getPassword();
        $options["proxyType"] = $proxy->getProxyType();

        return new static($proxy->getIp(), $proxy->getPort(), $options);
    }

    public static function fromProxyString($proxy, array $options = [])
    {
        $proxyPieces = explode("@", $proxy);

        if (count($proxyPieces) == 2) {
            $authPieces = explode(":", $proxyPieces[0]);

            if (count($authPieces)>2) {
                throw new Exception("Bad proxy string. Format : [user[:passsword]@]ip:port");
            }

            if (!isset($authPieces[1])) {
                $authPieces[1] = null;
            }

            $hostPieces = explode(":", $proxyPieces[1]);
            if (count($hostPieces) !== 2) {
                throw new Exception("Bad proxy string. Format : [user[:passsword]@]ip:port");
            }

        } elseif (count($proxyPieces) == 1) {
            $authPieces = [null,null];
            $hostPieces = explode(":", $proxyPieces[0]);

        } else {
            throw new Exception("Bad proxy string. Format : [user[:passsword]@]ip:port");
        }

        $options["login"] = $authPieces[0];
        $options["password"] = $authPieces[1];

        return new static($hostPieces[0], $hostPieces[1], $options);
    }
    
    public function __construct($ip, $port, array $options = [])
    {
        parent::__construct($ip, $port);

        $defaultOptions = [
            "login" => null,
            "password" => null,
            "proxyType" => CURLPROXY_HTTP,
            "lastUse" => null,
            "nextDelay" => null,
            "delaysCount" => null,
            "locked" => null
        ];

        $options = array_merge($defaultOptions, $options);

        $this->setLastUse($options["lastUse"]);
        $this->setLocked($options["locked"]);
        $this->setNextDelay($options["nextDelay"]);
        $this->setDelaysCount($options["delaysCount"]);
        
        $this->setLogin($options["login"]);
        $this->setPassword($options["password"]);
        $this->setProxyType($options["proxyType"]);
    }
    
    
    public function getNextDelay()
    {
        return $this->nextDelay;
    }

    public function setNextDelay($nextDelay)
    {
        $this->nextDelay = $nextDelay;
    }

    public function getLocked()
    {
        return $this->locked;
    }

    public function setLocked($locked)
    {
        $this->locked = $locked;
    }
    
    public function prepareNextDelay($delays)
    {
        return $this->__prepareNextDelay($delays, true);
    }
    
    
    public function __toString()
    {
        return $this->ip . ":" . $this->port;
    }
    
    
    private function __prepareNextDelay($delaysDefault, $first = false)
    {
        
        if (!$this->delays) {
            $delays = $delaysDefault;
        } else {
            $delays = $this->delays;
        }
        
        foreach ($delays as $count => $range) {
            if ($count < 0 ||  $this->delaysCount <= $count) {
                $this->nextDelay = rand($range[0], $range[1]);
                $this->increaseDelayCount();
                return $this->nextDelay;
            }
        }
        
        if ($first) {
            $this->setDelaysCount(0);
            $this->increaseCycle();
            return $this->__prepareNextDelay($delaysDefault);
        } else {
            throw new \Exception("Preventing endless loop : proxy delays are not correctly configured");
        }
    }
    
    public function getDelayCount()
    {
        return $this->getDelaysCount();
    }

    
    public function isAvailable()
    {
        return !$this->getLocked() && ($this->getTimeToAvailability() == 0);
    }
    
    public function getTimeToAvailability()
    {

        $next = ($this->lastUse + $this->nextDelay) - time();

        return $next > 0 ? $next : 0;
    }

        
    public function getLastUse()
    {
        return $this->lastUse;
    }

    public function setLastUse($lastUse)
    {
        $this->lastUse = $lastUse;
    }

    public function getDelays()
    {
        return $this->delays;
    }

    public function setDelays($delays)
    {
        $this->delays = $delays;
    }

    public function getDelaysCount()
    {
        return $this->delaysCount;
    }

    public function setDelaysCount($delaysCount)
    {
        $this->delaysCount = $delaysCount;
    }
    
    public function increaseDelayCount()
    {
        $this->delaysCount++;
    }

    public function getCycle()
    {
        return $this->cycle;
    }

    public function setCycle($cycle)
    {
        $this->cycle = $cycle;
    }

    public function increaseCycle()
    {
        $this->cycle++;
    }
}

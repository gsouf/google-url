<?php

namespace GoogleUrl\Proxy;

use GoogleUrl\Proxy\ProxyInterface;

/**
 * Description of ProxyPool
 *
 * @author bob
 */
class ProxyPool implements ProxyAccessInterface
{
    
    /**
     *
     * @var ProxyInterface[]
     */
    protected $proxys;
    
    protected $delays;
    
    /**
     * the delays to use by default if the proxy doesnt have
     *
     * <code>
     *
     * new ProxyPool([
     *
     *      10 => 50,   // 50 seconds for the 10 first requests
     *      20 => 60,   // 60 seconds for the 10th to 20th requests
     *      50 => 80    // 80 seconds for the 20th to 50th requests
     *
     * ]);
     *
     * </code>
     *
     * @param array $delays
     */
    function __construct($delays)
    {
        $this->delays = $delays;
    }

    
    /**
     * @inheritdoc
     */
    public function setProxy(ProxyInterface $p)
    {
        if (!$p instanceof ProxyObject) {
            throw new \Exception("ProxyPool::addProxy() first parameter must be an instance of GoogleUrl\Proxy\ProxyObject. " . get_class($p) . " used instead.");
        }
        
        $this->proxys[$p->__toString()] = $p;
    }


    /**
     * @inheritdoc
     */
    public function hasProxy(SimpleProxyInterface $p)
    {
        return isset($this->proxys[$p->getIp() . ":" . $p->getPort()]);
    }


    /**
     * @inheritdoc
     */
    public function removeProxy(ProxyInterface $proxy)
    {
        $string = $proxy->getIp() . ":" . $proxy->getPort();
        if (isset($this->proxys[$string])) {
            unset($this->proxys[$string]);
        }
    }

    /**
     * @inheritdoc
     */
    public function findShortestTimeProxy()
    {
        $finalProxy = null;
        foreach ($this->proxys as $p) {
            if (!$finalProxy) {
                $finalProxy = $p;
            } else {
                if ($p->getTimeToAvailability() < $finalProxy->getTimeToAvailability()) {
                    $finalProxy = $p;
                }
            }
        }
        return $finalProxy;
    }

    /**
     * @inheritdoc
     */
    public function findAvailableProxy()
    {
        foreach ($this->proxys as $p) {
            $avail = $p->isAvailable();
            if ($avail) {
                return $p;
            }
        }
        return null;
    }

    /**
     * @inheritdoc
     */
    public function proxyRegisterUsage(ProxyInterface $proxy)
    {
        $string = $proxy->getIp() . ":" . $proxy->getPort();

        if (isset($this->proxys[$string])) {
            $p = $this->proxys[$string];
            $p->setLastUse(time());
            $p->prepareNextDelay($this->delays);
        }
    }

    /**
     * @inheritdoc
     */
    public function acquireProxyLock(ProxyInterface $proxy)
    {
        $string = $proxy->getIp() . ":" . $proxy->getPort();
        
        if (isset($this->proxys[$string])) {
            $p = $this->proxys[$string];
            $p->setLocked(true);
        }
    }

    /**
     * @inheritdoc
     */
    public function releaseProxyLock(ProxyInterface $proxy)
    {
        $string = $proxy->getIp() . ":" . $proxy->getPort();
        
        if (isset($this->proxys[$string])) {
            $p = $this->proxys[$string];
            $p->setLocked(false);
        }
    }
}

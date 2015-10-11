<?php
/**
 * @license see LICENSE
 */

namespace GoogleUrl\Test\Proxy\ProxyPool;

use GoogleUrl\Proxy\ProxyObject;
use GoogleUrl\Proxy\ProxyPool;

/**
 * @covers GoogleUrl\Proxy\ProxyPool
 */
class ProxyPoolTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var ProxyPool
     */
    protected $pool;

    public function setUp(){

        $this->pool = new ProxyPool([ -1 => 10 ]);
        $this->pool->setProxy(ProxyObject::fromProxyString("1.1.1.1:80"));
    }

    public function testHasProxy(){
        $this->assertTrue($this->pool->hasProxy(ProxyObject::fromProxyString("1.1.1.1:80")));
    }

    public function testFindAvailableProxy(){
        $proxy = $this->pool->findAvailableProxy();
        $this->assertInstanceOf("GoogleUrl\Proxy\ProxyInterface", $proxy);
        $this->assertEquals("1.1.1.1", $proxy->getIp());
        $this->assertEquals("80", $proxy->getPort());

        $this->pool->acquireProxyLock($proxy);
        $this->assertNull($this->pool->findAvailableProxy());

        $this->pool->releaseProxyLock($proxy);
        $this->assertInstanceOf("GoogleUrl\Proxy\ProxyInterface", $proxy);
    }

    public function testAcquireProxyLock(){
        $this->pool->acquireProxyLock(ProxyObject::fromProxyString("1.1.1.1:80"));
        $this->assertTrue($this->pool->getProxy("1.1.1.1:80")->getLocked());
    }

    public function testReleaseProxyLock(){
        $this->pool->acquireProxyLock(ProxyObject::fromProxyString("1.1.1.1:80"));
        $this->pool->releaseProxyLock(ProxyObject::fromProxyString("1.1.1.1:80"));
        $this->assertFalse($this->pool->getProxy("1.1.1.1:80")->getLocked());
    }

    public function testGetProxy(){
        $proxy = $this->pool->getProxy("1.1.1.1:80");
        $this->assertInstanceOf("GoogleUrl\Proxy\ProxyInterface", $proxy);
        $this->assertEquals("1.1.1.1", $proxy->getIp());
        $this->assertEquals("80", $proxy->getPort());

        $proxy = $this->pool->getProxy(ProxyObject::fromProxyString("1.1.1.1:80"));
        $this->assertInstanceOf("GoogleUrl\Proxy\ProxyInterface", $proxy);
        $this->assertEquals("1.1.1.1", $proxy->getIp());
        $this->assertEquals("80", $proxy->getPort());
    }

    public function testGetProxies(){
        $proxies = $this->pool->getProxies();
        $this->assertCount(1, $proxies);
        $proxy = $proxies[0];
        $this->assertInstanceOf("GoogleUrl\Proxy\ProxyInterface", $proxy);
        $this->assertEquals("1.1.1.1", $proxy->getIp());
        $this->assertEquals("80", $proxy->getPort());
    }

    public function testSetProxy(){
        $newProxy = ProxyObject::fromProxyString("2.2.2.2:88");
        $this->pool->setProxy($newProxy);
        $this->assertCount(2, $this->pool->getProxies());
    }
}

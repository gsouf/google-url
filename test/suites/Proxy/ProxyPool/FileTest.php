<?php
/**
 * @license see LICENSE
 */

namespace GoogleUrl\Test\Proxy\ProxyPool;

use GoogleUrl\Proxy\ProxyObject;
use GoogleUrl\Proxy\ProxyPool\File;
use org\bovigo\vfs\vfsStream;

/**
 * @covers GoogleUrl\Proxy\ProxyPool\File
 */
class FileTest extends \PHPUnit_Framework_TestCase {

    protected $filePath;

    /**
     * @var File
     */
    protected $file;

    public function setUp(){
        $root = vfsStream::setup("FileTest");
        vfsStream::copyFromFileSystem("test/Fixtures/FilesTest", $root);

        $this->filePath = "vfs://FileTest/proxy.json";
        $this->file = new File($this->filePath, [ -1 => 10 ]);
    }

    public function testHasProxy(){
        $this->assertTrue($this->file->hasProxy(ProxyObject::fromProxyString("1.1.1.1:80")));
    }

    public function testFindAvailableProxy(){
        $proxy = $this->file->findAvailableProxy();
        $this->assertInstanceOf("GoogleUrl\Proxy\ProxyInterface", $proxy);
        $this->assertEquals("1.1.1.1", $proxy->getIp());
        $this->assertEquals("80", $proxy->getPort());

        $this->file->acquireProxyLock($proxy);
        $this->assertNull($this->file->findAvailableProxy());

        $this->file->releaseProxyLock($proxy);
        $this->assertInstanceOf("GoogleUrl\Proxy\ProxyInterface", $proxy);
    }

    public function testAcquireProxyLock(){
        $this->file->acquireProxyLock(ProxyObject::fromProxyString("1.1.1.1:80"));
        $fileContent = json_decode(file_get_contents($this->filePath), true);
        $this->assertTrue($fileContent["proxy"]["1.1.1.1:80"]["locked"]);
    }

    public function testReleaseProxyLock(){
        $this->file->acquireProxyLock(ProxyObject::fromProxyString("1.1.1.1:80"));
        $this->file->releaseProxyLock(ProxyObject::fromProxyString("1.1.1.1:80"));
        $fileContent = json_decode(file_get_contents($this->filePath), true);
        $this->assertFalse($fileContent["proxy"]["1.1.1.1:80"]["locked"]);
    }

    public function testGetProxy(){
        $proxy = $this->file->getProxy("1.1.1.1:80");
        $this->assertInstanceOf("GoogleUrl\Proxy\ProxyInterface", $proxy);
        $this->assertEquals("1.1.1.1", $proxy->getIp());
        $this->assertEquals("80", $proxy->getPort());

        $proxy = $this->file->getProxy(ProxyObject::fromProxyString("1.1.1.1:80"));
        $this->assertInstanceOf("GoogleUrl\Proxy\ProxyInterface", $proxy);
        $this->assertEquals("1.1.1.1", $proxy->getIp());
        $this->assertEquals("80", $proxy->getPort());
    }

    public function testGetProxies(){
        $proxies = $this->file->getProxies();
        $this->assertCount(1, $proxies);
        $proxy = $proxies[0];
        $this->assertInstanceOf("GoogleUrl\Proxy\ProxyInterface", $proxy);
        $this->assertEquals("1.1.1.1", $proxy->getIp());
        $this->assertEquals("80", $proxy->getPort());
    }

    public function testSetProxy(){
        $newProxy = ProxyObject::fromProxyString("2.2.2.2:88");
        $this->file->setProxy($newProxy);
        $this->assertCount(2, $this->file->getProxies());

        $fileContent = json_decode(file_get_contents($this->filePath), true);
        $this->assertEquals("2.2.2.2", $fileContent["proxy"]["2.2.2.2:88"]["ip"]);
        $this->assertEquals("88", $fileContent["proxy"]["2.2.2.2:88"]["port"]);
    }
}

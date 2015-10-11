<?php

/**
 * @license see LICENSE
 */

namespace GoogleUrl\Test\Proxy;

use GoogleUrl\Proxy\ProxyObject;
use GoogleUrl\Proxy\SimpleProxy;

/**
 * @covers GoogleUrl\Proxy\ProxyObject
 */
class ProxyObjectTest extends \PHPUnit_Framework_TestCase
{

    public function testConstruct()
    {
        $ip = "1.1.1.1";
        $port = 80;

        $proxyObject = new ProxyObject($ip, $port);
        $this->assertEquals($ip, $proxyObject->getIp());
        $this->assertEquals($port, $proxyObject->getPort());


        $options = [
            "login" => "login",
            "password" => "password",
            "proxyType" => CURLPROXY_HTTP,
            "lastUse" => time(),
            "nextDelay" => 20,
            "delaysCount" => 5,
            "locked" => false
        ];

        $proxyObject = new ProxyObject($ip, $port, $options);
        $this->assertEquals($options["login"], $proxyObject->getLogin());
        $this->assertEquals($options["password"], $proxyObject->getPassword());
        $this->assertEquals($options["proxyType"], $proxyObject->getProxyType());
        $this->assertEquals($options["lastUse"], $proxyObject->getLastUse());
        $this->assertEquals($options["nextDelay"], $proxyObject->getNextDelay());
        $this->assertEquals($options["delaysCount"], $proxyObject->getDelayCount());
        $this->assertEquals($options["locked"], $proxyObject->getLocked());

    }

    public function testFromSimpleProxy(){
        $ip = "1.1.1.1";
        $port = 80;

        $simpleProxy = new SimpleProxy($ip, $port);
        $proxyObject = ProxyObject::fromSimpleProxy($simpleProxy);
        $this->assertInstanceOf("GoogleUrl\Proxy\ProxyObject", $proxyObject);
        $this->assertEquals($ip, $proxyObject->getIp());
        $this->assertEquals($port, $proxyObject->getPort());
    }

    public function testFromProxyString(){
        $proxyObject = ProxyObject::fromProxyString("login:password@1.1.1.1:80");
        $this->assertInstanceOf("GoogleUrl\Proxy\ProxyObject", $proxyObject);
        $this->assertEquals("1.1.1.1", $proxyObject->getIp());
        $this->assertEquals("80", $proxyObject->getPort());
        $this->assertEquals("login", $proxyObject->getLogin());
        $this->assertEquals("password", $proxyObject->getPassword());
    }

    public function testIsAvailable(){
        $proxy = new ProxyObject("1.1.1.1", 80, [
            "locked" => false,
            "lastRun" => time() - 20,
            "delaysCount" => 1
        ]);

        $this->assertTrue($proxy->isAvailable());
        $proxy->setLocked(true);
        $this->assertFalse($proxy->isAvailable());
        $proxy->setLocked(false);
        $this->assertTrue($proxy->isAvailable());

        $proxy->setNextDelay(10);
        $proxy->setLastUse(time() - 9);
        $this->assertFalse($proxy->isAvailable());

        $proxy->setNextDelay(10);
        $proxy->setLastUse(time() - 11);
        $this->assertTrue($proxy->isAvailable());

    }

    public function assertBetweenInclusive($min, $max, $value){
        $this->assertGreaterThanOrEqual($min, $value);
        $this->assertLessThanOrEqual($max, $value);
    }


    public function testPrepareNextDelay(){
        $proxy = new ProxyObject("1.1.1.1", 80);

        $delays = [
            3 => [5, 5],
            5 => [11, 15],
            6 => [2, 3]
        ];

        $this->assertEquals(0, $proxy->getCycle());
        // 1
        $proxy->prepareNextDelay($delays);
        $this->assertEquals(5, $proxy->getNextDelay());
        // 2
        $proxy->prepareNextDelay($delays);
        $this->assertEquals(5, $proxy->getNextDelay());
        // 3
        $proxy->prepareNextDelay($delays);
        $this->assertEquals(5, $proxy->getNextDelay());
        // 4
        $proxy->prepareNextDelay($delays);
        $this->assertBetweenInclusive(11, 15, $proxy->getNextDelay());
        // 5
        $proxy->prepareNextDelay($delays);
        $this->assertBetweenInclusive(11, 15, $proxy->getNextDelay());
        // 6
        $proxy->prepareNextDelay($delays);
        $this->assertBetweenInclusive(2, 3, $proxy->getNextDelay());
        $this->assertEquals(0, $proxy->getCycle());
        // 7 (back to 1)
        $proxy->prepareNextDelay($delays);
        $this->assertEquals(5, $proxy->getNextDelay());
        $this->assertEquals(1, $proxy->getCycle());
        // 8 (back to 2)
        $proxy->prepareNextDelay($delays);
        $this->assertEquals(5, $proxy->getNextDelay());
        // 9 (back to 3)
        $proxy->prepareNextDelay($delays);
        $this->assertEquals(5, $proxy->getNextDelay());
        // 10 (back to 4)
        $proxy->prepareNextDelay($delays);
        $this->assertBetweenInclusive(11, 15, $proxy->getNextDelay());


        $delays = [
            3 => [5, 5],
            -1 => 20
        ];

        // 11 and more (always 20)
        $proxy->prepareNextDelay($delays);
        $this->assertEquals(20, $proxy->getNextDelay());
        $proxy->prepareNextDelay($delays);
        $this->assertEquals(20, $proxy->getNextDelay());
        $proxy->prepareNextDelay($delays);
        $this->assertEquals(20, $proxy->getNextDelay());
        $proxy->prepareNextDelay($delays);
        $this->assertEquals(20, $proxy->getNextDelay());

    }
}

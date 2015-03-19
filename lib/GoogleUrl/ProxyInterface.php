<?php

/**
 * @copyright (c) Rock A Gogo VPC
 */

namespace GoogleUrl;

/**
 * ProxyInterface
 *
 * @author sghzal
 */
/**
 * Interface ProxyInterface
 * @package GoogleUrl
 */
interface ProxyInterface {

    /**
     * @param $ip
     * @param $port
     * @param $login
     * @param $password
     * @param $proxyType
     * @param $lastRun
     * @param $nextDelay
     * @param $delayCount
     * @param $locked
     */
    function __construct($ip, $port,$login,$password,$proxyType, $lastRun, $nextDelay,$delayCount,$locked);

    /**
     * @return mixed
     */
    public function getIp();

    /**
     * @return mixed
     */
    public function getPort();

    /**
     * @return mixed
     */
    public function getDelayCount();

    /**
     * @return mixed
     */
    public function getLocked();

    /**
     * @return mixed
     */
    public function getTimeToAvailability();

    /**
     * @return mixed
     */
    public function isAvailable();

    /**
     * @return mixed
     */
    public function getLogin();

    /**
     * @return mixed
     */
    public function getPassword();

    /**
     * @return mixed
     */
    public function getProxyType();
    
}
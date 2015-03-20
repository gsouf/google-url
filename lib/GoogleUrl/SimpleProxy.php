<?php

namespace GoogleUrl;

/**
 * Class SimpleProxy
 * @package GoogleUrl
 * @author bob
 */
class SimpleProxy implements SimpleProxyInterface{

    /**
     * @var
     */
    public $ip;
    /**
     * @var
     */
    public $port;
    /**
     * @var
     */
    public $login;
    /**
     * @var
     */
    public $password;
    /**
     * @var int
     */
    public $proxyType;

    /**
     * @param $ip
     * @param $port
     */
    function __construct($ip, $port) {
        $this->ip = $ip;
        $this->port = $port;
        $this->proxyType = CURLPROXY_HTTP;
    }

    /**
     * @return mixed
     */
    public function getIp() {
        return $this->ip;
    }

    /**
     * @return mixed
     */
    public function getPort() {
        return $this->port;
    }

    /**
     * @param $ip
     */
    public function setIp($ip) {
        $this->ip = $ip;
    }

    /**
     * @param $port
     */
    public function setPort($port) {
        $this->port = $port;
    }

    /**
     * @return mixed
     */
    public function getLogin() {
        return $this->login;
    }

    /**
     * @return mixed
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * @param $login
     */
    public function setLogin($login) {
        $this->login = $login;
    }

    /**
     * @param $password
     */
    public function setPassword($password) {
        $this->password = $password;
    }

    /**
     * @return int
     */
    public function getProxyType() {
        return $this->proxyType;
    }

    /**
     * @param $proxyType
     */
    public function setProxyType($proxyType) {
        $this->proxyType = $proxyType;
    }
}

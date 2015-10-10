<?php


namespace GoogleUrl\Proxy;

/**
 * That describes basic informations of a proxy (ip, port, login...)
 */
interface SimpleProxyInterface {

    public function getIp();
    public function getPort();
    
    public function getLogin();
    public function getPassword();

    /**
     * Proxy type used in curl proxy type parameter (http, sock5...)
     * @return string
     */
    public function getProxyType();

}

<?php


namespace GoogleUrl;

/**
 * Description of SimpleProxyInterface
 *
 * @author sghzal
 */
interface SimpleProxyInterface {

    public function getIp();
    public function getPort();
    
    public function getLogin();
    public function getPassword();
    
    public function getProxyType();
    
    
}

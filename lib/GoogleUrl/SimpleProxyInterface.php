<?php

namespace GoogleUrl;

/**
 * Interface SimpleProxyInterface
 * @author sghzal
 * @package GoogleUrl
 */
interface SimpleProxyInterface {

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

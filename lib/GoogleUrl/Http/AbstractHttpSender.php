<?php

namespace GoogleUrl\Http;

use GoogleUrl\Proxy\SimpleProxyInterface;

abstract class AbstractHttpSender {


    protected $userAgent =  "Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2049.0 Safari/537.36";


    /**
     * Sends a http query to the given url with the given proxy
     * @param $url
     * @param SimpleProxyInterface $proxy
     * @return string the raw response of the server
     */
    abstract public function send($url, $options, SimpleProxyInterface $proxy = null);

    /**
     * @return string
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     * @param string $userAgent
     */
    public function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;
    }




}
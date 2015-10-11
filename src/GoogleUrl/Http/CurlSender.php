<?php


namespace GoogleUrl\Http;

use GoogleUrl\Http\Curl\Curl;
use GoogleUrl\Proxy\SimpleProxyInterface;

class CurlSender extends AbstractHttpSender
{



    /**
     * @inheritdoc
     */
    public function send($url, $options, SimpleProxyInterface $proxy = null)
    {


        /**=========
         * INIT CURL
        =========*/
        $c = new Curl();
        $c->url = $url;


        /**==========
         * DO HEADERS
        ===========*/
        // let's be redirected if needed
        $c->followLocation();
        // use a true user agent, maybe better for true results
        $c->useragent = $this->getUserAgent();


        if (isset($options["headers"])) {
            $headers = [];
            foreach ($options["headers"] as $header => $value) {
                $headers[] = "$header: $value";
            }

            $c->httpHeader = $headers;
        }



        if (isset($options["postData"])) {
            $c->post = count($options["postData"]);
            $c->postFields = $options["postData"];

        }


        /**=========
         * SET PROXY
        =========*/
        if ($proxy) {
            $c->proxy     = $proxy->getIp();
            $c->proxyport = $proxy->getPort();

            $login = $proxy->getLogin();
            if ($login) {
                $auth = $login;
                $psw  = $proxy->getPassword();
                if ($psw) {
                    $auth .= ":" . $psw;
                }
                $c->proxyuserpwd = $auth;
            }


            $proxyType    = $proxy->getProxyType();
            $c->proxytype = $proxyType ? $proxyType : "http";

        }


        /**========
         * EXECUTE
        =========*/
        $r = $c->exec();

        if (false === $r) {
            $errno = $c->errno();

            if (CURLE_COULDNT_RESOLVE_PROXY == $errno) {
                throw new \GoogleUrl\Exception\ProxyException("HTTP query failled [curl-error : $errno - " . $c->error() . " ] for the following URL : ".$url);
            } else {
                throw new \GoogleUrl\Exception\CurlException("HTTP query failled [curl-error : $errno - " . $c->error() . " ] for the following URL : ".$url);
            }

        }

        return $r;


    }
}

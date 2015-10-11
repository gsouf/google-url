<?php
/**
 * Created by PhpStorm.
 * User: bob
 * Date: 2/28/15
 * Time: 4:11 PM
 */

namespace GoogleUrl\Result;

abstract class ClickableResult extends PositionedResult
{

    protected $targetUrl;
    protected $website = null;

    /**
     * @return mixed
     */
    public function getTargetUrl()
    {
        return $this->targetUrl;
    }

    /**
     * @param mixed $url
     */
    public function setTargetUrl($url)
    {
        $this->targetUrl = $url;
    }

    /**
     * @return mixed
     */
    public function getWebsite()
    {

        if (null === $this->website) {
            $this->website = $this->_extractDomain($this->getTargetUrl());
        }

        return $this->website;
    }

    protected function _extractDomain($url)
    {

        $protPos=strpos($url, "://");

        $shortUrl=  substr($url, $protPos+3); // ltrim the protocol
        $shortUrl=  substr($shortUrl, 0, strpos($shortUrl, "/")); // remove all what left after the first /

        return $shortUrl;

    }
}

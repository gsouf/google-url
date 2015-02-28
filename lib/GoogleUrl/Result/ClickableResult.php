<?php
/**
 * Created by PhpStorm.
 * User: bob
 * Date: 2/28/15
 * Time: 4:11 PM
 */

namespace GoogleUrl\Result;


abstract class ClickableResult extends PositionedResult {

    protected $targetUrl;
    protected $website;

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
        return $this->website;
    }

    /**
     * @param mixed $website
     */
    public function setWebsite($website)
    {
        $this->website = $website;
    }

}
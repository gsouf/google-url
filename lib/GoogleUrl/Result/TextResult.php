<?php


namespace GoogleUrl\Result;


abstract class TextResult extends PositionedResult {

    protected $targetUrl;
    protected $website;
    protected $title;
    protected $snippet;

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

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getSnippet()
    {
        return $this->snippet;
    }

    /**
     * @param mixed $snippet
     */
    public function setSnippet($snippet)
    {
        $this->snippet = $snippet;
    }




}
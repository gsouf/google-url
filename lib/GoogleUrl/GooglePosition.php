<?php

// See LICENSE

namespace GoogleUrl;

/**
 * Description of GooglePosition
 *
 * @author sghzal
 * @license http://www.freebsd.org/copyright/license.html BSD
 */
class GooglePosition {
    /**
     * search query
     * @var string
     */
    protected $keyword;
    
    /**
     * the website formated like follow : subdomain.domain.tld  (leave out the www.)
     * @var string 
     */
    protected $website;
    
    /**
     * position in the SERP
     * @var int
     */
    protected $position;
    
    /**
     * matching url
     * @var string
     */
    protected $url;
    
    /**
     * matching title
     * @var string
     */
    protected $title;
    
    /**
     * html string of the matching snipper
     * @var string
     */
    protected $snippet;
    
    /**
     * UNIX timestamp date of the search
     * @var int
     */
    protected $date;
    
    
    /**
     * 
     * @param string $keyword the search query
     * @param string $website the searched website
     * @param int $date the date in seconds (UNIX timestamp)
     * @param int|boolean $position the position in the serp of false if not found. Begins to 1
     * @param string $url the url found in the search
     * @param string $title the title found in the search
     * @param strnig $snippet the html snippet found in the search
     */
    function __construct($keyword, $website, $date, $position, $url, $title, $snippet) {
        $this->keyword  = $keyword;
        $this->website  = $website;
        $this->position = $position;
        $this->url      = $url;
        $this->title    = $title;
        $this->snippet  = $snippet;
        $this->date     = $date;
    }

    
    
    public function getKeyword() {
        return $this->keyword;
    }

    public function setKeyword($keyword) {
        $this->keyword = $keyword;
    }

    public function getWebsite() {
        return $this->website;
    }

    public function setWebsite($website) {
        $this->website = $website;
    }

    public function getPosition() {
        return $this->position;
    }

    public function setPosition($position) {
        $this->position = $position;
    }

    public function getUrl() {
        return $this->url;
    }

    public function setUrl($url) {
        $this->url = $url;
    }

    public function getTitle() {
        return $this->title;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function getSnippet() {
        return $this->snippet;
    }

    public function setSnippet($snippet) {
        $this->snippet = $snippet;
    }

    public function getDate() {
        return $this->date;
    }

    public function setDate($date) {
        $this->date = $date;
    }
    
}
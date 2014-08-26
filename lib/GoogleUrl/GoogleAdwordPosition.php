<?php

// See LICENSE

namespace GoogleUrl;

/**
 * Description of GooglePosition
 *
 * @author sghzal
 * @license http://www.freebsd.org/copyright/license.html BSD
 */
class GoogleAdwordPosition {
    /**
     * search query
     * @var string
     */
    protected $keyword;
    
    /**
     * the visual url used for display only
     * @var string 
     */
    protected $visurl;
    
    /**
     * position in the SERP
     * @var int
     */
    protected $position;
    
    /**
     * adwords url (do not play with it)
     * @var string
     */
    protected $adwordsUrl;
    
    /**
     * the displayed title
     * @var string
     * 
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
    
    
    function __construct($keyword, $position , $visurl , $adwordsUrl, $title, $snippet, $date) {
        $this->keyword = $keyword;
        $this->visurl = $visurl;
        $this->position = $position;
        $this->adwordsUrl = $adwordsUrl;
        $this->title = $title;
        $this->snippet = $snippet;
        $this->date = $date;
    }
    
    public function getKeyword() {
        return $this->keyword;
    }

    public function setKeyword($keyword) {
        $this->keyword = $keyword;
    }

    public function getVisurl() {
        return $this->visurl;
    }

    public function setVisurl($visurl) {
        $this->visurl = $visurl;
    }

    public function getPosition() {
        return $this->position;
    }

    public function setPosition($position) {
        $this->position = $position;
    }

    public function getGoogleUrl() {
        return $this->googleUrl;
    }

    public function setGoogleUrl($googleUrl) {
        $this->googleUrl = $googleUrl;
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
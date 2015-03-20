<?php

// See LICENSE

namespace GoogleUrl;

/**
 * Class GoogleAdwordPosition
 * @package GoogleUrl
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
    protected $text;
    
    /**
     * UNIX timestamp date of the search
     * @var int
     */
    protected $date;
    
    /**
     * where on the page (body, column)
     * @var string
     */
    protected $location;
    
    
    /**
     * 
     * @param type $keyword
     * @param type $position
     * @param type $visurl
     * @param type $adwordsUrl
     * @param type $title
     * @param type $text
     * @param type $date
     */
    function __construct($keyword, $position , $visurl , $adwordsUrl, $title, $text, $date) {
        $this->keyword = $keyword;
        $this->visurl = $visurl;
        $this->position = $position;
        $this->adwordsUrl = $adwordsUrl;
        $this->title = $title;
        $this->text = $text;
        $this->date = $date;
    }

    /**
     * @return string
     */
    public function getLocation() {
        return $this->location;
    }

    /**
     * @param $location
     */
    public function setLocation($location) {
        $this->location = $location;
    }


    /**
     * @return type|string
     */
    public function getKeyword() {
        return $this->keyword;
    }

    /**
     * @param $keyword
     */
    public function setKeyword($keyword) {
        $this->keyword = $keyword;
    }

    /**
     * @return type|string
     */
    public function getVisurl() {
        return $this->visurl;
    }

    /**
     * @param $visurl
     */
    public function setVisurl($visurl) {
        $this->visurl = $visurl;
    }

    /**
     * @return type|int
     */
    public function getPosition() {
        return $this->position;
    }

    /**
     * @param $position
     */
    public function setPosition($position) {
        $this->position = $position;
    }


    /**
     * @return type|string
     */
    public function getAdwordsUrl() {
        return $this->adwordsUrl;
    }

    /**
     * @param $adwordsUrl
     */
    public function setAdwordsUrl($adwordsUrl) {
        $this->adwordsUrl = $adwordsUrl;
    }

    /**
     * @return type|string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * @param $title
     */
    public function setTitle($title) {
        $this->title = $title;
    }


    /**
     * @return type|int
     */
    public function getDate() {
        return $this->date;
    }

    /**
     * @param $date
     */
    public function setDate($date) {
        $this->date = $date;
    }

    /**
     * @return type|string
     */
    public function getText() {
        return $this->text;
    }

    /**
     * @param $text
     */
    public function setText($text) {
        $this->text = $text;
    }
}
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
    
    public function getLocation() {
        return $this->location;
    }

    public function setLocation($location) {
        $this->location = $location;
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

    
    public function getAdwordsUrl() {
        return $this->adwordsUrl;
    }

    public function setAdwordsUrl($adwordsUrl) {
        $this->adwordsUrl = $adwordsUrl;
    }
        
    public function getTitle() {
        return $this->title;
    }

    public function setTitle($title) {
        $this->title = $title;
    }


    public function getDate() {
        return $this->date;
    }

    public function setDate($date) {
        $this->date = $date;
    }

    public function getText() {
        return $this->text;
    }

    public function setText($text) {
        $this->text = $text;
    }


    

    
}
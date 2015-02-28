<?php

// See LICENSE

namespace GoogleUrl;

/**
 * Description of GooglePosition
 *
 * @author sghzal
 * @license http://www.freebsd.org/copyright/license.html BSD
 */
class AdWordsPosition {

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
     * where on the page (body, column)
     * @var string
     */
    protected $pageLocation;
    

    
    public function getPageLocation() {
        return $this->pageLocation;
    }

    public function setPageLocation($location) {
        $this->pageLocation = $location;
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

    public function getText() {
        return $this->text;
    }

    public function setText($text) {
        $this->text = $text;
    }


    function __get($name)
    {
        $methodName = "get" . ucfirst($name);


        if(method_exists($this,$methodName)){
            return  $this->$methodName();
        }else{
            return null;
        }
    }

    
}
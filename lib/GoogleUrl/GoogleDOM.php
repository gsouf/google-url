<?php

// See LICENSE

namespace GoogleUrl;

use \GoogleUrl\AdwordsResultSet;

/**
 * Description of GoogleDOM
 *
 * @author sghzal
 * @license http://www.freebsd.org/copyright/license.html BSD
 */
class GoogleDOM extends \DOMDocument{
    
    
    /**
     * list of natural nodes
     */
    const NATURAL_QUERY="//div[@id = 'ires']/ol/descendant::*[self::div or self::li][@class='g']";
    /**
     * Get natural link (<a> tag) in the natural node context
     */
    const NATURAL_LINKS_IN="descendant::h3[@class='r'][1]/a"; 
    
    
    /**
     * Get adwords nodes
     */
    const RHS_QUERY_COLUMN="//div[@id = 'rhs']//ol/li[@class='ads-ad']"; 
    const RHS_QUERY_BODY="//div[@id = 'tads']//ol/li[@class='ads-ad']"; 
    const RHS_LINK="descendant::h3/a[@onmousedown]"; 
    const RHS_VISURL="descendant::div[@class='ads-visurl']/cite"; 
    const RHS_TEXT="descendant::div[@class='ads-creative']"; 
    
    // we check if there is a form named 'captcha' to detect a bad page
    const CAPTCHA_FORM_XPATH="//input[@name='captcha']";
    
    protected $naturalsResults = null; // used for cache
    protected $adwsResults = null; // used for cache
    protected $xpath;

    // the keyword(s)
    protected $search;
    // the google url
    protected $generatedUrl;
    // date of the search
    protected $date;
    // page result
    protected $page;
    //nb results per pages
    protected $numberResults;

    public function __construct($search,$generatedUrl,$page,$numberResults,$version = null, $encoding = null) {
        parent::__construct($version, $encoding);
        
        $this->search = $search;
        $this->generatedUrl=$generatedUrl;
        $this->date = time();
        $this->page = $page;
        $this->numberResults = $numberResults;

        $this->init();
    }
    
    public function init(){
        $this->naturalsResults=null;
    }
    
    /**
     * get the object xpath to query it
     * @return \DOMXPath
     */
    public function getXpath() {
        if(null === $this->xpath){
            $this->xpath=new \DOMXPath($this);
        }
        return $this->xpath;
    }

    /**
     * detect if the page is a captcha
     * @return bool
     */
    public function isCaptcha(){
        
        return $this->getXpath()->query(self::CAPTCHA_FORM_XPATH)->length > 0;
        
    }
        
    /**
     * gives the list of the natural results
     * @return \DOMNodeList list of naturals results
     */
    public function getNaturals() {
        
        if (null === $this->naturalsResults) {
            $query=self::NATURAL_QUERY;
            $this->naturalsResults=$this->getXpath()->query($query);
        }
        
        return $this->naturalsResults;
    }
    
    /**
     * get the list of the natural results with position, url, title, snippet et matching website
     * @return GooglePosition[] list of positions
     */
    public function getPositions(){
        
        // list of naturals nodes
        $naturals=$this->getNaturals();



        // prepare the query to find url+title into the natural nodes
        $query=self::NATURAL_LINKS_IN;      
        

        
        $positions=array();// we buf results
        $number=1;
        foreach($naturals as $node){
            
            // query to find the tilte/url
            $aTag=$this->getXpath()->query($query,$node);
            //take the first element, because anyway only one can be found
            $aTag=$aTag->item(0);
            

            /* @var $aTag \DOMElement */
            
            if(!$aTag)
                continue;
            
            $url=$aTag->getAttribute("href"); // get the link of the result
            
            if(($protPos=strpos($url, "://"))>0){ //if no protocole it means the result is a an relative path to google. then it means than it is not a true natural result
                $title=$aTag->nodeValue; // get the title of the result
                $shortUrl=  substr($url,$protPos+3); // ltrim the protocol
                $shortUrl=  substr($shortUrl,0,strpos($shortUrl, "/")); // remove all what left after the first /   "google.com/search?..." becomes "google.com"

                $truePosition = $number + ($this->numberResults * $this->page);

                $positions[]=new GooglePosition($this->search, $shortUrl, $this->date, $truePosition, $url, $title, $node->C14N());
                
                $number++;
            }
            
        }
        
        return $positions;
    }
    
    
    /**
     * list of adwords nodes. Please consider using getAdwordsPositions() instead
     * @return \DOMNodeList
     */
    public function getAdwords(){
        
        if(null === $this->adwsResults){
        
            
            $DOMbodyAdwords = $this->getXpath()->query(self::RHS_QUERY_BODY);
            $body = $this->parseAdwords($DOMbodyAdwords, AdwordsResultSet::LOCATION_BODY);
            
            $DOMColumnAdwords = $this->getXpath()->query(self::RHS_QUERY_COLUMN);
            $column = $this->parseAdwords($DOMColumnAdwords, AdwordsResultSet::LOCATION_COLUMN);
            
            $resultSet = new AdwordsResultSet(array_merge($body,$column));
         
            
            return $resultSet;
            
        }
        
        return $this->adwsResults;
        
    }
    
    /**
     * Get the list of adwords positions
     * @return \GoogleUrl\GoogleAdwordPosition[]
     */
    public function parseAdwords(\DOMNodeList $dlist,$location = null){
    
        
        $positions=array();// we buf results
        $number=1;
        
        
        foreach($dlist as $node){
            
            
            // query to find the tilte/url
            $aTag=$this->getXpath()->query(self::RHS_LINK,$node)->item(0);
            /* @var $aTag \DOMElement */

            $visUrlTag = $this->getXpath()->query(self::RHS_VISURL,$node)->item(0);
            /* @var $visUrlTag \DOMElement */
            
            
            $textTag = $this->getXpath()->query(self::RHS_TEXT,$node)->item(0);
            /* @var $textTag \DOMElement */
            
            
            $title = $aTag ?  strip_tags($aTag->textContent) : "";
            $adwordsUrl = $aTag ? $aTag->getAttribute("href") : "";
            $visurl = $visUrlTag ? strip_tags($visUrlTag->textContent) : "";
            $text = $textTag ? strip_tags($textTag->textContent) : "";
            
            
            $position = new GoogleAdwordPosition($this->search, $number, $visurl, $adwordsUrl, $title, $text, $this->date);
            $position->setLocation($location);
            $positions[] = $position;
            
            $number++;
            
        }
        
        return $positions;
    }
    

        /**
     * @return int
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return string
     */
    public function getKeywords()
    {
        return $this->search;
    }

    public function getUrl(){
        return $this->generatedUrl;
    }

    
}

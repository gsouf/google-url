<?php

// See LICENSE

namespace GoogleURL;
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
    const NATURAL_QUERY="//div[@id = 'ires']/ol/li[@class='g'][not(@id) or @id != 'imagebox_bigimages']";
    
    /**
     * Get natural link (<a> tag) in the natural node context
     */
    const NATURAL_LINKS_IN="descendant::h3[@class='r'][1]/a"; 
    
    /**
     * Get snipet into a natural node
     */
    const SNIPPET_IN="div[@class='vsc']/div[@class='s']"; 
    
    protected $naturalsResults;
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
     * gives the list of the natural results
     * @return \DOMNodeList list of naturals results
     */
    public function getNaturals() {
        
        if(null === $this->naturalsResults){
        
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
            $aTag=$this->naturalsResults=$this->getXpath()->query($query,$node);
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
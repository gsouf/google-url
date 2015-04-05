<?php

// See LICENSE

namespace GoogleUrl;

use \GoogleUrl\AdwordsResultSet;
use GoogleUrl\Parser\AdWordsParser;
use GoogleUrl\Parser\Rule\ImageGroupResultRule;
use GoogleUrl\Parser\Rule\InDepthArticleRule;
use GoogleUrl\Parser\Rule\VideoResultRule;

/**
 * Description of GoogleDOM
 *
 * @author sghzal
 * @license http://www.freebsd.org/copyright/license.html BSD
 */
class GoogleDOM extends \DOMDocument{

    
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

    public function __construct($search, $generatedUrl, $page, $numberResults, $version = null, $encoding = null) {
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
     * @return mixed
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @return mixed
     */
    public function getNumberResults()
    {
        return $this->numberResults;
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
    public function getNaturalResults() {
        
        if(null === $this->naturalsResults){

            $parser = new \GoogleUrl\Parser\NaturalParser();
            $parser->addRule(new \GoogleUrl\Parser\Rule\ClassicalResultRule());
            $parser->addRule(new \GoogleUrl\Parser\Rule\ClassicalResultGroupRule());
            $parser->addRule(new \GoogleUrl\Parser\Rule\InTheNewsRule());
            $parser->addRule(new VideoResultRule());
            $parser->addRule(new ImageGroupResultRule());
            $parser->addRule(new InDepthArticleRule());
            $this->naturalsResults = $parser->parse($this);

        }
        
        return $this->naturalsResults;
    }
    

    
    
    /**
     * list of adwords nodes. Please consider using getAdwordsPositions() instead
     * @return Ad
     */
    public function getAdWordsResults(){
        
        if(null === $this->adwsResults){

            $parser = new AdWordsParser();

            $resultSet = $parser->parse($this);
            
            $this->adwsResults = $resultSet;
            
        }
        
        return $this->adwsResults;
        
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
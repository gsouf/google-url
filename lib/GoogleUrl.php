<?php

// See License

use \GoogleUrl\GoogleDOM;


/**
 * Description of GoogleUrl
 *
 * @author sghzal
 * @license http://www.freebsd.org/copyright/license.html BSD
 */
class GoogleUrl{

    /** SEARCH PARAMS CONSTANTS */
    const  PARAM_NBRESULTS="num";
    /** END SEARCH PARAMS CONSTANTS */


    /** CONSTANTS OF LANG **/
    // french
    const HL_FR="fr";
    const LR_FR="lang_fr";
    const TLD_FR="fr";
    const ACCEPT_FR="fr;q=0.8";
    // english
    const HL_EN="en";
    const LR_EN="lang_en";
    const TLD_EN="com";
    const ACCEPT_EN="en-us,en;q=0.8";
    /** END CONSTANTS OF LANG **/


    protected $tld;
    protected $acceptLangage;

    protected $googleParams;
    
    public function __construct() {
        $this->init();
    }
    
    /**
     * Reset all params to default :
     *      
     *       "q" => "",                      // Search Query
     * 
     *       "start" => 0,                   // First result number
     * 
     *       "num" => 10,                    // Number of results per pages
     * 
     *       "complete" => 0,                // Suggestion auto
     * 
     *       "pws" => 0,                     // Personnal search
     * 
     *       "hl" => "en",                   // Interface langage
     * 
     *       "lr" => "lang_en",              // Results Langage
     *      
     *       TLD => "com"
     */
    public function init(){
        
        $this->googleParams=[
            
            "q" => "",                      // Search Query
            "start" => 0,                   // First result number
            "num" => 10,                    // Number of results per pages
            "complete" => 0,                // Suggestion auto
            "pws" => 0,                     // Personnal search
            "hl" =>  self::HL_EN,           // Interface langage
            "lr" =>  self::LR_EN,          // Results Langage


            
        ];
        $this->acceptLangage=self::ACCEPT_EN;
        $this->setTld("com");
    }
    
    /**
     * ask if a langage is configured 
     * @param string $iso the iso code of the country. e.g  english : "en" , france : "fr"
     * @return boolean true if available
     */
    public static function langageIsAvailable($iso){
        $hl="HL_".strtoupper($iso);
        
        return defined("self::".$hl);
    }
    
    /**
     * Set the lang to the given (iso formated) lang. This will modify the params hl and lr
     * @param string $iso the iso code of the country. e.g  english : "en" , france : "fr"
     * @param boolean $setTld change the tld to matching with the langage. Default to true
     * @return \Peek\Net\Google\GoogleUrl this instance
     * @throws Exception
     */
    public function setLang($iso,$setTld=true){
               
        if(self::langageIsAvailable($iso)){
            $hl="HL_".strtoupper($iso);
            $lr="LR_".strtoupper($iso);
            $accept="ACCEPT_".strtoupper($iso);
            
            
            $this->setParam("hl", constant("self::".$hl))
                 ->setParam('lr', constant("self::".$lr));
            
            $this->acceptLangage=constant("self::".$accept);
            
            if($setTld){
                $tld="TLD_".strtoupper($iso);
                $this->setTld(constant("self::".$tld));
            }
            
        }else{
            throw new \Exception("Unknown lang '".$iso."'");
        }
            
        return $this;
    }

    
    /**
     * 
     * @param string $tld google tld "com","fr","co.uk"
     * @return \GoogleURL\GoogleUrl
     */
    public function setTld($tld){
        $this->tld=trim($tld," .");
        return $this;
    }
    
    /**
     * Set terms to search but doesnt launch the search
     * @param string $search set the string to search
     * @return GoogleUrl
     */
    public function searchTerm($search){
        return $this->setParam("q",$search);
    }
    
    /**
     * 
     * @param string $name name of the param
     * @param string $value value of the param
     * @return \GoogleUrl
     */
    private function setParam($name,$value){
        $this->googleParams[$name]=$value;
        
        return $this;
    }

    /**
     * get a param by its name
     * @param string $name the param to get
     * @return string
     */
    private function param($name){
        return $this->googleParams[$name];
    }


    /**
     * check if param isset
     * @param string $name the param to get
     * @return string
     */
    private function hasParam($name){
        return isset($this->googleParams[$name]);
    }




    /**
     * Set which page to query. Between 0 and 100
     * @param int $n the number of the page. Begins to 0
     * @return GoogleUrl this instance
     */
    public function setPage($n){
        $this->setParam("start", $this->param("num")*$n);
        return $this;
    }

    public function getPage(){
        return $this->param("start")/$this->param("num");
    }

    /**
     * Set how many results per page between 1 and 100
     * Will also update the start param to match the page number
     * @param int $n the number of the page. Begins to 0
     * @return GoogleUrl this instance
     */
    public function setNumberResults($n){

        $page=$this->getPage();

        $this->setParam(self::PARAM_NBRESULTS, $n);
        
        $this->setPage($page);
        
        return $this;
    }
    
    /**
     * Launch a google Search
     * @param string $searchTerm the string to search. Or if not specified will take the given with ->searchTerm($search)
     * @return GoogleDOM the Google DOMDocument
     * @throws Exception
     */
    public function search($searchTerm=null){

        /**======================
         * CHANGE SEARCH IF NEEDED
          ========================*/
        if(null !== $searchTerm)
            $this->searchTerm($searchTerm);
        else
            if( ! strlen($this->param("q"))>0 )
                throw new Exception ("Nothing to Search");

        /**=========
         * INIT CURL
          =========*/
        $c = new \Peek\Net\Curl();
        $c->url=$this->__toString();


        /**==========
         * DO HEADERS
          ===========*/
        // let's be redirected if needed
        $c->followLocation();
        // use a true user agent, maybe better for true results
        $c->useragent="Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.22 (KHTML, like Gecko) Ubuntu Chromium/25.0.1364.160 Chrome/25.0.1364.160 Safari/537.22";

        // use other headers

        // accept-langage to make sure google use the same language as asked
        $header[]="Accept-Language: ".$this->acceptLangage;

        $c->HTTPHEADER=$header;


        /**========
         * EXECUTE
          =========*/
        $r=$c->exec();
        if(!$r)
            throw new \Exception ("HTTP query failled with the following URL : ".$this);

        /**===============
         * CREATE DOCUMENT
          ================*/
        $doc=new GoogleDOM($this->param("q"),$this->getUrl(),$this->getPage(),$this->param(self::PARAM_NBRESULTS));
        libxml_use_internal_errors(TRUE);
        $doc->loadHTML($r);
        libxml_use_internal_errors(FALSE);
        libxml_clear_errors();

        return $doc;
    }
    
    /**
     * get the generated url
     * @return string the generated url
     */
    public function getUrl(){
        return $this->__toString();
    }
    
    /**
     * Same as gerUrl
     * @return string the generated url
     */
    public function __toString() {
        
        $url="https://google.".$this->tld."/search?".http_build_query($this->googleParams);
        
        return $url;
        
    }

}
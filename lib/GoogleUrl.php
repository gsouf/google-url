<?php

// See License

use \GoogleUrl\GoogleDOM,
 GoogleUrl\ProxyDefinition;

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

    /**
     * @var \GoogleUrl\Language
     */
    protected $language;

    protected $tld;
    protected $acceptLangage;

    protected $googleParams;
    
    protected $userAgent = "Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2049.0 Safari/537.36";

    protected $enableLr = true;

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
        
        $this->googleParams=array(
            
            "q" => "",                      // Search Query
            "start" => 0,                   // First result number
            "num" => 10,                    // Number of results per pages
            "complete" => 0,                // Suggestion auto
            "pws" => 0                      // Personnal search

        );

        $this->setLang("en");

    }
    

    
    /**
     * Set the lang to the given (iso formated) lang. This will modify the params hl and lr
     * @param string $iso the iso code of the country. e.g  english : "en" , france : "fr"
     * @param boolean $setTld change the tld to matching with the langage. Default to true
     * @return GoogleUrl
     * @throws Exception
     */
    public function setLang($lang){

        if(is_string($lang)){
            $this->language = \GoogleUrl\Language::buildFromIso($lang);
        }else if(is_object($lang) && $lang instanceof \GoogleUrl\Language){
            $this->language = $lang;
        }else{
            throw new \GoogleUrl\Exception("Bad parameter type for Google::setLang. It should be either a string or a Language instance");
        }
            
        return $this;
    }
    
    public function enableLr($enabled = true){
        $this->enableLr = $enabled;
        if(!$this->enableLr){
            if($this->hasParam("lr")) {
                $this->setParam('lr', null);
            }
        }
    }

    
    /**
     * 
     * @param string $tld google tld "com","fr","co.uk"
     * @return \GoogleURL
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
    public function setSearchTerm($search){
        return $this->setParam("q",$search);
    }
    
    /**
     * 
     * @param string $name name of the param
     * @param string $value value of the param
     * @return \GoogleUrl
     */
    public function setParam($name,$value){
        if(null === $value){
            if($this->googleParams[$name]){
                unset($this->googleParams[$name]);
            }
        }else
            $this->googleParams[$name]=$value;
        
        return $this;
    }

    /**
     * get a param by its name
     * @param string $name the param to get
     * @return string
     */
    public function param($name){
        return $this->googleParams[$name];
    }


    /**
     * check if param isset
     * @param string $name the param to get
     * @return string
     */
    public function hasParam($name){
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
    
    public function setUserAgent($userAgent) {
        $this->userAgent = $userAgent;
    }

        
    /**
     * Launch a google Search
     * @param string $searchTerm the string to search. Or if not specified will take the given with ->searchTerm($search)
     * @param \GoogleUrl\SimpleProxyInterface $options an optional proxy for the query
     *                       
     * @return GoogleDOM the Google DOMDocument
     * @throws Exception
     * @throws \GoogleUrl\Exception\CaptchaException google detected us as a bot
     */
    public function search($searchTerm=null, \GoogleUrl\SimpleProxyInterface $proxy = null){
        
    
        /**======================
         * CHANGE SEARCH IF NEEDED
          ========================*/
        if (null !== $searchTerm) {
            $url = $this->getUrl(array(
                "q" => $searchTerm
            ));
        } else {
            if (!strlen($this->param("q")) > 0) {
                throw new \GoogleUrl\Exception ("Nothing keyword to Search");
            }
            $url = $this->getUrl();
            $searchTerm = $this->param("q");
        }



        /**=========
         * INIT CURL
          =========*/
        $c = new \GoogleUrl\Curl();
        $c->url = $url;


        /**==========
         * DO HEADERS
          ===========*/
        // let's be redirected if needed
        $c->followLocation();
        // use a true user agent, maybe better for true results
        $c->useragent = $this->userAgent;

        // use other headers

        // accept-langage to make sure google use the same language as asked
        $header[]="Accept-Language: ". ($this->acceptLangage ? $this->acceptLangage : $this->language->getAccept());

        $c->HTTPHEADER=$header;

        
        /**=========
         * SET PROXY
           =========*/
        if($proxy){
            $c->proxy = $proxy->getIp();
            $c->proxyport = $proxy->getPort();
            
            $login = $proxy->getLogin();
            if($login){
                $auth = $login;
                $psw = $proxy->getPassword();
                if($psw){
                    $auth .= ":" . $psw;
                }
                $c->proxyuserpwd = $auth;
            }
            
            
            $proxyType = $proxy->getProxyType();
            $c->proxytype = $proxyType ? $proxyType : "http";
  
        }
        

        /**========
         * EXECUTE
          =========*/
        $r = $c->exec();
        
        if(false === $r){
            
            $errno = $c->errno();
            
            if(CURLE_COULDNT_RESOLVE_PROXY == $errno){
                throw new \GoogleUrl\Exception\ProxyException("HTTP query failled [curl-error : $errno - " . $c->error() . " ] for the following URL : ".$this);
            }else{
                throw new \GoogleUrl\Exception\CurlException ("HTTP query failled [curl-error : $errno - " . $c->error() . " ] for the following URL : ".$this);
            }
            
        }
        
        /**===============
         * CREATE DOCUMENT
          ================*/
        $doc = new GoogleDOM($searchTerm,$url,$this->getPage(),$this->param("num"));
        libxml_use_internal_errors(TRUE);
        $doc->loadHTML($r);
        libxml_use_internal_errors(FALSE);
        libxml_clear_errors();
        
        if($doc->isCaptcha())
            throw new \GoogleUrl\Exception\CaptchaException();

        return $doc;
    }
    
    /**
     * get the generated url
     * @return string the generated url
     */
    public function getUrl($overrides = array()){

        if($this->language){

            $langParams = array(
                "lr" => $this->language->getLr(),
                "hl" => $this->language->getHl(),
            );

            $params = array_merge($langParams,$this->googleParams);

        }else{
            $params = $this->googleParams;
        }

        $params = array_merge($params, $overrides);


        if(isset($params["num"])){
            if($params["num"] == 10 ){
                unset($params["num"]);
            }
        }

        if(isset($params["start"])){
            if($params["start"] == 0 ){
                unset($params["start"]);
            }
        }

        if(false == $this->enableLr){
            unset($params["lr"]);
        }

        return "https://www.google.". ($this->tld ? $this->tld : $this->language->getDefaultTld()) ."/search?".http_build_query($params);

    }

    /**
     * Same as gerUrl
     * @return string the generated url
     */
    public function __toString() {

        return $this->getUrl();
        

    }

}

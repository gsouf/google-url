<?php

namespace GoogleUrl;


class Language {

    protected $iso;
    protected $hl;
    protected $lr;
    protected $defaultTld;
    protected $accept;

    function __construct($iso, $hl, $lr, $defaultTld, $accept)
    {
        $this->iso = $iso;
        $this->hl = $hl;
        $this->lr = $lr;
        $this->defaultTld = $defaultTld;
        $this->accept = $accept;
    }

    /**
     * @return mixed
     */
    public function getHl()
    {
        return $this->hl;
    }

    /**
     * @param mixed $hl
     */
    public function setHl($hl)
    {
        $this->hl = $hl;
    }

    /**
     * @return mixed
     */
    public function getLr()
    {
        return $this->lr;
    }

    /**
     * @param mixed $lr
     */
    public function setLr($lr)
    {
        $this->lr = $lr;
    }

    /**
     * @return mixed
     */
    public function getDefaultTld()
    {
        return $this->defaultTld;
    }

    /**
     * @param mixed $defaultTld
     */
    public function setDefaultTld($defaultTld)
    {
        $this->defaultTld = $defaultTld;
    }

    /**
     * @return mixed
     */
    public function getAccept()
    {
        return $this->accept;
    }

    /**
     * @param mixed $accept
     */
    public function setAccept($accept)
    {
        $this->accept = $accept;
    }





    public static function buildFromIso($iso){


        $langs = array(

            // french
            "fr" => array(
                "hl" => "fr",
                "lr" => "lang_fr",
                "tld"=> "fr",
                "accept" => "fr;q=0.8"
            ),

            // english
            "en" => array(
                "hl" => "en",
                "lr" => "lang_en",
                "tld"=> "com",
                "accept"=> "en-us,en;q=0.8"
            ),

            // german
            "de" => array(
                "hl" => "de",
                "lr" => "lang_de",
                "tld"=> "de",
                "accept"=> "de;q=0.8"
            ),

            // dutch
            "nl" => array(
                "hl" => "nl",
                "lr" => "lang_nl",
                "tld"=> "nl",
                "accept"=> "nl;q=0.8"
            ),

            // Czech
            "cs" => array(
                "hl" => "cs",
                "lr" => "lang_cs",
                "tld"=> "com",
                "accept"=> "cs;q=0.8"
            ),

            // Danish
            "da" => array(
                "hl" => "da",
                "lr" => "lang_da",
                "tld"=> "dk",
                "accept"=> "da;q=0.8"
            ),

            // Japanese
            "jp" => array(
                "hl" => "ja",
                "lr" => "lang_ja",
                "tld"=> "co.jp",
                "accept"=> "ja;q=0.8"
            ),

            // Spanish
            "es" => array(
                "hl" => "es",
                "lr" => "lang_es",
                "tld"=> "es",
                "accept"=> "es;q=0.8"
            ),

            // Russian
            "ru" => array(
                "hl" => "ru",
                "lr" => "lang_ru",
                "tld"=> "ru",
                "accept"=> "ru;q=0.8"
            )


        );



        if(isset($langs[$iso])){

            return new self($iso, $langs[$iso]["hl"], $langs[$iso]["lr"], $langs[$iso]["tld"], $langs[$iso]["accept"]);

        }else{
            throw new Exception("Language $iso is not implemented");
        }


    }


}
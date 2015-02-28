<?php

namespace GoogleUrl\Result;


class ImageResult extends ClickableResult {

    protected $parsedTargetUrl=null;
    protected $parsedWebsite = null;
    protected $parsedImageUrl  = null;

    public function getType()
    {
        return 'image';
    }

    private function _parseUrl(){
        $originalUrl = $this->targetUrl;

        $preparse = parse_url($originalUrl);
        parse_str($preparse['query'], $params);

        $this->parsedTargetUrl = $params["imgrefurl"];
        $this->parsedImageUrl  = $params["imgurl"];
    }

    public function getTargetUrl(){

        if(null === $this->parsedTargetUrl){
            $this->_parseUrl();
        }

        return  $this->parsedTargetUrl;

    }

    public function getWebsite(){
        if(null === $this->parsedWebsite){
            $this->_parseUrl();
            $this->parsedWebsite = $this->_extractDomain($this->parsedTargetUrl);
        }

        return  $this->parsedWebsite;
    }

    public function getImageUrl(){
        if(null === $this->parsedImageUrl){
            $this->_parseUrl();
        }

        return  $this->parsedImageUrl;
    }


}
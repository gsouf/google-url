<?php


class UrlTest  extends PHPUnit_Framework_TestCase{
    
   
    
    public function testUrlGeneration(){
        
        $googleUrl=new \GoogleUrl();
        
        $googleUrl->setLang('fr')->setNumberResults(10)->searchTerm("simpsons");

        $this->assertEquals("https://www.google.fr/search?q=simpsons&start=0&num=10&complete=0&pws=0&hl=fr&lr=lang_fr", $googleUrl->__toString());

        $googleUrl->setRawParam("uule", "w+CAIQICIJTmV3IERlbGhp");


        $this->assertEquals("https://www.google.fr/search?q=simpsons&start=0&num=10&complete=0&pws=0&hl=fr&lr=lang_fr&uule=w+CAIQICIJTmV3IERlbGhp", $googleUrl->__toString());

    }
    
}
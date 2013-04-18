<?php


class NetTest  extends PHPUnit_Framework_TestCase{
    
   
    
    public function testQuery(){
        
        
        $googleUrl=new \GoogleURL\GoogleUrl();
        var_dump($googleUrl->setLang('fr')->setNumberResults(20)->search("bob")->getPositions());
        
    }
    
}
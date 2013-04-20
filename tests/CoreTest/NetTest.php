<?php


class NetTest  extends PHPUnit_Framework_TestCase{
    
   
    
    public function testQuery(){
        
        
        $googleUrl=new \GoogleURL\GoogleUrl();
        echo "Number rusults : ".(count($googleUrl->setLang('fr')->setNumberResults(10)->search("acdc")->getPositions()));
        
        $this->assertEquals(true,  GoogleURL\GoogleUrl::langageIsAvailable("fr"));
        $this->assertEquals(true,  GoogleURL\GoogleUrl::langageIsAvailable("en"));
        
        $this->assertEquals(false,  GoogleURL\GoogleUrl::langageIsAvailable("rf"));
        
    }
    
}
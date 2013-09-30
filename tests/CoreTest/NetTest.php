<?php


class NetTest  extends PHPUnit_Framework_TestCase{
    
   
    
    public function testQuery(){

        $googleUrl=new \GoogleUrl\DelayedQuery();

        $this->assertEquals(10, count($googleUrl->setLang('fr')->setNumberResults(10)->search("acdc")->getPositions()));
        $this->assertEquals(10, count($googleUrl->setLang('fr')->setNumberResults(10)->setPage(2)->search("acdc")->getPositions()));

        $this->assertEquals(true,  GoogleUrl::langageIsAvailable("fr"));
        $this->assertEquals(true,  GoogleUrl::langageIsAvailable("en"));
        
        $this->assertEquals(false,  GoogleUrl::langageIsAvailable("rf"));
        
    }
    
}
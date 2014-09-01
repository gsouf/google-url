<?php


class NetTest  extends PHPUnit_Framework_TestCase{
    
   
    
    public function testQuery(){
        
        $googleUrl=new \GoogleUrl\DelayedQuery();
        
        $firstSearch = $googleUrl->setLang('fr')->setNumberResults(10)->search("simpson tshirt");
        
        $adwords = $firstSearch->getAdwords();        
        
        $this->assertTrue(is_array($adwords->getBodyResults()));
        $this->assertTrue(is_array($adwords->getColumnResults()));
        
        $this->assertTrue(count($adwords)>0);
        $this->assertEquals(10, count($firstSearch->getPositions()));
        $this->assertEquals(10, count($googleUrl->setLang('fr')->setNumberResults(10)->setPage(2)->search("acdc")->getPositions()));

        $this->assertEquals(true,  GoogleUrl::langageIsAvailable("fr"));
        $this->assertEquals(true,  GoogleUrl::langageIsAvailable("en"));
        
        $this->assertEquals(false,  GoogleUrl::langageIsAvailable("rf"));
        
    }
    
}
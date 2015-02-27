<?php


class NetTest  extends PHPUnit_Framework_TestCase{


    /**
     * @group bla
     */
    public function testQuery(){


        $googleUrl = new GoogleUrl();
        $googleUrl->setLang("fr")->setTld("com.au");
        $dom = $googleUrl->search("charlie hebdo");

        $parsed = $dom->getNaturals();

        echo $dom->getUrl();

        echo PHP_EOL . PHP_EOL;

        foreach($parsed as $item){

            if($item->type == "classical"){
                echo $item->position . " " . $item->title;
            }else if($item->type == "inTheNews" ){
                echo $item->position . " [IN THE NEWS]";
                foreach($item->getItems() as $card){
                    echo PHP_EOL . "   " . $card->position . " " . $card->title;
                }
            }
            echo PHP_EOL;
        }

        return;

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
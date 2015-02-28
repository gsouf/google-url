<?php


class NetTest  extends PHPUnit_Framework_TestCase{


    /**
     * @group bla
     */
    public function testQuery(){


        $googleUrl = new GoogleUrl();
        $googleUrl->setLang("en")->enableLr(false);
        $dom = $googleUrl->search("simpsons mug");

        echo $dom->getUrl();

        $parsed = $dom->getNaturalResults();


        echo PHP_EOL . PHP_EOL;

        foreach($parsed as $item){

            if($item->is("classical")){

                echo $item->position . " " . $item->title . " ";
                echo PHP_EOL . "  url -> (" . $item->website . ")" . $item->targetUrl ;

            }else if($item->is("inTheNewsGroup")){
                echo $item->getPosition() . " [IN THE NEWS]";
                foreach($item->getItems() as $card){
                    echo PHP_EOL . "   " . $card->position . " " . $card->title ;
                    echo PHP_EOL . "     target-> (" . $card->website . ") " . $card->targetUrl ;
                }
            }else if($item->is("video")){
                echo $item->getPosition() . " [VIDEO] " . $item->title;
                echo PHP_EOL . "  url -> (" . $item->website . ")" . $item->targetUrl ;
            }else if($item->is("imageGroup")){
                echo $item->getPosition() . " [IMAGE GROUP] " ;
                foreach($item->getItems() as $card){
                    echo PHP_EOL . "   " . $card->position . " " . $card->imageUrl ;
                    echo PHP_EOL . "     target->(" . $card->website . ") " . $card->targetUrl ;
                }
            }else if($item->is("inDepthArticleGroup")){
                echo $item->getPosition() . " [IN DEPTH ARTICLES] " ;
                foreach($item->getItems() as $card){
                    echo PHP_EOL . "   " . $card->position . " " . $card->title ;
                    echo PHP_EOL . "     target->(" . $card->website . ") " . $card->targetUrl ;
                }
            }else{
                echo "Unknown type : " . $item->getType();
            }
            echo PHP_EOL;
        }


        echo PHP_EOL;
        echo PHP_EOL;
        echo "ADWORDS ====";
        echo PHP_EOL;

        $parsedAdws = $dom->getAdWordsResults();

        foreach($parsedAdws as $adwords){

            echo $adwords->position . " in " . $adwords->pageLocation;
            echo PHP_EOL . "title : " . $adwords->title;
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
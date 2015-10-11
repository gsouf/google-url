<?php


class CaptchaTest  extends PHPUnit_Framework_TestCase{


    public function testSolver(){

        $googleUrl = new GoogleUrl();

        for($i = 0; $i < 1 ; $i++){

            try{
                $googleDom = $googleUrl->search("simpsons" . $i);
                $naturals = $googleDom->getNaturalResults();
            }catch (\GoogleUrl\Exception\CaptchaException $e){
                $solver = new GoogleUrl\CaptchaSolver\ManualSolver();
                $id = $e->getCaptchaPage()->getId();
                $image = $e->getCaptchaPage()->getImage();
                $text = $solver->solve($image);

                $captchaSender = new \GoogleUrl\CaptchaSolver\CaptchaSender();
                $captchaSender->send($id, $text, null);

            }

        }

        
    }
    
}
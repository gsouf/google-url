<?php

namespace GoogleUrl\Parser\Rule;


use GoogleUrl\GoogleDOM;
use GoogleUrl\Result\ClassicalResult;
use GoogleUrl\Result\ResultSetInterface;

class ClassicalResultRule extends AbstractNaturalRule {


    public function match(\DOMElement $node){

        if($node->getAttribute("class") == "srg" ){
            return self::RULE_MATCH_MATCHED;
        }

        return self::RULE_MATCH_IGNORE;

    }

    public function parseGroup(GoogleDOM $googleDOM, \DomElement $group, ResultSetInterface $resultSet, $currentPosition){

        $xpath = $googleDOM->getXpath();

        $results = $xpath->query("descendant::li[@class='g']",$group);

        foreach($results as $searchItem){

            // query to find the tilte/url
            /* @var $aTag \DOMElement */
            $aTag=$xpath->query("descendant::h3[@class='r'][1]/a",$searchItem)
                  //take the first element, because anyway only one can be found
                ->item(0);

            if (!$aTag) {
                continue;
            }

            $url=$aTag->getAttribute("href"); // get the link of the result

            // if no protocole it means the result is a an relative path to google.
            // then it means than it is not a true natural result
            // it mays be a link or something else visual that we are not interested in
            if(($protPos=strpos($url, "://"))>0){
                $title=$aTag->nodeValue; // get the title of the result
                $shortUrl=  substr($url,$protPos+3); // ltrim the protocol
                $shortUrl=  substr($shortUrl,0,strpos($shortUrl, "/")); // remove all what left after the first /

                //   "https://google.com/search?..." becomes "google.com"

                $currentPosition++;
                $truePosition = $currentPosition + ($googleDOM->getNumberResults() * $googleDOM->getPage());

                $item = new ClassicalResult();
                $item->setPosition($truePosition);
                $item->setSnippet($searchItem->C14N());
                $item->setTitle($title);
                $item->setUrl($url);
                $item->setWebsite($shortUrl);

                $resultSet->addItem($item);
            }
        }
        return $currentPosition;
    }

}
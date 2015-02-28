<?php

namespace GoogleUrl\Parser\Rule;


use GoogleUrl\GoogleDOM;
use GoogleUrl\Result\ResultSetInterface;
use GoogleUrl\Result\VideoResult;

class VideoResultRule extends  AbstractNaturalRule{


    public function match(\DOMElement $node)
    {

        if($node->getAttribute("class") == "g mnr-c g-blk"){
            return self::RULE_MATCH_MATCHED;
        }else{
            return self::RULE_MATCH_NOMATCH;
        }

    }

    public function parseGroup(GoogleDOM $googleDOM, \DomElement $itemDom, ResultSetInterface $resultSet, $currentPosition)
    {

        $xpath = $googleDOM->getXpath();

        $aTag=$xpath->query("descendant::h3[@class='r'][1]/a",$itemDom)->item(0);

        $url=$aTag->getAttribute("href"); // get the link of the result

        $title=$aTag->nodeValue; // get the title of the result

        $videoItem = new VideoResult();
        $videoItem->setTargetUrl($url);
        $videoItem->setTitle($title);

        $currentPosition++;
        $videoItem->setPosition($currentPosition);

        $resultSet->addItem($videoItem);

        return $currentPosition;

    }


}
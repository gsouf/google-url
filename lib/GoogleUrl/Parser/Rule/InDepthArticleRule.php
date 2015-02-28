<?php

namespace GoogleUrl\Parser\Rule;


use GoogleUrl\GoogleDOM;
use GoogleUrl\Result\InDepthArticleGroupResult;
use GoogleUrl\Result\InDepthArticleResult;
use GoogleUrl\Result\InTheNewsGroupResult;
use GoogleUrl\Result\InTheNewsResult;
use GoogleUrl\Result\ResultSetInterface;

class InDepthArticleRule extends AbstractNaturalRule {

    public function match(\DOMElement $node)
    {
        if($node->getAttribute("class") == "r-search-3" ){
            return self::RULE_MATCH_MATCHED;
        }

        return self::RULE_MATCH_NOMATCH;
    }

    public function parseGroup(GoogleDOM $googleDOM, \DomElement $group, ResultSetInterface $resultSet, $currentPosition)
    {
        $currentPosition++;

        $item = new InDepthArticleGroupResult();
        $item->setPosition($currentPosition);
        $resultSet->addItem($item);

        $xpathCards = "li[contains(concat(' ',normalize-space(@class),' '),' card-section ')]";
        $cardNodes = $googleDOM->getXpath()->query($xpathCards,$group);

        $cardPosition = 1;
        foreach($cardNodes as $cardNode){
            $card = $this->_parseItem($googleDOM, $cardNode);

            $card->setPosition($cardPosition);
            $item->addItem($card);
            $cardPosition++;
        }

        return $currentPosition;

    }


    /**
     * @param GoogleDOM $googleDOM
     * @param \DomElement $node
     * @return InTheNewsResult
     */
    protected function _parseItem(GoogleDOM $googleDOM, \DomElement $node){

        $xpathTitle = "descendant::h3[@class = 'r']/a";

        $aTag = $googleDOM->getXpath()->query($xpathTitle, $node)->item(0);

        $title = $aTag->nodeValue;

        $targetUrl = $aTag->getAttribute("href");

        $card = new InDepthArticleResult();
        $card->setTitle($title);
        $card->setTargetUrl($targetUrl);

        return $card;



    }

}
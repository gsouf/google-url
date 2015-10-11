<?php

namespace GoogleUrl\Parser\Rule;

use GoogleUrl\GoogleDOM;
use GoogleUrl\Result\InTheNewsGroupResult;
use GoogleUrl\Result\InTheNewsResult;
use GoogleUrl\Result\ResultSetInterface;

class InTheNewsRule extends AbstractNaturalRule
{

    public function match(\DOMElement $node)
    {

        $child = $node->firstChild;

        if (!$child || !($child instanceof \DOMElement)) {
            return self::RULE_MATCH_NOMATCH;
        }

        if ($child->getAttribute("class") == "mnr-c _yE") {
            return self::RULE_MATCH_MATCHED;
        }

        return self::RULE_MATCH_NOMATCH;
    }

    public function parseGroup(GoogleDOM $googleDOM, \DomElement $group, ResultSetInterface $resultSet, $currentPosition)
    {
        $currentPosition++;
        $truePosition = $currentPosition + ($googleDOM->getNumberResults() * $googleDOM->getPage());

        $item = new InTheNewsGroupResult();
        $item->setPosition($truePosition);
        $resultSet->addItem($item);

        $xpathCards = "li[contains(concat(' ',normalize-space(@class),' '),' card-section ')]";
        $cardNodes = $googleDOM->getXpath()->query($xpathCards, $group);

        $cardPosition = 1;
        foreach ($cardNodes as $cardNode) {
            $card = $this->_parseItem($googleDOM, $cardNode);
            $card->setIsBig(true);

            $card->setPosition($cardPosition);
            $item->addItem($card);
            $cardPosition++;
        }


        $xpathCardsLittle = "div/li[contains(concat(' ',normalize-space(@class),' '),' card-section ')]";
        $cardNodesLittle = $googleDOM->getXpath()->query($xpathCardsLittle, $group);
        foreach ($cardNodesLittle as $cardNode) {
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
    protected function _parseItem(GoogleDOM $googleDOM, \DomElement $node)
    {

        $xpathTitle = "descendant::*[@class = '_Dk']";

        $aTag = $googleDOM->getXpath()->query($xpathTitle, $node)->item(0);

        $title = $aTag->nodeValue;

        $targetUrl = $aTag->getAttribute("href");

        $card = new InTheNewsResult();
        $card->setTitle($title);
        $card->setTargetUrl($targetUrl);


        return $card;



    }
}

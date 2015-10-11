<?php

namespace GoogleUrl\Parser\Rule;

use GoogleUrl\GoogleDOM;
use GoogleUrl\Result\ImageGroupResult;
use GoogleUrl\Result\ImageResult;
use GoogleUrl\Result\ResultSetInterface;
use GoogleUrl\Result\VideoResult;

class ImageGroupResultRule extends AbstractNaturalRule
{


    public function match(\DOMElement $node)
    {

        if ($node->hasAttribute("id") && $node->getAttribute("id") == "imagebox_bigimages") {
            return self::RULE_MATCH_MATCHED;
        } else {
            return self::RULE_MATCH_NOMATCH;
        }

    }

    public function parseGroup(GoogleDOM $googleDOM, \DomElement $group, ResultSetInterface $resultSet, $currentPosition)
    {

        $currentPosition++;

        $item = new ImageGroupResult();
        $item->setPosition($currentPosition);
        $resultSet->addItem($item);

        $xpathCards = "descendant::ul[@class='rg_ul']/li/div/a";
        $imageNodes = $googleDOM->getXpath()->query($xpathCards, $group);

        $imagePosition = 1;
        foreach ($imageNodes as $imgNode) {
            $img = $this->_parseItem($googleDOM, $imgNode);
            $img->setPosition($imagePosition);
            $item->addItem($img);
            $imagePosition++;
        }

        return $currentPosition;

    }


    /**
     * @param GoogleDOM $googleDOM
     * @param \DOMElement $imgNode
     * @return ImageResult
     */
    protected function _parseItem(GoogleDOM $googleDOM, \DOMElement $imgNode)
    {

        $targetUrl = $imgNode->getAttribute("href");

        $item = new ImageResult();
        $item->setTargetUrl($targetUrl);

        return $item;


    }
}

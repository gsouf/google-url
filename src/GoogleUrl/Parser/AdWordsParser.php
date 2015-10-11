<?php

namespace GoogleUrl\Parser;

use GoogleUrl\AdWordsResultSet;
use GoogleUrl\AdWordsPosition;
use GoogleUrl\GoogleDOM;

class AdWordsParser
{

    /**
     * @param GoogleDOM $googleDOM
     * @return AdwordsResultSet
     */
    public function parse(GoogleDOM $googleDOM)
    {

        $DOMbodyAdwords = $googleDOM->getXpath()->query("//div[@id = 'tads']//ol/li[@class='ads-ad']");
        $body = $this->_parse($DOMbodyAdwords, $googleDOM, AdWordsResultSet::LOCATION_BODY_TOP);

        $DOMColumnAdwords = $googleDOM->getXpath()->query("//div[@id = 'rhs']//ol/li[@class='ads-ad']");
        $column = $this->_parse($DOMColumnAdwords, $googleDOM, AdWordsResultSet::LOCATION_COLUMN);

        $DOMBottomAdwords = $googleDOM->getXpath()->query("//div[@id = 'tadsb']//ol/li[@class='ads-ad']");
        $bottom = $this->_parse($DOMBottomAdwords, $googleDOM, AdWordsResultSet::LOCATION_BODY_BOTTOM);

        return new AdWordsResultSet(array_merge($body, $column, $bottom));

    }



    /**
     * Get the list of adwords positions
     * @return \GoogleUrl\AdWordsPosition[]
     */
    protected function _parse(\DOMNodeList $dlist, GoogleDOM $googleDOM, $location = null)
    {

        $positions=[];// we buf results
        $number=1;

        foreach ($dlist as $node) {
            // query to find the tilte/url
            /* @var $aTag \DOMElement */
            $aTag=$googleDOM->getXpath()->query("descendant::h3/a[@onmousedown]", $node)->item(0);

            /* @var $visUrlTag \DOMElement */
            $visUrlTag = $googleDOM->getXpath()->query("descendant::div[@class='ads-visurl']/cite", $node)->item(0);


            /* @var $textTag \DOMElement */
            $textTag = $googleDOM->getXpath()->query("descendant::div[@class='ads-creative']", $node)->item(0);


            $title = $aTag ?  strip_tags($aTag->textContent) : "";
            $adwordsUrl = $aTag ? $aTag->getAttribute("href") : "";
            $visurl = $visUrlTag ? strip_tags($visUrlTag->textContent) : "";
            $text = $textTag ? strip_tags($textTag->textContent) : "";


            $position = new AdWordsPosition();
            $position->setPosition($number);
            $position->setVisurl($visurl);
            $position->setAdwordsUrl($adwordsUrl);
            $position->setTitle($title);
            $position->setText($text);

            $position->setPageLocation($location);
            $positions[] = $position;

            $number++;

        }

        return $positions;
    }
}

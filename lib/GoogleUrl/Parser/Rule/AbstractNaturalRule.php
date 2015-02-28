<?php

namespace GoogleUrl\Parser\Rule;

use GoogleUrl\GoogleDOM;
use GoogleUrl\Result\ResultSetInterface;

abstract class AbstractNaturalRule {

    const RULE_MATCH_MATCHED = 1;
    const RULE_MATCH_IGNORE = 2;
    const RULE_MATCH_STOP = 3;

    abstract public function match(\DOMElement $node);

    abstract public function parseGroup(GoogleDOM $googleDOM, \DomElement $group, ResultSetInterface $resultSet, $currentPosition);

    public function extractDomain($url){

        $protPos=strpos($url, "://");

        $shortUrl=  substr($url,$protPos+3); // ltrim the protocol
        $shortUrl=  substr($shortUrl,0,strpos($shortUrl, "/")); // remove all what left after the first /

        return $shortUrl;

    }

}
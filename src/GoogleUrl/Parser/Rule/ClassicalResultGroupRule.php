<?php

namespace GoogleUrl\Parser\Rule;

use GoogleUrl\GoogleDOM;
use GoogleUrl\Result\ClassicalResult;
use GoogleUrl\Result\ResultSetInterface;

class ClassicalResultGroupRule extends ClassicalResultBase
{


    public function match(\DOMElement $node)
    {

        if ($node->getAttribute("class") == "srg") {
            return self::RULE_MATCH_MATCHED;
        }

        return self::RULE_MATCH_NOMATCH;

    }

    public function parseGroup(GoogleDOM $googleDOM, \DomElement $group, ResultSetInterface $resultSet, $currentPosition)
    {

        $xpath = $googleDOM->getXpath();

        $results = $xpath->query("descendant::div[@class='g']", $group);

        foreach ($results as $searchItem) {
            $currentPosition = $this->_parseItem($googleDOM, $searchItem, $resultSet, $currentPosition);

        }
        return $currentPosition;
    }
}

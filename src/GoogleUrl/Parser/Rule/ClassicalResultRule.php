<?php

namespace GoogleUrl\Parser\Rule;

use GoogleUrl\GoogleDOM;
use GoogleUrl\Result\ClassicalResult;
use GoogleUrl\Result\ResultSetInterface;

class ClassicalResultRule extends ClassicalResultBase
{

    public function match(\DOMElement $node)
    {

        if ($node->getAttribute("class") == "g") {
            if ($node->hasAttribute("id") && $node->getAttribute("id") == "imagebox_bigimages") {
                return self::RULE_MATCH_NOMATCH;
            }

            return self::RULE_MATCH_MATCHED;

        }

        return self::RULE_MATCH_NOMATCH;

    }

    public function parseGroup(GoogleDOM $googleDOM, \DomElement $group, ResultSetInterface $resultSet, $currentPosition)
    {

        return $this->_parseItem($googleDOM, $group, $resultSet, $currentPosition);

    }
}

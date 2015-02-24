<?php


namespace GoogleUrl\Parser;


use GoogleUrl\GoogleDOM;
use GoogleUrl\Parser\Rule\AbstractNaturalRule;
use GoogleUrl\Result\ResultSet;
use GoogleUrl\Result\ResultSetInterface;

class NaturalParser {

    /**
     * @var AbstractNaturalRule[]
     */
    protected $rules;


    /**
     * @param GoogleDOM $googleDOM
     * @return ResultSetInterface $resultSet
     */
    public function parse(GoogleDOM $googleDOM){

        $xpathObject = $googleDOM->getXpath();

        $xpathElementGroups = "//div[@id = 'ires']/ol/*";
        $elementGroups = $xpathObject->query($xpathElementGroups);

        $resultSet = new ResultSet();

        $currentPosition = 0;

        foreach($elementGroups as $group){
        /* @var $group \DOMNode */

            foreach($this->rules as $rule){
                $match = $rule->match($group);

                switch($match){

                    case AbstractNaturalRule::RULE_MATCH_MATCHED:
                        $currentPosition = $rule->parseGroup($googleDOM, $group,  $resultSet, $currentPosition);
                        continue;
                        break;

                    case AbstractNaturalRule::RULE_MATCH_STOP:
                        continue;
                        break;

                }

            }

        }

        return $resultSet;

    }


}
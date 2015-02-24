<?php

namespace GoogleUrl\Result;

interface ResultSetInterface extends \IteratorAggregate {

    public function addItem(ResultItemInterface $item);

    /**
     * returns all the results that match the asked type or types
     * @param string|string[] $typeName
     * @return ResultItemInterface[]
     */
    public function getResultsByType($typeName);

    /**
     * check if the asked type is in the resultSet
     * @param $typeName
     * @return bool
     */
    public function hasType($typeName);

}
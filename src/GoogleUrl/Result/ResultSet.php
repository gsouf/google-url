<?php

namespace GoogleUrl\Result;

class ResultSet extends \ArrayObject implements ResultSetInterface
{

    public function addItem(ResultItemInterface $item)
    {
        $this[] = $item;
    }

    /**
     * @inheritdoc
     */
    public function getResultsByType($typeName)
    {
        $results = new ResultSet();

        foreach ($this as $item) {
            if ($item->getType() == $typeName) {
                $results->addItem($item->getPosition(), $item->getItem()); // TODO : REINDEX
            }
        }

        return $results;
    }

    /**
     * @inheritdoc
     */
    public function hasType($typeName)
    {
        foreach ($this as $item) {
            if ($item->getType == $typeName) {
                return true;
            }
        }

        return false;
    }
}

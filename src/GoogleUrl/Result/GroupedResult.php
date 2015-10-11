<?php

namespace GoogleUrl\Result;

abstract class GroupedResult extends PositionedResult
{

    public $items = [];

    public function addItem(ResultItemInterface $item)
    {

        $this->items[] = $item;

    }

    public function getItems()
    {
        return $this->items;
    }
}

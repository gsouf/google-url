<?php

namespace GoogleUrl\Result;

abstract class PositionedResult implements ResultItemInterface
{

    protected $position;

    /**
     * @return mixed
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param mixed $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    function __get($name)
    {
        $methodName = "get" . ucfirst($name);


        if (method_exists($this, $methodName)) {
            return  $this->$methodName();
        } else {
            return null;
        }
    }

    public function is($type)
    {
        return $type == $this->getType();
    }
}

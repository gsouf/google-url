<?php

namespace GoogleUrl\Result;


class InTheNewsResult extends TextResult {

    /**
     * @var bool
     */
    protected $isBig = false;

    public function getType()
    {
        return 'InTheNews';
    }

    /**
     * @return boolean
     */
    public function isBig()
    {
        return $this->isBig;
    }

    /**
     * @param boolean $isBig
     */
    public function setIsBig($isBig)
    {
        $this->isBig = $isBig;
    }



}
<?php


namespace GoogleUrl\Result;

interface ResultItemInterface
{
    public function getPosition();
    public function getType();
    public function is($type);
}

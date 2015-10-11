<?php

namespace GoogleUrl\Result;

class VideoResult extends ClickableResult
{

    protected $title;

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }



    public function getType()
    {
        return "video";
    }
}

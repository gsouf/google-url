<?php

// See LICENSE

namespace GoogleURL;

/**
 * Description of GooglePosition
 *
 * @author sghzal
 * @license http://www.freebsd.org/copyright/license.html BSD
 */
class GooglePosition {
    /**
     * search query
     * @var string
     */
    protected $keyword;
    
    /**
     * the website formated like follow : subdomain.domain.tld  (leave out the www.)
     * @var string 
     */
    protected $website;
    
    /**
     * position in the SERP
     * @var int
     */
    protected $position;
    
    /**
     * matching url
     * @var string
     */
    protected $url;
    
    /**
     * matching title
     * @var string
     */
    protected $title;
    
    /**
     * html string of the matching snipper
     * @var string
     */
    protected $snippet;
    
    /**
     * UNIX timestamp date of the search
     * @var int
     */
    protected $date;
    
    
    /**
     * 
     * @param string $keyword the search query
     * @param string $website the searched website
     * @param int $date the date in seconds within the UNIX timestamp
     * @param int|boolean $position the position in the serp of false if not found. Begins to 1
     * @param string $url the url found in the search
     * @param string $title the title found in the search
     * @param strnig $snippet the html snippet found in the search
     */
    function __construct($keyword, $website, $date, $position, $url, $title, $snippet) {
        $this->keyword  = $keyword;
        $this->website  = $website;
        $this->position = $position;
        $this->url      = $url;
        $this->title    = $title;
        $this->snippet  = $snippet;
        $this->date     = $date;
    }

    
    
    
}

?>

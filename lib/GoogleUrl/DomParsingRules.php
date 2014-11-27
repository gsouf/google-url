<?php

namespace GoogleUrl;

/**
 * DomParsingRules
 *
 * @author sghzal
 */
class DomParsingRules {
    
    protected $rules;

    function __construct($rules) {
        $this->rules = $rules["Rules"];
    }
    
    public function hasCaptcha(){
        var_dump($this->rules);
        return $this->rules["Captcha"]["hasCaptcha"];
    }

    public function naturalNodes(){
        return $this->rules["NaturalResults"]["Nodes"];
    }
    public function naturalNodesLinks(){
        return $this->rules["NaturalResults"]["LinkInNodes"];
    }
    public function rhsBodyNodes(){
        return $this->rules["Rhs"]["BodyNodes"];
    }
    public function rhsColumnNodes(){
        return $this->rules["Rhs"]["ColumnNodes"];
    }
    public function rhsNodesLink(){
        return $this->rules["Rhs"]["LinkInNodes"];
    }
    public function rhsNodesVisurl(){
        return $this->rules["Rhs"]["VisUlrlInNodes"];
    }
    public function rhsNodesText(){
        return $this->rules["Rhs"]["TextInNodes"];
    }
    
}
<?php


namespace GoogleUrl;

/**
 * AdwordsResultSet
 *
 * @author sghzal
 */
class AdwordsResultSet extends \ArrayObject {
    
    const LOCATION_COLUMN = "column";
    const LOCATION_BODY    = "body";
    
    /**
     * List of adwords in body
     * @return GoogleAdwordPosition[]
     */
    public function getBodyResults(){
        
        $pos = array();
        
        foreach($this as $r){
            if($r->getLocation() == self::LOCATION_BODY){
                $pos[] = $r;
            }
        }
        
        return $pos;
    }
    
    /**
     * List of adwords in colmun
     * @return GoogleAdwordPosition[]
     */
    public function getColumnResults(){
        
        $pos = array();
        
        foreach($this as $r){
            if($r->getLocation() == self::LOCATION_COLUMN){
                $pos[] = $r;
            }
        }
        
        return $pos;
    }
    
    
    
}
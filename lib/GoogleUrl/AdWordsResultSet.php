<?php


namespace GoogleUrl;

/**
 * AdwordsResultSet
 *
 * @author sghzal
 */
class AdWordsResultSet extends \ArrayObject {
    
    const LOCATION_COLUMN      = "column";
    const LOCATION_BODY_TOP    = "body_top";
    const LOCATION_BODY_BOTTOM = "body_bottom";

    /**
     * List of adwords in body top
     * @return GoogleAdwordPosition[]
     */
    public function getBodyTopResults(){

        $pos = array();

        foreach($this as $r){
            if($r->getPageLocation() == self::LOCATION_BODY_TOP){
                $pos[] = $r;
            }
        }

        return $pos;
    }


    /**
     * List of adwords in body bottom
     * @return GoogleAdwordPosition[]
     */
    public function getBodyBottomResults(){

        $pos = array();

        foreach($this as $r){
            if($r->getPageLocation() == self::LOCATION_BODY_BOTTOM){
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
            if($r->getPageLocation() == self::LOCATION_COLUMN){
                $pos[] = $r;
            }
        }
        
        return $pos;
    }
}
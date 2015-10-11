<?php
/**
 * @license see LICENSE
 */

namespace GoogleUrl\Captcha;

class CaptchaImage {

    protected $data;

    public function __construct($data){
        $this->data = $data;
    }

    public function getRawImage(){
        return $this->data;
    }

}
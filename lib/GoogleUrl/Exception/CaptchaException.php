<?php

namespace GoogleUrl\Exception;
use GoogleUrl\CaptchaPage;
use GoogleUrl\Exception;


/**
 * CaptchaException
 *
 * @author sghzal
 */
class CaptchaException extends Exception{

    /**
     * @var CaptchaPage
     */
    protected $captchaPage;

    function __construct(CaptchaPage $captchaPage)
    {
        $this->captchaPage = $captchaPage;
        parent::__construct("Google wants you to solve a captcha to continue. More info at : TODO");
    }

    /**
     * @return \GoogleUrl\CaptchaPage
     */
    public function getCaptchaPage()
    {
        return $this->captchaPage;
    }



}
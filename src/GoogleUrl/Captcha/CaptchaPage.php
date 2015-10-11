<?php

namespace GoogleUrl\Captcha;

use GoogleUrl\CaptchaSolver\CaptchaImage;

class CaptchaPage
{

    protected $captchaImageUrlXpath = "//body/div/img";
    protected $captchaIdXpath = "//body/div/form/input[@name='id']";

    private $__imageUrl;

    /**
     * @var GoogleDOM
     */
    protected $googleDom;

    function __construct(GoogleDOM $googleDom)
    {
        $this->googleDom = $googleDom;
    }

    public function getImageUrl()
    {
        if (null === $this->__imageUrl) {
            $imageTag = $this->googleDom
                ->getXpath()
                ->query($this->captchaImageUrlXpath);
            $this->__imageUrl = $imageTag->item(0)->getAttribute("src");
        }
        return $this->__imageUrl;
    }

    /**
     * @return string the image of the captcha to solve
     */
    public function getImage()
    {
        $imageUrl = $this->getImageUrl();
        return new CaptchaImage(file_get_contents("https://google.com$imageUrl"));
    }

    public function getId()
    {
        $inputTag = $this->googleDom
            ->getXpath()
            ->query($this->captchaIdXpath);
        $id = $inputTag->item(0)->getAttribute("value");

        return $id;
    }
}

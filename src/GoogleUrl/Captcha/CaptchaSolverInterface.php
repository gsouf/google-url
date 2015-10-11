<?php

namespace GoogleUrl\Captcha;

use GoogleUrl\Captcha\CaptchaImage;

interface CaptchaSolverInterface
{

    public function solve(CaptchaImage $image);
}

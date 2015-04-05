<?php

namespace GoogleUrl\CaptchaSolver;


use GoogleUrl\CaptchaPage;

interface CaptchaSolverInterface {

    public function solve($image);

}
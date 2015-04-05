<?php

namespace GoogleUrl\CaptchaSolver;


use GoogleUrl\CaptchaPage;

class ManualSolver implements CaptchaSolverInterface{

    public function solve($image){


        return "text";


    }

}
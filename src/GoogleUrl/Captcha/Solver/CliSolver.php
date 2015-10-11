<?php
/**
 * @license see LICENSE
 */

namespace GoogleUrl\Captcha\Solver;

use GoogleUrl\Captcha\CaptchaSolverInterface;
use GoogleUrl\Captcha\CaptchaImage;

class CliSolver implements CaptchaSolverInterface {

    protected $savePath = "/tmp";

    public function solve(CaptchaImage $image)
    {
        $tempFile = tempnam($this->savePath, "google-url-captcha");
        file_put_contents($tempFile, $image->getRawImage());

        $answer = "n";

        echo "Captcha image was saved under $tempFile ";

        while($answer != "yes"){
            echo PHP_EOL . "Please enter the code on this image: ";
            $handle = fopen ("php://stdin","r");
            $line = trim(fgets($handle));

            echo "Do you want to send '$line' to solve captcha ? [yes/NO] ";
            $handle = fopen ("php://stdin","r");
            $answer = trim(fgets($handle));
        }

        return $line;

    }


}
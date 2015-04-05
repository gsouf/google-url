<?php

namespace GoogleUrl\CaptchaSolver;


use GoogleUrl\Http\CurlSender;
use GoogleUrl\SimpleProxyInterface;

class CaptchaSender {

    protected $tld = "com";

    public function send($id, $text, SimpleProxyInterface $proxy = null){

        $url = "https://www.google." . $this->tld;

        $params = [
            "continue" => "https://www.google." . $this->tld,
            "id" => $id,
            "text" => $text,
            "submit" => "Submit"
        ];

        $sender = new CurlSender();

        $r = $sender->send($url, [], $proxy);

    }

}
<?php

include __DIR__ . "/autoload.php";


// Create 2 proxy with ip/address and port
$proxy1 = new \GoogleUrl\SimpleProxy("localhost", "3128");
$proxy2 = new \GoogleUrl\SimpleProxy("someproxyAddress", "8080");

// use them for the query
$googleUrl=new \GoogleUrl();
$googleUrl->setLang('fr')->setNumberResults(10)->search("simpson",$proxy1);
$googleUrl->setLang('fr')->setNumberResults(10)->search("simpsons",$proxy2);
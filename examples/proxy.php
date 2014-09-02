<?php

include __DIR__ . "/../autoload.php";



$proxy1 = new \GoogleUrl\SimpleProxy("localhost", "3128");
$proxy2 = new \GoogleUrl\SimpleProxy("someproxyAddress", "8080");

$googleUrl=new \GoogleUrl();
$googleUrl->setLang('fr')->setNumberResults(10)->search("simpson",$proxy1);
$googleUrl->setLang('fr')->setNumberResults(10)->search("simpsons",$proxy2);
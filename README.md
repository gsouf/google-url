Project Deprecated
==================

This project has been deprecated in favor of a **shiny** :sparkles:  , **stronger** :muscle:, **better** :rocket: alternative: http://serp-spider.github.io

<p align="center">
  <img src ="https://serp-spider.github.io/logo.png" />
</p>

That means that the project support will be stoped in favor of the new one.


Google URL : Google scraper for php
===================================

Get ready to query google like a pro and make awesome google searches with PHP


Features
--------

 * Google SERP url generation
 * Google scraping including : 
   * Natural results parsing
   * Adwords results parsing


**PLEASE READ ALL THE FOLLOWING SECTIONS BEFORE USING IT** it contains important informations about the usage.


Be aware...
------------

...that scraping google is forbiden (what an irony for the biggest scraper ever written)... But who cares ? 

Google does. And it will stop you with a captcha if you submit too many requests in a short time.

Usually I delay each query with 30 seconds. But if you do a lot of requests it's still too short.

**How to counter :**

* Optimize your delays between each queries.
* If you want to do very high number of requests on a short time you will have to use proxy(s). There is a tool packaged in the library that can do it for you ([see below](#using-proxy)), but you still can do it by yourself.


Installation
------------

The library is available on packgist : ``"sneakybobito/google-url": "dev-master"``

If you are not familiar with packagist, you can also use the loader packaged in the repo. To do so download the library (e.g. as a zip from github) 
and just include the file named ``autoload.php`̀` : 

```php

    <?php
       
    include("path/to/googleurl/autoload.php");

    $g = new \GoogleUrl();

    // ......


```

Example of use
--------------


When you use this library you have to keep in mind that querying google is something that you have to control.

You cant use it everytime someone loads a page on your webserver. Indeed it mays be long, it means long time to load the web page.
You also have to control the number of query you do over the time. Or else google will consider you as a bot and you will get blocked by the captcha.

Instead you may use it in a cli program that will store results in database. And then query the database from the webpage script.

Once again think about using delays between each query. It is very important for not google to add your server to the blacklist. 
There is no universal rule for the delays to apply. It is hard to figure out the best delays to use and it requires many tests. That's why people want to keep it secret.


```php

    <?php
        $googleUrl=new \GoogleUrl();
        $googleUrl->setLang('en') // lang allows to adapt the query (tld, and google local params)
            ->setNumberResults(10);                        // 10 results per page
        $acdcPage1=$googleUrl->setPage(0)->search("acdc"); // acdc results page 1 (results 1-10)
        $acdcPage2=$googleUrl->setPage(1)->search("acdc"); // acdc results page 2 (results 11-20)

        $googleUrl->setNumberResults(20);
        $simpsonPage1=$googleUrl->setPage(0)->search("simpson"); // simpsons results page 1 (results 1-20)




        // GET NATURAL RESULTS

        $positions=$simpsonPage1->getPositions();

        echo "results for " . $simpsonPage1->getKeywords();
        echo "<ul>";
        foreach($positions as $result){
            echo "<li>";
            echo "<ul>";
            echo "<li>position : " . $result->getPosition() . "</li>";
            echo "<li>title : "    . utf8_decode($result->getTitle())    . "</li>";
            echo "<li>website : "  . $result->getWebsite()  . "</li>";
            echo "<li>URL : <a href='" . $result->getUrl() ."'>" . $result->getUrl() . "</a></li>";
            echo "</ul>";
            echo "</li>";
        }
        echo "</ul>";

        // GET ADWORDS RESULTS

        $commercialSearch = $googleUrl->setLang("fr")->setPage(0)->search("simpson tshirt");
        $adwordsPositions = $commercialSearch->getAdwords();
        echo "adwords for " . $commercialSearch->getKeywords();
        echo "<ul>";
        foreach($adwordsPositions as $result){
            echo "<li>";
            echo "<ul>";
            echo "<li>location : " . $result->getLocation() . "</li>"; // adwords can be displayed in body or in column
            echo "<li>position : " . $result->getPosition() . "</li>";
            echo "<li>title : "    . utf8_decode($result->getTitle())    . "</li>";
            echo "<li>fake url : "  . $result->getVisurl()  . "</li>";
            echo "<li>URL :" . $result->getAdwordsUrl() . "</li>";
            echo "<li>Text : " . $result->getText() . "<li>";
            echo "</ul>";
            echo "</li>";
        }
        echo "</ul>";

        // we can also get only results in body
        echo "adwords <b>IN BODY</b> for " . $commercialSearch->getKeywords();
        echo "<ul>";
        foreach($adwordsPositions->getBodyResults() as $result){
            echo "<li>" . $result->getPosition() . " : " . utf8_decode($result->getTitle()) . "</li>";
        }
        echo "</ul>";


        // and obviously results in the right column
        echo "adwords <b>IN COLUMN</b> for " . $commercialSearch->getKeywords();
        echo "<ul>";
        foreach($adwordsPositions->getColumnResults() as $result){
            echo "<li>" . $result->getPosition() . " : " . utf8_decode($result->getTitle()) . "</li>";
        }
        echo "</ul>";
        
    }


```

Using Proxy
-----------


### IMPORTANT WANRING

Using proxy pool offered by the library is currently **deprecated** because it will be removed from the next release. Proxy management is very specific and can't be implemented in a decent way in the library. The library will continue to offer support for querying through a proxy, but proxy management will be managed by yourself.


```php

<?php

include __DIR__ . "/../autoload.php";



$proxy1 = new \GoogleUrl\SimpleProxy("localhost", "3128");
$proxy2 = new \GoogleUrl\SimpleProxy("someproxyAddress", "8080");
$proxy2->setLogin("mylogin");
$proxy2->setPassword("myPassword");


// OR WITH PROXY STRING :

$proxy2 = new \GoogleUrl\ProxyString("mylogin:myPassword@someproxyAddress:8080");


$googleUrl=new \GoogleUrl();
$googleUrl->setLang('fr')->setNumberResults(10)->search("simpson",$proxy1);
$googleUrl->setLang('fr')->setNumberResults(10)->search("simpsons",$proxy2);

```

Please refere to https://github.com/SneakyBobito/google-url/blob/master/doc/proxy_rotation.md for more complet usage of proxies.


Implemented Languages
---------------------

Each language matches a google domain (google.com, google.fr, google.de...) and language of the search. You will not have the same results for EN or DE.

The following languages are implemented.

* en (english)
* fr (french)
* de (german)
* nl (dutch)
* cs (czech)
* dk (danish)
* jp (japanese)
* es (spannish)
* ru (russian)


more are comming over the time, but because the language management is going to change soon we dont want to implement too many right now (dont be affraid about using them it's only internal changes).

Support - Contact
-----------------

Feel free to open an issue for any request/question

Roadmap
-------

* Page parsing improvment (images results, website results...)
* Refactoring and moving proxy pools
* refactoring language management
* refactoring page parsing management to handle better google page changes

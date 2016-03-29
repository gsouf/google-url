Project Deprecated
==================

This project has been deprecated in favor of a better alternative: http://serp-spider.github.io

That means that the project support will be stoped

google-url
==========


Google url is a library that brings a very comfortable way to query and parse google SERPs (Search Engine Result Page)

**BE AWARE**  This branch was aimed to serve for the release of the v2, apparently some people started to use it but a lot of refactoring is going to happen and it's better to wait it to be released as a stable thing.



Features
--------

 * Google SERP url generation
 * Natural results parsing
 * Adwords results parsing
 * Proxy Usage


**PLEASE READ ALL THE FOLLOWING SECTIONS BEFORE USING IT** it contains important information about the usage.


Be aware...
-----------

...that scrapping google is forbiden (what an irony for the biggest scrapper ever written)... But who cares ? 

Google does. And it will stop you with a captcha if you submit too many requests in a short time.

Usually I delay each query with 30 seconds. But if you do a lot of requests it's still too short.

**How to counter :**

* Optimize your delays between each queries.
* If you want to do very high number of requests on a short time you will have to use proxy(s).
There is a tool packaged in the library that can do it for you ([see below](#using-proxy)).


This library needs to be updated frequently
-------------------------------------------

As said in the previous section google doesn't want bots to scrape it. 
Then there is no guaranty that the current build of GoogleUrl still works in the future.

Every update made by google can lead to unpredicted behaviours of the library. We try to keep it up to date

In the last 2 years, there were only two critical google update for the library. We can tell it's safe enough.

Sometime google also adds new stuff to the results. Every new google feature needs to be implemented and you
need to update to the new version once it's done.

We kindly encourage you to report any problem or question you encounter with the library to help us to keep
the library up to date as often as possible (please use the github issue tracker).



Install
-------

The library is available on packagist : ``"gsouf/google-url": "~2.0"``

If you are not familiar with packagist, you can also use the loader packaged in the repo.
To do so download the library (e.g. as a zip from github) and just include the file named ``autoload.php`̀` :

```php

    <?php
       
    include("path/to/googleurl/autoload.php");

    $g = new GoogleUrl();

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
        $googleUrl=new GoogleUrl();
        $googleUrl->setLang('en') // lang allows to adapt the query (tld, and google local params)
            ->setNumberResults(10);                        // 10 results per page
        $acdcPage1=$googleUrl->setPage(0)->search("acdc"); // acdc results page 1 (results 1-10)
        $acdcPage2=$googleUrl->setPage(1)->search("acdc"); // acdc results page 2 (results 11-20)

        $googleUrl->setNumberResults(20);
        $simpsonPage1=$googleUrl->setPage(0)->search("simpson"); // simpsons results page 1 (results 1-20)


```

**Get natural results**


```php
        $positions=$simpsonPage1->getPositions();

        echo 'results for ' . $simpsonPage1->getKeywords();

        foreach($positions as $result){
            
            if($result->is("classical")){
                $resultTitle = $result->title;
                $website     = $result->website;
                $url         = $result->targetUrl;
            }else if($result->is("video"){
                $resultTitle = $result->title;
                $website     = $result->website;
            }else if($result->is("imageGroup"){
                $images = $result->getItems();
                foreach ($images as $imgResult) {
                    $imgUrl = $imgResult->imageUrl;
                    $url    = $imgResult->targetUrl;
                }
            }else{
            
                echo 'Ignored result : ';
                echo $result->position . ' ' . $result->type;
            
            }

            
        }


```

**Get adWords results**

```php
        $commercialSearch = $googleUrl->setLang("fr")->setPage(0)->search("simpson tshirt");
        $adwordsPositions = $commercialSearch->getAdwords();
        echo "adwords for " . $commercialSearch->getKeywords();
        echo "<ul>";
        foreach($adwordsPositions as $result){
            echo "<li>";
            echo "<ul>";
            // adwords can be displayed in body top, body bottom or right column
            echo "<li>location : " . $result->getLocation() . "</li>"; 
            echo "<li>position : " . $result->getPosition() . "</li>";
            echo "<li>title : "    . $result->getTitle()    . "</li>";
            echo "<li>fake url : " . $result->getVisurl()  . "</li>";
            echo "<li>URL :" . $result->getAdwordsUrl() . "</li>";
            echo "<li>Text : " . $result->getText() . "<li>";
            echo "</ul>";
            echo "</li>";
        }
        echo "</ul>";

        // we can also get only results in body top
        echo "adwords <b>IN BODY</b> for " . $commercialSearch->getKeywords();
        echo "<ul>";
        foreach($adwordsPositions->getBodyTopResults() as $result){
            echo "<li>" . $result->getPosition() . " : " . $result->getTitle() . "</li>";
        }
        echo "</ul>";


        // or results in the right column
        echo "adwords <b>IN COLUMN</b> for " . $commercialSearch->getKeywords();
        echo "<ul>";
        foreach($adwordsPositions->getColumnResults() as $result){
            echo "<li>" . $result->getPosition() . " : " . $result->getTitle() . "</li>";
        }
        echo "</ul>";
        
    }


```

Using Proxy
-----------


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

Each language matches a google domain (google.com, google.fr, google.de...) and language of the search.
You will not have the same results for EN or DE.

The following languages are currently implemented.

* en (english)
* fr (french)
* de (german)
* nl (dutch)
* cs (czech)
* dk (danish)
* jp (japanese)
* es (spannish)
* ru (russian)


more are coming over the time. You can open an issue if you want your language to appear in the library.

Optionally you can use your language with a very few efforts TODO : link to the doc

Support - Contact
-----------------

Feel free to open an issue for any request/question or to [talk on gitter](https://gitter.im/gsouf/google-url)

Roadmap
-------

* Create better test and set up a task to handle google updates

ChangeLog
---------

What is new in V2 :

* We moved minimal php version to 5.4
* We implemented captcha resolver to make possible to automatically solve captcha with external services
* Proxy and proxy rotation has been reviewed to be more stable
* Page parsing has been wholly refactored to allow better control and extensibility (internal changes)
* The result analyse API as been reviewed to be more semantic and now accepts different types of results ("in the news", "images", "in depth articles"...)
* The language management is more transparent. You can control easily the tld, the language, etc...


google-url
==========

Get ready to query google like a pro and make awesome google searches with PHP

The library is being improved to make better pages parsing. Feel free to give any feedback/nfr from the issue tracker.


Features
--------

 * Google SERP url generation
 * Natural results parsing
 * Adwords results parsing



**PLEASE READ ALL THE FOLLOWING SECTIONS BEFORE USING IT** it contains important informations about the usage.


Be aware...
------------

...that scrapping google is forbiden (what an irony for the biggest scrapper ever written)... But who cares ? 

Google does. And it will stop you with a captcha if you submit too many requests in a short time.

Usually I delay each query with 30 seconds. But if you do a lot of requests it's still too short.

**How to counter :**

* Optimize your delays (this package contains a query delayer that does it for you with different delays, but i'm still trying to figure out what is the best timing to use)
* If you want to do very high number of requests on a short time you will have to use proxy(s). I'm looking for the best implementation I can do of it in the library.



Available on packgist
---------------------

``"sneakybobito/google-url": "dev-master"``


Example of use
--------------


When you use this library you have to keep in mind that querying google is something that you have to control.

You cant use it everytime someone loads a page on your webserver. Indeed it mays be long, it means long time to load the web page.
You also have to control the number of query you do over the time. Or else google will consider you as a bot and you will get blocked by the captcha.

Instead you may to use it in a cli program that will store results in database. And the query the database from the webpage.

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

        $commercialSearch = $googleUrl->setPage(0)->search("simpson tshirt");
        $adwordsPositions = $commercialSearch->getAdwordsPositions();
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
        echo "adwords <b>IN BODY</b> for " . $commercialSearch->getBodyResults();
        echo "<ul>";
        foreach($adwordsPosition->getBodyResults() as $result){
            echo "<li>" . $result->getPosition() . " : " . utf8_decode($result->getTitle()) . "</li>";
        }
        echo "</ul>";


        // and obviously results in the right column
        echo "adwords <b>IN COLUMN</b> for " . $commercialSearch->getBodyResults();
        echo "<ul>";
        foreach($adwordsPosition->getBodyResults() as $result){
            echo "<li>" . $result->getPosition() . " : " . utf8_decode($result->getTitle()) . "</li>";
        }
        echo "</ul>";


```


**Implemented Languages**
* en (english)
* fr (french)
* de (german)
* nl (dutch)
* cs (czech)
* dk (danish)
* jp (japanese)
* es (spannish)
* ru (russian)

more are incoming over the time...

Support - Contact
-----------------

Feel free to open an issue for any request/question

Roadmap
-------

* Page parsing improvment (images results, website results...)
* Delayer/query queue


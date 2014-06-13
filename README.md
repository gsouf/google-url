google-url
==========

Get ready to query google like a pro and make awesome google searches with PHP

The library is being improved to make better pages parsing. Feel free to give any feedback/nfr from the issue tracker.


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

```php

    <?php
        $googleUrl=new \GoogleUrl();
        $googleUrl->setLang('en') // lang allows to adapt the query (tld, and google local params)
            ->setNumberResults(10);                        // 10 results per page
        $acdcPage1=$googleUrl->setPage(0)->search("acdc"); // acdc results page 1 (results 1-10)
        $acdcPage2=$googleUrl->setPage(1)->search("acdc"); // acdc results page 2 (results 11-20)

        $googleUrl->setNumberResults(20);
        $simpsonPage1=$googleUrl->setPage(0)->search("simpson"); // simpsons results page 1 (results 1-20)


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
```


**Available Languages**
* en
* fr

more are incoming...

Support - Contact
-----------------

Feel free to open an issue for any request/question

Roadmap
-------

* Page parsing improvment (images results, website results...)
* Delayer/query queue
* Removing dependancy + php 5.3 compatibility

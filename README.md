google-url
==========

Get ready to query google like a pro and make awesome google searches with PHP

The library is being improved to make better pages parsing. Feel free to give any feedback/nfr from the issue tracker.


Warning
-------

Google mays limit you with a captcha if you make too many queries in a short time.


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


Support - Contact
-----------------

Feel free to open an issue for any request/question

Roadmap
-------

* Page parsing improvment (images results, website results...)
* Delayer/query queue
* Removing dependancy + php 5.3 compatibility

google-url
==========

Make awesome google searches with PHP and query google like a pro

Example of use
--------------

```php

    <?php
        $googleUrl=new \GoogleURL\GoogleUrl();
        $googleUrl->setLang('en')
            ->setNumberResults(10);
        $acdcPage1=$googleUrl->setPage(0)->search("acdc");
        $acdcPage2=$googleUrl->setPage(1)->search("acdc");

        $googleUrl->setNumberResults(20);
        $simpsonPage1=$googleUrl->setPage(0)->search("simpson");


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


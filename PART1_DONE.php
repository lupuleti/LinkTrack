<?php
function sortByDate($a, $b){
    return strtotime($a->Date)-strtotime($b->Date); 
}

$articles = array("http://www.theguardian.com/world/2015/sep/04/syrian-refugee-crisis-why-has-it-become-so-bad",
                  "http://www.bbc.co.uk/news/world-europe-34131911",
                  "http://www.huffingtonpost.co.uk/2015/09/04/rich-arab-nations-syria-refugees_n_8089414.html",
                  "http://www.independent.co.uk/news/world/europe/refugee-crisis-migrants-map-shows-how-europe-is-becoming-a-fortress-to-keep-people-ou-a6707986.html",
                  "http://news.sky.com/story/1562525/uk-deeply-divided-over-letting-in-refugees");


for ($i = 0; $i < count($articles); $i++) {
//    $source = $articles[$i];
    $source = "http://api.majestic.com/api/json?app_api_key=96CA2AAC8EC2F73FA1365D69BED49B1D&cmd=GetBackLinkData&item=$articles[$i]&Count=50000&datasource=fresh";
    $json = file_get_contents($source);
    $decodedJson = json_decode($json);
    $arrayOfData = $decodedJson -> DataTables ->  BackLinks -> Data;

    usort($arrayOfData, 'sortByDate');

    $uniqueID = 0;

    foreach ($arrayOfData as $value){
        echo strtotime($value->Date) . '|';

        $article = explode("/", $decodedJson->DataTables->BackLinks->Headers->Item)[2];
        if (substr($article, 0, 3) == "www")
            $article = substr($article, 4);
        echo $article . "_$i" . '|A|';

        $url = explode("/", $value->SourceURL)[2];
        if (substr($url, 0, 3) == "www")
            $url = substr($url, 4);

        echo "$article-$i/$url/" . $uniqueID++ . '|';

        if ($value->SourceCitationFlow == 0)
            echo 'FFFFFF';
        else if ($value->SourceCitationFlow < 5)
            echo 'FF0000';
        else if ($value->SourceCitationFlow < 10)
            echo 'FF4000';
        else if ($value->SourceCitationFlow < 20)
            echo 'FF8000';
        else if ($value->SourceCitationFlow < 40)
            echo 'AEB404';
        else if ($value->SourceCitationFlow < 80)
            echo 'FFFF00';
        else
            echo '01DF01';
        echo "\n";
    }
}

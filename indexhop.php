<?php
$searchedLink = "http://www.bctia.org/About-Us/BCTIA-Partners";
$count = 0;
$depth = 0;
class Queue extends SPLQueue{}

$backLinks = new Queue();

$backLinks->enqueue("mlh.io");

    while ($count < 100) {
        $depth++;

        $current = $backLinks->dequeue();
        $temp= "http://api.majestic.com/api/json?app_api_key=96CA2AAC8EC2F73FA1365D69BED49B1D&cmd=GetBackLinkData&item=".$current."&Count=50000&datasource=fresh";
        $jsonQ = file_get_contents($temp);
        $decodedJsonQ = json_decode($jsonQ);
        $arrayOfDataQ = $decodedJsonQ -> DataTables ->  BackLinks -> Data;

        foreach ($arrayOfDataQ as $item) {
            if (strcmp($item->SourceURL, $searchedLink) == 0) {
                echo $depth;
                die();
            } else {
                $backLinks->enqueue($item->SourceURL);

        }
        $count++;
    }






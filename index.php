<?php
require './vendor/autoload.php';
include './functions.php';

use App\ITunesTop;
use GuzzleHttp\Client;

ini_set('max_execution_time', 0);

$itunes = new ITunesTop(new GuzzleHttp\Client());
foreach (getTop('./top.json')['results']['songs'][0]['data'] as $song) {
    show($itunes->getTopArtistBySongId((int) $song['id']));
}
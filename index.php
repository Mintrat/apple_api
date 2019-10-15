<?php
require './vendor/autoload.php';

use App\ITunesTop;
use GuzzleHttp\Client;

try {
    $http = new GuzzleHttp\Client();    
    $itunes = new ITunesTop(new GuzzleHttp\Client(), 'appleToken');
    $topArtist = $itunes->getTopArtistBySongId(900032829);
} catch (Exception $e) {
    echo $e->getMessage();
}

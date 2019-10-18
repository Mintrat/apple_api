<?php
require './vendor/autoload.php';

use App\ITunesTop;
use App\Artist;
use App\Song;
use GuzzleHttp\Client;

$dataSong = file_get_contents('song.txt');
$dataArtist = file_get_contents('artist.txt');

$song = Song::getInstance($dataSong);
$artist = Artist::getInstance($dataArtist);

die;
try {
    $http = new GuzzleHttp\Client();    
    $itunes = new ITunesTop(new GuzzleHttp\Client(), 'appleToken');
    $topArtist = $itunes->getTopArtistBySongId(900032829);
} catch (Exception $e) {
    echo $e->getMessage();
}

<?php
require './vendor/autoload.php';

use App\ITunesTop;
use App\Artist;
use App\Song;
use App\Top;
use App\SongTop;
use GuzzleHttp\Client;

$itunes = new ITunesTop(new GuzzleHttp\Client());
$artist = $itunes->getArtistById(6671250);
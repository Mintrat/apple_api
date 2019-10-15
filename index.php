<?php

require './vendor/autoload.php';

use App\ITunesTop;
use GuzzleHttp\Client;

$itunes = new ITunesTop(new GuzzleHttp\Client());
$topArtist = $itunes->getTopArtistBySongId(900032829);

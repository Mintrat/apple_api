<?php
require './vendor/autoload.php';

use App\ITunesTop;
use App\ArtistDTO;
use App\AppleMusicSongDTO;
use App\Top;
use App\ITunesSong;
use GuzzleHttp\Client;

$itunes = new ITunesTop(new GuzzleHttp\Client());
$artist = $itunes->getArtistById(6671250);

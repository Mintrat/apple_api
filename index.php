<?php
require './vendor/autoload.php';

use App\ITunesService;
use App\ArtistDTO;
use App\AppleMusicSongDTO;
use App\TopDTO;
use App\ITunesSongDTO;
use GuzzleHttp\Client;

$itunes = new ITunesService(new GuzzleHttp\Client());
$artist = $itunes->getArtistById(6671250);
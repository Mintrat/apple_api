<?php
ini_set('max_execution_time', 0);
include './functions.php';

$microtime = microtime();

$url = 'https://itunes.apple.com/search';
$query = '?limit=200&term=';
$top = getTop('./top.json');
$songs = getSongs($top);
$artists = [];

foreach ($songs as $song) {
    $artistName = $song['attributes']['artistName'];
    
    // если в записе трека учавствовало несколько артистов, разбиваем их
    if (strpos($artistName, ',') !== false) {
        $compoundName = explode(',', $artistName);
    }

    // если несколько артистов учавствовали в записи трека, записываем каждого отдельно в массив $artists
    if (!empty($compoundName)) {
        foreach ($compoundName as $name) {
            writeInArtists($artists, $name, (int) $song['id']);
        }
        unset($compoundName);
    } else {
        writeInArtists($artists, $artistName, (int) $song['id']);
    }
}

// ищит id артиста и его top
foreach ($artists as $name => $dataArtist) {
    $requestURL = $url . $query . str_replace(' ', '+', $name);
    $resultRequest = json_decode(file_get_contents($requestURL), true);
    $artistId = getArtistId($resultRequest, $dataArtist['idsSongs']);
    $artists[$name]['artistId'] = $artistId;
    if ($artistId) {
        // у функции getTopArtistById второй параметер $limit равен 5 
        $artists[$name]['top'] = getTopArtistById($artistId);
    }
    unset($requestURL);
}

show($artists);
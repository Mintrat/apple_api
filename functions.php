<?php

function getTop(String $path): ?array {
    if (file_exists($path)) {
        return json_decode(file_get_contents($path), true);
    }
}

function getSongs(array $top): array {
    return $top['results']['songs'][0]['data'];
}

function writeInArtists(array &$artists, String $artistName, int $idSong) {
    $artistName = trim($artistName);
    if (isset($artists[$artistName])) {
        ++$artists[$artistName]['count'];
    } else {
        $artists[$artistName]['count'] = 1;
        $artists[$artistName]['idsSongs'][] = $idSong;
    }
}

function getArtistId(array $data, array $idsSongs): ?int {
    foreach ($data['results'] as $song) {
        /************************************ */
        if (isset($song['trackId']) && in_array($song['trackId'], $idsSongs)) {
            return (int) $song['artistId'];
        }
        /********************************* */
    }
    return null;
}

function getTopArtistById(int $artistId, int $limit = 5): array {
    $top = [];
    $url = 'https://itunes.apple.com/lookup';
    $query = [
        'limit' => $limit,
        'id' => $artistId,
        'entity' => 'song'
    ];
    $data = file_get_contents($url . '?' . http_build_query($query));
    $data = json_decode($data, true);
    /*************************************************** */
    if (!empty($data['results'])) {
        for ($i = 1, $count = count($data['results']); $i < $count; ++$i) {
            $top[] = $data['results'][$i]['trackId'];
        }
    }
    /*************************************************** */
    return $top;
}



function show($data, $die = false) {
    echo '<pre>';
    print_r($data);
    echo '</pre>';

    if ($die) {
        die;
    }
}
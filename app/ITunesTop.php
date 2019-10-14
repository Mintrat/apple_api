<?php
namespace App;

class ITunesTop 
{
    private $http;
    private $urlSearch = 'https://api.music.apple.com/v1/catalog/us/songs/{id}';
    private $urlLookup = 'https://itunes.apple.com/lookup';
    private $queryLookup = ['entity' => 'song', 'id' => ''];

    public function __construct(\GuzzleHttp\Client $http)
    {
        $this->http = $http;
    }

    public function getTopArtistBySongId(int $songId, String $artistName): array
    {
        $artistId = $this->getArtistIdBySongId($songId);

        if ($artistId !== false) {
            $topArtist = $this->getTopArtistById($artistId);
            return $topArtist ? $topArtist : false;
        }

        return false;
    }

    private function searchArtitstBySongId(int $idSong)
    {
        $query = str_replace('{id}', $idSong, $this->urlSearch);
        $response = $this->http->request('GET', $query);
        $jsonObj =  json_decode($response->getBody());

        if (!empty($jsonObj->data)) {
            return $jsonObj->data[0]->relationships->artists->data[0]->id;
        }

        return false;
    }

    private function getTopArtistById(int $artistId)
    {
        $queryLoop = $this->queryLookup;
        $queryLoop['id'] = $artistId;
        $query = $this->urlLookup . '?' . http_build_query($queryLoop);
        $response = $this->http->request('GET', $query);
        $jsonObj = json_decode($response, true);

        if ($jsonObj) {
            $topArtist = [];
            for ($i = 1, $countSongs = $jsonObj->resultCount; $i < $countSongs; ++$i) {
                $topArtist[] = $jsonObj->results[$i]->trackId;
            }
            return $topArtist;
        }
        return false;
    }
}
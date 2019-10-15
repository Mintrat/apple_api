<?php
namespace App;

class ITunesTop 
{
    private $http;
    private $urlSearch = 'https://api.music.apple.com/v1/catalog/us/songs/{id}';
    private $urlLookup = 'https://itunes.apple.com/lookup';
    private $queryLookup = ['entity' => 'song', 'amgArtistId' => ''];

    public function __construct(\GuzzleHttp\Client $http)
    {
        $this->http = $http;
    }

    public function getTopArtistBySongId(int $songId, String $artistName): array
    {
        $artistsIds = $this->getArtistIdBySongId($songId);

        if ($artistsIds !== false) {
            $topArtists = [];

            foreach ($artistsIds as $artistId) {
                $topArtists[$artistId] = $this->getTopArtistById($artistId)                
            }

            $topArtist = $this->getTopArtistById($artistId);
            return $topArtists;
        }

        return false;
    }

    public function getArtistIdBySongId(int $idSong)
    {
        $query = str_replace('{id}', $idSong, $this->urlSearch);
        $response = $this->http->request('GET', $query);
        $jsonObj =  json_decode($response->getBody());

        if (!empty($jsonObj->data)) {
            $artists = $jsonObj->data[0]->relationships->artists->data;
            $idArtists = [];

            for ($i = 0, $countAtrists = count($artists); $i < $countAtrists; ++$i) {
                $idArtists[] = $artists[$i]->id;
            }
            return $idArtists;
        }

        return false;
    }

    public function getTopArtistById(int $artistId)
    {
        $queryLoop = $this->queryLookup;
        $queryLoop['amgArtistId'] = $artistId;
        $response = $this->http->request('GET', $this->urlLookup, ['query' => $queryLoop]);
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

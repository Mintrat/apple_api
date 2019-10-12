<?php
namespace App;

class ITunesTop 
{
    private $ghc;
    private $urlSearch = 'https://itunes.apple.com/search';
    private $querySearch = '?limit=200&term=';

    private $urlLookup = 'https://itunes.apple.com/lookup';
    private $queryLookup = '?limit=200&entity=song&id=';

    public function __construct(\GuzzleHttp\Client $ghc)
    {
        $this->ghc = $ghc;
    }

    public function getTopArtistBySongIdArtName(int $idSong, String $artistName): array
    {
        $searchList = $this->searchArtitsts($artistName);
        $artistId = $this->getArtistIdBySongId($searchList, $idSong);
        return getTopArtistById($artistId);
    }

    private function searchArtitsts(String $artistName): array
    {
        $query = $this->urlSearch . $this->querySearch . $artistName;
        $response = $this->ghc->request('GET', $query);
        return json_decode($response->getBody(), true);
    }

    private function getArtistIdBySongId(array $searchList, int $songId): int
    {
        foreach ($searchList['results'] as $song) {
            if (in_array($songId, $song)) {
                return $song['artistId'];
            }
        }
        return 0;
    }

    private function getTopArtistById(int $artistId): array
    {
        $query = $this->urlLookup . $this->queryLookup . $artistId;
        $response = $this->ghc->request('GET', $query);
        return json_decode($response, true);
    }
}
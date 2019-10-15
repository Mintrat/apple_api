<?php
namespace App;
class ITunesTop 
{
    private $http;
    private $urlSearch = 'https://api.music.apple.com/v1/catalog/us/songs/{id}';
    private $searchHeaders = ['Authorization' => 'someToken'];
    private $urlLookup = 'https://itunes.apple.com/lookup';
    private $queryLookup = ['entity' => 'song', 'amgArtistId' => ''];
/**
 * @param \GuzzleHttp\Client $http
 * @param String $token the apple token for authorization
 */
    public function __construct(\GuzzleHttp\Client $http, String $token)
    {
        $this->http = $http;
        $this->searchHeaders['Authorization'] = $token;
    }
/**
 *  @param int $songId
 *  @return array Return array with top artists by song id or false
 */
    public function getTopArtistBySongId(int $songId)
    {
        $artistsIds = $this->getArtistIdBySongId($songId);
        if ($artistsIds !== false) {
            $topArtists = [];
            foreach ($artistsIds as $artistId) {
                $topArtists[$artistId] = $this->getTopArtistById($artistId);
            }
            
            return $topArtists;
        }
        return false;
    }
    
    /**
    *  @param int $songId
    *  @return array Return array with artists ids or false. If result is not seccesseful, will be throw an exception  
    */
    public function getArtistIdBySongId(int $songId)
    {
        $query = str_replace('{id}', $songId, $this->urlSearch);
        $response = $this->http->request('GET', $query, ['headers' => $this->searchHeaders]);
        $jsonObj =  json_decode($response->getBody());
        if (!empty($jsonObj->data)) {
            $artists = $jsonObj->data[0]->relationships->artists->data;
            
            $idArtists = array_map(function($artist) {
                return $artist->id;
            }, $artists);
            
            return $idArtists;
        }
        return false;
    }
    /**
     * 
     * @param int $artistId
     * @return array Return array with top artists. If result is not seccesseful, will be throw an exception
     */
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

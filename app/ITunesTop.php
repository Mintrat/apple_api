<?php
namespace App;

class ITunesTop 
{
    private $http;
    private $urlApppleMusic = 'https://api.music.apple.com/v1/catalog/{storefront}/songs/{id}';
    private $headerAppleMusic =  ['headers' => []];
    private $urlITunes = 'https://itunes.apple.com/lookup';
    private $queryITunesTop = ['entity' => 'song', 'amgArtistId' => ''];
/**
 * @param \GuzzleHttp\Client $http
 * @param String $token the apple token for authorization
 */
    public function __construct(\GuzzleHttp\Client $http)
    {
        $this->http = $http;
    }
/**
 * @param int $songId
 * @param String $storefront An iTunes Store territory, specified by an ISO 3166 alpha-2 country code 
 * possible values: https://help.apple.com/itc/musicspec/?lang=en#/itc740f60829 
 */
    public function getArtistBySongId(int $songId, String $storefront)
    {
        $song = getSongById($songId, $storefront);

        if ($song->hasError()) {
            return false;
        }
        $artists = [];
        foreach ($song->getArtistsIds() as $id) {
            $artist = $this->getArtistById($id);

            if (!$artist->hasError()) {
                $artists[] = $artist;
            }
        }

        return $artists;
    }

/**
 * @param int $songId
 * @param String $storefront An iTunes Store territory, specified by an ISO 3166 alpha-2 country code 
 * possible values: https://help.apple.com/itc/musicspec/?lang=en#/itc740f60829 
 */
    public function getSongById(int $songId, String $storefront, $rawData = false)
    {
        $url = str_replace('{storefront}', $storefront, $this->urlApppleMusic);
        $url = str_replace('{id}', $songId, $url);

        try {
            $res = $http->request('GET', $url, $this->headerAppleMusic);
            $body = $res->getBody();
            if ($rawData) {
                return $body->getContents();
            }
            return Song::getInstance($body->getContents());
        } catch (Exceptio $e) {
            if ($rawData) {
                return false;
            }
            return App\Song::getInstanceError();
        }
    }

/**
 * @param int $artistId
 * @param boolean if is value is true method return raw data
 * @return App\Artist or string, or false 
 */
    public function getArtistById(int $artistId, $rawData = false)
    {
        try {
            $res = $this->http->request('GET', $this->urlITunes, ['query' => ['id' => $artistId]]);
            $body = $res->getBody();

            if ($rawData) {
                return $body->getContents();
            }
           $artist = Artist::getInstance($body->getContents());
           $top = $this->getTopArtistByAmgIdArtist($artist->getAmgArtistId());
            if ($top) {
                $artist->setTop($top);
            }
            return $artist;

        } catch (Exception $e) {
            if ($rawData) {
                return false;
            }
            return App\Artist::getInstanceError();
        }
    }

/**
 * @param int $amgArtistId All Music Guide (AMG) https://affiliate.itunes.apple.com/resources/documentation/itunes-store-web-service-search-api/
 */
    public function getTopArtistByAmgIdArtist(int $amgArtistId, $rawData = false)
    {
        $query = $this->queryITunesTop;
        $query['amgArtistId'] = $amgArtistId;
        try {
            $res = $this->http->request('GET', $this->urlITunes, ['query' => $query]);
            $body = $res->getBody();

            if ($rawData) {
                return $body->getContents();
            }

            return Top::getInstanceList($body->getContents());
        } catch (Exception $e) {
            return false;
        }
    }

    public function setQueryITunesTop($key, $val)
    {
        $this->queryITunesTop[$key] = $val;
    }

/**
 * @param String $key
 * @param String key
 * setting headers for request apple music
 */
    public function setHeaderAppleMusic(String $key, String $value)
    {
        $this->headerAppleMusic['headers'][$key] = $value;
    }
}

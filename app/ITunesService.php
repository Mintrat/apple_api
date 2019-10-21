<?php

namespace App;

use GuzzleHttp\Exception\GuzzleException;

class ITunesService
{
    private $http;
    private $urlAppleMusic = 'https://api.music.apple.com/v1/catalog/%s/songs/%d';
    private $headerAppleMusic = ['headers' => []];
    private $urlITunes = 'https://itunes.apple.com/lookup';
    private $queryITunesTop = ['entity' => 'song', 'amgArtistId' => ''];

    /**
     * @param \GuzzleHttp\Client $http
     */
    public function __construct(\GuzzleHttp\Client $http)
    {
        $this->http = $http;
    }

    /**
     * @param int $songId
     * @param String $storefront An iTunes Store territory, specified by an ISO 3166 alpha-2 country code
     * possible values: https://help.apple.com/itc/musicspec/?lang=en#/itc740f60829
     * @return array|bool
     */
    public function getArtistBySongId(int $songId, String $storefront)
    {
        $song = $this->getSongById($songId, $storefront);

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
     * @return AppleMusicSongDTO
     */
    public function getSongById(int $songId, String $storefront)
    {
        $rawData = $this->getSongByIdRaw($songId, $storefront);
        return AppleMusicSongDTO::getInstance($rawData);
    }

    /**
     * @param int $songId
     * @param String $storefront
     * @return bool|string
     */
    public function getSongByIdRaw(int $songId, String $storefront)
    {
        $url = sprintf($this->urlAppleMusic, $storefront, $songId);
        try {
            $res = $this->http->request('GET', $url, $this->headerAppleMusic);
            $body = $res->getBody();
            $contents = $body->getContents();
        } catch (GuzzleException $e) {
            $contents = false;
        }
        return $contents;
    }

    /**
     * @param int $artistId
     * @return ArtistDTO
     */
    public function getArtistById(int $artistId)
    {
        $rawData = $this->getArtistByIdRaw($artistId);

        $artist = ArtistDTO::getInstance($rawData);
        if ($artist->hasError()) {
            return $artist;
        }
        $top = $this->getTopArtistByAmgIdArtist($artist->getAmgArtistId());
        if ($top) {
            $artist->setTop($top);
        }
        return $artist;
    }

    /**
     * @param int $artistId
     * @return bool|string
     */
    public function getArtistByIdRaw(int $artistId)
    {
        try {
            $res = $this->http->request('GET', $this->urlITunes, ['query' => ['id' => $artistId]]);
            $body = $res->getBody();
            $contents = $body->getContents();
        } catch (GuzzleException $e) {
            $contents = false;
        }
        return $contents;
    }

    /**
     * @param int $amgArtistId All Music Guide (AMG) https://affiliate.itunes.apple.com/resources/documentation/itunes-store-web-service-search-api/
     * @return TopDTO|bool
     */
    public function getTopArtistByAmgIdArtist(int $amgArtistId)
    {
        $rawData = $this->getTopArtistByAmgIdArtistRaw($amgArtistId);
        return TopDTO::getInstanceList($rawData);
    }

    /**
     * @param int $amgArtistId
     * @return bool|string
     */
    public function getTopArtistByAmgIdArtistRaw(int $amgArtistId)
    {
        $query = $this->queryITunesTop;
        $query['amgArtistId'] = $amgArtistId;
        try {
            $res = $this->http->request('GET', $this->urlITunes, ['query' => $query]);
            $body = $res->getBody();
            $contents = $body->getContents();
        } catch (GuzzleException $e) {
            $contents = false;
        }

        return $contents;
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

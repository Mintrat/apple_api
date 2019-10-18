<?php

namespace App;

class Artist
{
    private $error = true;
    private $id;
    private $name;
    private $amgArtistId;
    private $top = [];

    static function getInstance(String $data)
    {
        if ($data) {
            $params = static::parseData($data);
            return $params ? new static($params, false) : static::getInstanceError();
        }

        return static::getInstanceError();
    }

    /**
 * @param Strring $data 
 * @return array parameters for create object or false
 */
private static function parseData(String $data)
{
    $error = false;
    $dataJsone = json_decode($data);

    if ($dataJsone && $dataJsone->resultCount > 1) {
        $params = ['artist' => [], 'top' => []];

        $params['artist']['id'] = $dataJsone->results[0]->artistId;
        $params['artist']['amgArtistId'] = $dataJsone->results[0]->amgArtistId;
        $params['artist']['name'] = $dataJsone->results[0]->artistName;

        for ($i = 1, $count = $dataJsone->resultCount; $i < $count; ++$i) {
            $song['id'] = $dataJsone->results[$i]->trackId;
            $song['title'] = $dataJsone->results[$i]->trackName;
            $params['top'] = $song;
        }

        return $params;

    } else {
        return false;
    }
}

    static function getInstanceError()
    {
        return new static('');
    }

    private function __construct($params, $error = true)
    {
        $this->error = $error;

        if (!$error) {
            $this->setParams($params);
        }
    }

    function setParams($params)
    {
        foreach ($params as $key => $val) {
            $this->$key = $val;
        }
    }

    function getId()
    {
        return $this->id;
    }

    function getAmgArtistId()
    {
        return $this->amgArtistId;
    }

    function getName()
    {
        return $this->name;
    }
    
    function getTop()
    {
        return $this->top;
    }

    function hasError()
    {
        return $this->error;
    }
}
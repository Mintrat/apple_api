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

            if ($params) {
                return new static($params, false);
            }
            return static::getInstanceError();
        }
        return static::getInstanceError();
    }

    /**
 * @param Strring $data 
 * @return array parameters for create object or false
 */
private static function parseData(String $data)
{
    $dataJsone = json_decode($data);

    if ($dataJsone && $dataJsone->resultCount) {
        $params = [];

        $params['id'] = $dataJsone->results[0]->artistId;
        $params['amgArtistId'] = $dataJsone->results[0]->amgArtistId;
        $params['name'] = $dataJsone->results[0]->artistName;

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

    public function setParams($params)
    {
        foreach ($params as $key => $val) {
            $this->$key = $val;
        }
    }

    public function setTop(\App\Top $top)
    {
        $this->top = $top;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAmgArtistId()
    {
        return $this->amgArtistId;
    }

    public function getName()
    {
        return $this->name;
    }
    
    public function getTop()
    {
        return $this->top;
    }

    public function hasError()
    {
        return $this->error;
    }
}
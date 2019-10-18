<?php

namespace App;

class Song
{
    private $error;
    private $id;
    private $title;
    private $aristsIds;

    /**
     * @param String $data parameters of song
     * @return App\Song 
     */
    
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

        if ($dataJsone && $dataJsone->data[0]) {
            $params = [];

            $params['title'] = $dataJsone->data[0]->attributes->name;
            $params['id'] = $dataJsone->data[0]->id;
            $params['artistsIds'] = [];

            foreach ($dataJsone->data[0]->relationships->artists->data as $artist) {
                $params['artistsIds'][] = (int) $artist->id;
            }

            foreach ($params as $param) {
                if (!$param) {
                    $error = true;
                    break;
                }
            }

            return $error ? false : $params;

        } else {
            return false;
        }
    }

/**
 * @return App\Song with error
 */
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
/**
 * @param array $params 
 * set property of this object
 */
    private function setParams($params)
    {
        foreach ($params as $key => $val) {
            $this->$key = $val;
        }
    }

    function getArtistsIds()
    {
        return $this->artistsIds;
    }

    function getId()
    {
        return (int) $this->id;
    }

    function getTitle()
    {
        return $this->title;
    }

    function hasError()
    {
        return $this->error;
    }
}
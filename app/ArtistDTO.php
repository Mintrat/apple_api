<?php

namespace App;

class ArtistDTO
{
    private $error;
    private $id;
    private $name;
    private $amgArtistId;
    private $top;

    /**
     * @param String $data
     * @return ArtistDTO
     */
    public static function getInstance(String $data)
    {
        if ($data) {
            $params = static::parseData($data);

            if ($params) {
                return new static($params);
            }
            return static::getInstanceError();
        }
        return static::getInstanceError();
    }

    /**
     * @param String $data
     * @return array|boolean parameters for create object or false
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

    /**
     * @return ArtistDTO with error
     */
    public static function getInstanceError()
    {
        return new static('', true);
    }

    /**
     * ArtistDTO constructor.
     * @param mixed $params
     * @param bool $error
     */
    private function __construct($params, $error = false)
    {
        $this->error = $error;


        if (!$error) {
            $this->id = $params['id'];
            $this->amgArtistId = $params['amgArtistId'];
            $this->name = $params['name'];
        }
    }

    /**
     * @param Top $top
     */
    public function setTop(Top $top)
    {
        $this->top = $top;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getAmgArtistId()
    {
        return $this->amgArtistId;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return Top
     */
    public function getTop()
    {
        return $this->top;
    }

    /**
     * @return bool
     */
    public function hasError()
    {
        return $this->error;
    }
}
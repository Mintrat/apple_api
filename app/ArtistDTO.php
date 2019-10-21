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
            $artist = static::parseData($data);
        }
        if (!$artist) {
            $artist = static::getInstanceError();
        }
        return $artist;
    }

    /**
     * @param String $data
     * @return ArtistDTO|boolean parameters for create object or false
     */
    private static function parseData(String $data)
    {
        $dataJsone = json_decode($data);

        if ($dataJsone && $dataJsone->resultCount) {

            $id = $dataJsone->results[0]->artistId;
            $amgArtistId = $dataJsone->results[0]->amgArtistId;
            $name = $dataJsone->results[0]->artistName;

            return new static($id, $amgArtistId, $name);

        } else {
            return false;
        }
    }

    /**
     * @return ArtistDTO with error
     */
    public static function getInstanceError()
    {
        return new static(null, null, null, true);
    }

    /**
     * ArtistDTO constructor.
     * @param $id
     * @param $amgArtistId
     * @param $name
     * @param bool $error
     */
    private function __construct($id, $amgArtistId, $name, $error = false)
    {
        $this->error = $error;

        if (!$error) {
            $this->id = $id;
            $this->amgArtistId = $amgArtistId;
            $this->name = $name;
        }
    }

    /**
     * @param TopDTO $top
     */
    public function setTop(TopDTO $top)
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
     * @return TopDTO
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
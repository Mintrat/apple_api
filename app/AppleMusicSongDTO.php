<?php

namespace App;

class AppleMusicSongDTO
{
    private $error;
    private $id;
    private $title;
    private $artistsIds;

    /**
     * @param String $data parameters of song
     * @return AppleMusicSongDTO
     */

    public static function getInstance(String $data)
    {
        if ($data) {
            $song = static::parseData($data);
        }

        if (!$song) {
            $song = static::getInstanceError();
        }

        return $song;
    }

    /**
     * @param String $data
     * @return AppleMusicSongDTO|boolean parameters for create object or false
     */
    private static function parseData(String $data)
    {
        $dataJsone = json_decode($data);

        if ($dataJsone && $dataJsone->data[0]) {

            $id = $dataJsone->data[0]->id;
            $title = $dataJsone->data[0]->attributes->name;
            $artistsIds = [];

            foreach ($dataJsone->data[0]->relationships->artists->data as $artist) {
                $artistsIds[] = (int)$artist->id;
            }

            return new static($id, $title, $artistsIds);

        } else {
            return false;
        }
    }

    /**
     * @return AppleMusicSongDTO with error
     */
    public static function getInstanceError()
    {
        return new static(null, null, null, true);
    }

    /**
     * AppleMusicSongDTO constructor.
     * @param $id
     * @param $title
     * @param $artistsIds
     * @param bool $error
     */
    private function __construct($id, $title, $artistsIds, $error = false)
    {
        $this->error = $error;
        if (!$error) {
            $this->id = $id;
            $this->title = $title;
            $this->artistsIds = $artistsIds;
        }
    }

    /**
     * @return array
     */
    public function getArtistsIds()
    {
        return $this->artistsIds;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return (int)$this->id;
    }

    /**
     * @return String
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return bool
     */
    public function hasError()
    {
        return $this->error;
    }
}
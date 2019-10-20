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
            $params = static::parseData($data);
            return $params ? new static($params) : static::getInstanceError();
        }

        return static::getInstanceError();
    }

    /**
     * @param String $data
     * @return array|boolean parameters for create object or false
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
                $params['artistsIds'][] = (int)$artist->id;
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
     * @return AppleMusicSongDTO with error
     */
    public static function getInstanceError()
    {
        return new static('', true);
    }

    /**
     * AppleMusicSongDTO constructor.
     * @param $params
     * @param bool $error
     */
    private function __construct($params, $error = false)
    {
        $this->error = $error;
        if (!$error) {
            $this->title = $params['title'];
            $this->id = $params['id'];
            $this->artistsIds = $params['artistsIds'];
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
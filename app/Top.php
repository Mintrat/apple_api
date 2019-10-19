<?php

namespace App;

class Top
{
    static public function getInstanceList(String $data)
    {
        $listOfSongs = [];
        $dataJsone = json_decode($data);
        $resultCount = $dataJsone->resultCount;

        if ($resultCount) {
            for ($i = 1; $i < $resultCount; ++$i) {
                $song = SongTop::getInstance($dataJsone->results[$i]);

                if ($song) {
                    $listOfSongs[] = $song;
                }
            }

            return new static($listOfSongs);
        } else {
            return false;
        }
    }

    public function __construct($top)
    {
        $this->top = $top;
    }

    public function getTop()
    {
        return $this->top;
    }
}
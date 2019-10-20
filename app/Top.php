<?php

namespace App;

class Top
{
    private $top;

    static public function getInstanceList(String $data)
    {
        $listOfSongs = [];
        $dataJsone = json_decode($data);
        $resultCount = $dataJsone->resultCount;

        if ($resultCount) {
            for ($i = 1; $i < $resultCount; ++$i) {
                $song = ITunesSong::getInstance($dataJsone->results[$i]);

                if ($song) {
                    $listOfSongs[] = $song;
                }
            }

            return new static($listOfSongs);
        } else {
            return false;
        }
    }

    private function __construct($top)
    {
        $this->top = $top;
    }

    public function getTop()
    {
        return $this->top;
    }
}
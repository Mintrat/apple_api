<?php

namespace App;

class ITunesSongDTO
{
    private $id;
    private $title;

    /**
     * @param $song
     * @return ITunesSongDTO
     */
    public static function getInstance($song)
    {
        return new static($song->trackId, $song->trackName);
    }

    /**
     * ITunesSongDTO constructor.
     * @param int $id
     * @param string $title
     */
    public function __construct(int $id, $title)
    {
        $this->id = $id;
        $this->title = $title;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }
}
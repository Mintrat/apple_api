<?php

namespace App;

class ITunesSong
{
    private $id;
    private $title;

    /**
     * @param $song
     * @return ITunesSong
     */
    public static function getInstance($song)
    {
        return new static($song->trackId, $song->trackName);
    }

    /**
     * ITunesSong constructor.
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
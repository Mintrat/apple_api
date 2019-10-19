<?php

namespace App;

class SongTop
{
    private $id;
    private $title;

    static public function getInstance($song)
    {
        return new static($song->trackId, $song->trackName);
    }

    public function __construct($id, $title)
    {
        $this->id = $id;
        $this->title = $title;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        $this->getTitle;
    }
}
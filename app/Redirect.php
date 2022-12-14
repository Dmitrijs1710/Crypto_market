<?php

namespace App;

class Redirect
{
    private string $link;

    public function __construct(string $link)
    {
        $this->link = $link;
    }

    /**
     * @return string
     */
    public function getLink(): string
    {
        return $this->link;
    }
}
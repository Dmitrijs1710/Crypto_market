<?php

namespace App;

class Template
{
    private string $link;
    private array $properties;

    public function __construct(string $link, array $properties)
    {
        $this->properties = $properties;
        $this->link = $link;
    }

    public function getLink(): string
    {
        return $this->link;
    }


    public function getProperties(): array
    {
        return $this->properties;
    }

}
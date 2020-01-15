<?php

namespace Models;

class Meta
{
    public $keywords;
    public $description;

    /**
     * Meta constructor.
     * @param string $keywords
     * @param string $description
     */
    public function __construct(
        string $keywords = null,
        string $description = null
    ) {
        $this->keywords = $keywords;
        $this->description = $description;
    }
}

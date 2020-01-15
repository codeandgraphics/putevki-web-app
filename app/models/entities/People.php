<?php

namespace Models\Entities;

class People
{
    public $adults;
    public $children = [];

    const CHILDREN_SEPARATOR = ',';

    public function __construct($people = null)
    {
        if ($people) {
            $this->adults = (int) $people->adults;

            if (is_array($people->children)) {
                $this->children = $people->children;
            }
        }
    }

    public function fromStored($people = null)
    {
        if ($people) {
            $this->adults = $people->adults
                ? (int) $people->adults
                : (int) $this->adults;
            $this->children = $people->children ?: $this->children;
        }
    }

    public function fromForm($people = null)
    {
        if ($people) {
            $this->adults = $people->adults ?: 2;

            if (
                property_exists($people, 'children') &&
                is_array($people->children) &&
                count($people->children) > 0
            ) {
                sort($people->children);
                $this->children = $people->children;
            } else {
                $this->children = [];
            }
        }
    }

    public function getChildrenString()
    {
        if (!is_array($this->children) || count($this->children) === 0) {
            return 0;
        }
        return implode(self::CHILDREN_SEPARATOR, $this->children);
    }
}

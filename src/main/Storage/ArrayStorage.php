<?php

namespace Chemisus\Storage;

class ArrayStorage implements Storage
{
    /**
     * @var mixed[]
     */
    private $entries;

    public function __construct(array $entries = array())
    {
        $this->entries = $entries;
    }

    public function entries()
    {
        return $this->entries;
    }

    public function get(array $keys)
    {
        return array_intersect_key($this->entries, array_flip($keys));
    }

    public function put(array $entries)
    {
        $this->entries = array_merge($this->entries, $entries);
    }

    public function delete(array $keys)
    {
        $this->entries = array_diff_key($this->entries, array_flip($keys));
    }
}
<?php

namespace Chemisus\Storage;

class StorageStack implements Storage
{
    /**
     * @var Storage[]
     */
    private $stack;

    /**
     * @param Storage[] $stack
     */
    public function __construct($stack)
    {
        $this->stack = $stack;
    }

    public function stack()
    {
        return $this->stack;
    }

    public function get(array $keys)
    {
        $entries = array();

        foreach ($this->stack as $storage) {
            if (!count($keys)) {
                break;
            }

            $items = $storage->get($keys);
            $entries = array_merge($entries, $items);
            $keys = array_diff($keys, array_keys($items));
        }

        return $entries;
    }

    public function put(array $entries)
    {
        foreach ($this->stack as $storage) {
            $storage->put($entries);
        }
    }

    public function delete(array $keys)
    {
        foreach ($this->stack as $storage) {
            $storage->delete($keys);
        }
    }
}

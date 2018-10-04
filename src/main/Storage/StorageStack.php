<?php

namespace Chemisus\Storage;

class StorageStack implements Storage
{
    /**
     * @var Storage[]
     */
    private $storages;

    /**
     * @param Storage[] $storages
     */
    public function __construct($storages)
    {
        $this->storages = $storages;
    }

    public function get(array $keys)
    {
        $entries = array();

        foreach ($this->storages as $storage) {
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
        foreach ($this->storages as $storage) {
            $storage->put($entries);
        }
    }

    public function delete(array $keys)
    {
        foreach ($this->storages as $storage) {
            $storage->delete($keys);
        }
    }
}

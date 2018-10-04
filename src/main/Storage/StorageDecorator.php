<?php

namespace Chemisus\Storage;

class StorageDecorator implements Storage
{
    /**
     * @var Storage
     */
    private $storage;

    /**
     * @var StorageDecoration
     */
    private $decoration;

    public function __construct(Storage $storage, StorageDecoration $decoration)
    {
        $this->storage = $storage;
        $this->decoration = $decoration;
    }

    public function get(array $keys)
    {
        $this->decoration->beforeGet($keys);
        $entries = $this->storage->get($keys);
        $this->decoration->afterGet($entries);
        return $entries;
    }

    public function put(array $entries)
    {
        $this->decoration->beforePut($entries);
        $this->storage->put($entries);
        $this->decoration->afterPut($entries);
    }

    public function delete(array $keys)
    {
        $this->decoration->beforeDelete($keys);
        $this->storage->delete($keys);
        $this->decoration->afterDelete($keys);
    }
}
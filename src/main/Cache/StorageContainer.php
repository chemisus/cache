<?php

namespace Chemisus\Cache;

use Chemisus\Storage\Storage;
use OutOfBoundsException;

class StorageContainer implements Container
{
    /**
     * @var Storage
     */
    private $storage;

    public function __construct(Storage $storage)
    {
        $this->storage = $storage;
    }

    public function storage()
    {
        return $this->storage;
    }

    public function get($key)
    {
        $entries = $this->mget(array($key));
        if (count($entries) === 0 || !array_key_exists($key, $entries)) {
            throw new OutOfBoundsException();
        }
        return $entries[$key];
    }

    public function getOr($key, $default = null)
    {
        try {
            return $this->get($key);
        } catch (OutOfBoundsException $error) {
            return $default;
        }
    }

    public function getOrPut($key, $factory)
    {
        try {
            return $this->get($key);
        } catch (OutOfBoundsException $error) {
            $value = call_user_func($factory, $key);
            $this->put($key, $value);
            return $value;
        }
    }

    public function put($key, $value)
    {
        $this->mput(array($key => $value));
        return $value;
    }

    public function delete($key)
    {
        $this->mdelete(array($key));
    }

    public function mget(array $keys)
    {
        return $this->storage->get($keys);
    }

    public function mgetOr($keys, $default = null)
    {
        return array_merge(array_fill_keys($keys, $default), $this->mget($keys));
    }

    public function mgetOrPut($keys, $factory)
    {
        $entries = $this->mget($keys);
        $missingKeys = array_diff($keys, array_keys($entries));
        if (count($missingKeys)) {
            $newEntries = call_user_func($factory, $missingKeys);
            $this->mput($newEntries);
            $entries = array_merge($entries, $newEntries);
        }
        return $entries;
    }

    public function mput($entries)
    {
        $this->storage->put($entries);
        return $entries;
    }

    public function mdelete($keys)
    {
        $this->storage->delete($keys);
    }
}

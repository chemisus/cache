<?php

namespace Chemisus\Cache;

use Chemisus\Storage\Storage;
use OutOfBoundsException;

class Container
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

    public function contains($key)
    {
        try {
            $this->get($key);
            return true;
        } catch (OutOfBoundsException $error) {
            return false;
        }
    }

    public function containsAll($keys)
    {
        return count($this->mget($keys)) === count($keys);
    }

    public function containsAny($keys)
    {
        return count($this->mget($keys)) > 0;
    }

    public function get($key)
    {
        $entries = $this->mget(array($key));
        if (count($entries) === 0 || !array_key_exists($key, $entries)) {
            throw new OutOfBoundsException();
        }
        return $entries[$key];
    }

    public function mget(array $keys)
    {
        return $this->storage->get($keys);
    }

    public function getOr($key, $default = null)
    {
        try {
            return $this->get($key);
        } catch (OutOfBoundsException $error) {
            return $default;
        }
    }

    public function mgetOr($keys, $default = null)
    {
        return array_merge(array_fill_keys($keys, $default), $this->mget($keys));
    }

    public function getOrPut($key, $factory)
    {
        try {
            return $this->get($key);
        } catch (OutOfBoundsException $error) {
            $value = call_user_func($factory);
            $this->put($key, $value);
            return $value;
        }
    }

    public function mgetOrPut($keys, $factory)
    {
        $entries = $this->mget($keys);
        $missingKeys = array_diff($keys, array_keys($entries));
        $newEntries = call_user_func($factory, $missingKeys);
        $this->mput($newEntries);
        return array_merge($entries, $newEntries);
    }

    public function put($key, $value)
    {
        $this->mput(array($key => $value));
        return $value;
    }

    public function mput($entries)
    {
        $this->storage->put($entries);
        return $entries;
    }

    public function delete($key)
    {
        $this->mdelete(array($key));
    }

    public function mdelete($keys)
    {
        $this->storage->delete($keys);
    }
}

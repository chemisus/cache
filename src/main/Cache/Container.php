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
        return count($this->gets($keys)) === count($keys);
    }

    public function containsAny($keys)
    {
        return count($this->gets($keys)) > 0;
    }

    public function get($key)
    {
        $entries = $this->gets(array($key));
        if (count($entries) === 0 || !array_key_exists($key, $entries)) {
            throw new OutOfBoundsException();
        }
        return $entries[$key];
    }

    public function gets(array $keys)
    {
        return $this->storage->get($keys);
    }

    public function getOrPut($key, callable $factory)
    {
        try {
            return $this->get($key);
        } catch (OutOfBoundsException $error) {
            $value = call_user_func($factory);
            $this->put($key, $value);
            return $value;
        }
    }

    public function put($key, $value)
    {
        $this->puts(array($key => $value));
        return $value;
    }

    public function getsOrPuts($keys, callable $factory)
    {
        $entries = $this->gets($keys);
        $missingKeys = array_diff($keys, array_keys($entries));
        $newEntries = call_user_func($factory, $missingKeys);
        $this->puts($newEntries);
        return array_merge($entries, $newEntries);
    }

    public function puts($entries)
    {
        $this->storage->put($entries);
        return $entries;
    }

    public function delete($key)
    {
        $this->deletes(array($key));
    }

    public function deletes($keys)
    {
        $this->storage->delete($keys);
    }
}

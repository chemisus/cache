<?php

namespace Chemisus\Storage;

use Memcached;

class MemcachedStorage implements Storage
{
    /**
     * @var Memcached
     */
    private $memcached;

    public function __construct(Memcached $memcached)
    {
        $this->memcached = $memcached;
    }

    public function get(array $keys)
    {
        return array_filter(
            $this->memcached->getMulti($keys),
            function ($value) {
                return $value !== false;
            }
        );
    }

    public function put(array $entries)
    {
        $this->memcached->setMulti($entries);
    }

    public function delete(array $keys)
    {
        $this->memcached->deleteMulti($keys);
    }
}

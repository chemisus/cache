<?php

namespace Chemisus\Cache;

interface Container
{
    /**
     * @param string $key
     * @return mixed
     */
    public function get($key);

    /**
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getOr($key, $default = null);

    /**
     * @param string $key
     * @param callable $factory
     * @return mixed
     */
    public function getOrPut($key, $factory);

    /**
     * @param string $key
     */
    public function delete($key);

    /**
     * @param string[] $keys
     * @return mixed[]
     */
    public function mget(array $keys);

    /**
     * @param string[] $keys
     * @param mixed $default
     * @return mixed[]
     */
    public function mgetOr($keys, $default = null);

    /**
     * @param string[] $keys
     * @param callable $factory
     * @return mixed[]
     */
    public function mgetOrPut($keys, $factory);

    /**
     * @param mixed[] $entries
     * @return mixed[]
     */
    public function mput($entries);

    /**
     * @param string[] $keys
     */
    public function mdelete($keys);
}

<?php

namespace Chemisus\Storage;

interface Storage
{
    /**
     * Returns a mapping of keys to values. Keys not found will be left out.
     *
     * @param string[] $keys
     * @return mixed[]
     */
    public function get(array $keys);

    /**
     * Puts entries in storage.
     *
     * @param array $entries
     */
    public function put(array $entries);

    /**
     * Deletes entries in storage associated by key.
     *
     * @param string[] $keys
     */
    public function delete(array $keys);
}

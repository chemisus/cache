<?php

namespace Chemisus\Storage;

class FileStorage implements Storage
{
    /**
     * @var string
     */
    private $directory;

    public function __construct($directory)
    {
        $this->directory = $directory;
    }

    public function directory()
    {
        return $this->directory;
    }

    public function file($key)
    {
        return $this->directory . '/' . urlencode($key);
    }

    public function files($keys)
    {
        if (!count($keys)) {
            return array();
        }

        return array_filter(
            array_combine($keys, array_map(
                array($this, 'file'),
                $keys
            )),
            'file_exists'
        );
    }

    public function get(array $keys)
    {
        return array_map(
            function ($file) {
                return file_get_contents($file);
            },
            $this->files($keys)
        );
    }

    public function put(array $entries)
    {
        foreach ($entries as $key => $value) {
            file_put_contents($this->file($key), $value);
        }
    }

    public function delete(array $keys)
    {
        foreach ($this->files($keys) as $file) {
            unlink($file);
        }
    }
}

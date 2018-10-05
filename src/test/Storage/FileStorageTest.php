<?php

namespace Chemisus\Storage;

class FileStorageTest extends StorageTest
{
    public function factory()
    {
        return new FileStorage(__DIR__);
    }
}

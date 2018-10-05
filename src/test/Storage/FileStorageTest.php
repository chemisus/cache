<?php

namespace Chemisus\Storage;

class FileStorageTest extends StorageTest
{
    public function factory()
    {
        return new FileStorage(__DIR__);
    }

    public function testConstruct()
    {
        $storage = new FileStorage(__DIR__);
        $this->assertEquals(__DIR__, $storage->directory());
    }
}

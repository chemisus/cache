<?php

namespace Chemisus\Storage;

class ArrayStorageTest extends StorageTest
{
    public function factory()
    {
        return new FileStorage(__DIR__);
    }
}

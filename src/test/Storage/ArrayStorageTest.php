<?php

namespace Chemisus\Storage;

class ArrayStorageTest extends StorageTest
{
    public function factory()
    {
        return new ArrayStorage();
    }

    public function testConstruct()
    {
        $entries = array('f' => 'F', 'g' => 'G');

        $storage = new ArrayStorage($entries);
        $this->assertEquals($entries, $storage->entries());
    }
}

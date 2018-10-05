<?php

namespace Chemisus\Storage;

use PHPUnit_Framework_TestCase;

abstract class StorageTest extends PHPUnit_Framework_TestCase
{
    private $keys;
    private $entries;

    public abstract function factory();

    public function data()
    {
        return array(
            array(static::factory()),
        );
    }

    protected function setUp()
    {
        parent::setUp();

        $this->keys = array("a", "b", "c", "x", "y", "z");
        $this->entries = array("a" => "A", "b" => "B", "c" => "C");
    }

    /**
     * @dataProvider data
     * @param Storage $storage
     */
    public function testStorage(Storage $storage)
    {
        self::assertEmpty($storage->get(array()), "Should get zero entries when zero keys are provided");
        self::assertEmpty($storage->get($this->keys), "Storage should be empty to begin with.");
        $storage->put($this->entries);
        self::assertNotEmpty($storage->get($this->keys), "Storage should store and return entries.");
        self::assertEquals($this->entries, $storage->get($this->keys), "Storage should return entries.");
        $storage->delete($this->keys);
        self::assertEmpty($storage->get($this->keys), "Storage should delete entries by key.");
    }
}

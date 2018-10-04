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
     * @return Storage
     */
    public function testFactory(Storage $storage)
    {
        $actual = $storage->get($this->keys);
        $this->assertEmpty($actual, "Storage should be empty to begin with.");
        return $storage;
    }

    /**
     * @dataProvider data
     * @param Storage $storage
     * @return Storage
     */
    public function testPut(Storage $storage)
    {
        $storage->put($this->entries);
        $actual = $storage->get($this->keys);
        self::assertNotEmpty($actual, "Storage should store and return entries.");
        return $storage;
    }

    /**
     * @dataProvider data
     * @param Storage $storage
     * @return Storage
     */
    public function testGet(Storage $storage)
    {
        $expect = $this->entries;
        $actual = $storage->get($this->keys);
        self::assertEquals($expect, $actual, "Storage should return entries.");
        return $storage;
    }

    /**
     * @dataProvider data
     * @param Storage $storage
     * @return Storage
     */
    public function testDelete(Storage $storage)
    {
        $storage->delete($this->keys);
        $actual = $storage->get($this->keys);
        self::assertEmpty($actual, "Storage should delete entries by key.");
        return $storage;
    }
}

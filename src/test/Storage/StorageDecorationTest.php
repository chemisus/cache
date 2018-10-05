<?php

namespace Chemisus\Storage;

use PHPUnit_Framework_TestCase;

abstract class StorageDecorationTest extends PHPUnit_Framework_TestCase
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
     * @param StorageDecoration $decoration
     */
    public function testDecoration(StorageDecoration $decoration)
    {
        $keys = $this->keys;
        $entries = $this->entries;

        $decoration->beforePut($entries);
        $decoration->afterPut($entries);

        $decoration->beforeGet($keys);
        $decoration->afterGet($entries);

        self::assertEquals($this->entries, $entries);
    }
}
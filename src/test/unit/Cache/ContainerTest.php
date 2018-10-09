<?php

namespace Chemisus\Cache;

use Chemisus\Storage\Storage;
use PHPUnit_Framework_MockObject_MockObject;
use PHPUnit_Framework_TestCase;

class ContainerTest extends PHPUnit_Framework_TestCase
{
    private $entries = array('a' => 'A', 'b' => 'B', 'c' => 'C');
    private $keys = array('a', 'b', 'c');
    private $presentEntries = array('a' => 'A', 'b' => 'B');
    private $presentKeys = array('a', 'b');
    private $missingEntries = array('c' => 'C');
    private $missingKeys = array('c');
    private $missingValue = 'C';

    /**
     * @return Storage|PHPUnit_Framework_MockObject_MockObject
     */
    public function storage()
    {
        $entries = &$this->presentEntries;
        $storage = self::getMockBuilder('Chemisus\\Storage\\Storage')->getMock();
        $storage->expects(self::any())->method('get')->will(self::returnCallback(function ($keys) use (&$entries) {
            return array_intersect_key($entries, array_flip($keys));
        }));
        return $storage;
    }

    public function testConstructor()
    {
        $storage = $this->storage();
        $container = new Container($storage);
        self::assertEquals($storage, $container->storage());
    }

    public function testMGet()
    {
        $keys = $this->keys;
        $expect = $this->presentEntries;

        $storage = $this->storage();
        $storage->expects(self::once())
            ->method('get')
            ->with($keys);

        $container = new Container($storage);

        $actual = $container->mget($keys);
        self::assertEquals($expect, $actual);
    }

    public function testMGetOr()
    {
        $keys = $this->keys;
        $expect = $this->entries;
        $default = $this->missingValue;

        $storage = $this->storage();
        $storage->expects(self::once())
            ->method('get')
            ->with($keys);

        $container = new Container($storage);

        $actual = $container->mgetOr($keys, $default);
        self::assertEquals($expect, $actual);
    }

    public function testMGetOrPut()
    {
        $keys = $this->keys;
        $expect = $this->entries;
        $default = $this->missingValue;

        $storage = $this->storage();
        $storage->expects(self::once())
            ->method('get')
            ->with($keys);
        $storage->expects(self::once())
            ->method('put')
            ->with($this->missingEntries);

        $container = new Container($storage);

        $actual = $container->mgetOrPut($keys, function ($keys) use ($default) {
            return array_fill_keys($keys, $default);
        });

        self::assertEquals($expect, $actual);
    }
}

<?php

namespace Chemisus\Cache;

use Chemisus\Storage\Storage;
use OutOfBoundsException;
use PHPUnit_Framework_MockObject_MockObject;
use PHPUnit_Framework_TestCase;

class StorageContainerTest extends PHPUnit_Framework_TestCase
{
    private $presentKey = 'a';
    private $presentValue = 'A';
    private $entries = array('a' => 'A', 'b' => 'B', 'c' => 'C');
    private $keys = array('a', 'b', 'c');
    private $presentEntries = array('a' => 'A', 'b' => 'B');
    private $presentKeys = array('a', 'b');
    private $missingEntries = array('c' => 'C');
    private $missingKeys = array('c');
    private $missingKey = 'c';
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
        $container = new StorageContainer($storage);
        self::assertEquals($storage, $container->storage());
    }

    public function testMGetSomeFound()
    {
        $keys = $this->keys;
        $expect = $this->presentEntries;

        $storage = $this->storage();
        $storage->expects(self::once())
            ->method('get')
            ->with($keys);

        $container = new StorageContainer($storage);

        $actual = $container->mget($keys);
        self::assertEquals($expect, $actual);
    }

    public function testMGetAllFound()
    {
        $keys = $this->presentKeys;
        $expect = $this->presentEntries;

        $storage = $this->storage();
        $storage->expects(self::once())
            ->method('get')
            ->with($keys);

        $container = new StorageContainer($storage);

        $actual = $container->mget($keys);
        self::assertEquals($expect, $actual);
    }

    public function testMGetNoneFound()
    {
        $keys = $this->missingKeys;
        $expect = array();

        $storage = $this->storage();
        $storage->expects(self::once())
            ->method('get')
            ->with($keys);

        $container = new StorageContainer($storage);

        $actual = $container->mget($keys);
        self::assertEquals($expect, $actual);
    }

    public function testMGetOrSomeFound()
    {
        $keys = $this->keys;
        $expect = $this->entries;
        $default = $this->missingValue;

        $storage = $this->storage();
        $storage->expects(self::once())
            ->method('get')
            ->with($keys);

        $container = new StorageContainer($storage);

        $actual = $container->mgetOr($keys, $default);
        self::assertEquals($expect, $actual);
    }

    public function testMGetOrAllFound()
    {
        $keys = $this->presentKeys;
        $expect = $this->presentEntries;
        $default = $this->missingValue;

        $storage = $this->storage();
        $storage->expects(self::once())
            ->method('get')
            ->with($keys);

        $container = new StorageContainer($storage);

        $actual = $container->mgetOr($keys, $default);
        self::assertEquals($expect, $actual);
    }

    public function testMGetOrNoneFound()
    {
        $keys = $this->missingKeys;
        $expect = $this->missingEntries;
        $default = $this->missingValue;

        $storage = $this->storage();
        $storage->expects(self::once())
            ->method('get')
            ->with($keys);

        $container = new StorageContainer($storage);

        $actual = $container->mgetOr($keys, $default);
        self::assertEquals($expect, $actual);
    }

    public function testMGetOrPutSomeFound()
    {
        $keys = $this->keys;
        $expect = $this->entries;
        $default = $this->missingValue;
        $called = false;
        $factory = function ($keys) use (&$called, $default) {
            $called = true;
            return array_fill_keys($keys, $default);
        };

        $storage = $this->storage();
        $storage->expects(self::once())
            ->method('get')
            ->with($keys);
        $storage->expects(self::once())
            ->method('put')
            ->with($this->missingEntries);

        $container = new StorageContainer($storage);

        $actual = $container->mgetOrPut($keys, $factory);

        self::assertTrue($called);
        self::assertEquals($expect, $actual);
    }

    public function testMGetOrPutAllFound()
    {
        $keys = $this->presentKeys;
        $expect = $this->presentEntries;
        $default = $this->missingValue;
        $called = false;
        $factory = function ($keys) use (&$called, $default) {
            $called = true;
            return array_fill_keys($keys, $default);
        };

        $storage = $this->storage();
        $storage->expects(self::once())
            ->method('get')
            ->with($keys);
        $storage->expects(self::never())
            ->method('put');

        $container = new StorageContainer($storage);

        $actual = $container->mgetOrPut($keys, $factory);

        self::assertFalse($called);
        self::assertEquals($expect, $actual);
    }

    public function testMGetOrPutNoneFound()
    {
        $keys = $this->missingKeys;
        $expect = $this->missingEntries;
        $default = $this->missingValue;
        $called = false;
        $factory = function ($keys) use (&$called, $default) {
            $called = true;
            return array_fill_keys($keys, $default);
        };

        $storage = $this->storage();
        $storage->expects(self::once())
            ->method('get')
            ->with($keys);
        $storage->expects(self::once())
            ->method('put')
            ->with($this->missingEntries);

        $container = new StorageContainer($storage);

        $actual = $container->mgetOrPut($keys, $factory);

        self::assertTrue($called);
        self::assertEquals($expect, $actual);
    }

    public function testMPut()
    {
        $entries = $this->entries;
        $expect = $this->entries;

        $storage = $this->storage();
        $storage->expects(self::once())
            ->method('put')
            ->with($entries);

        $container = new StorageContainer($storage);

        $actual = $container->mput($entries);
        self::assertEquals($expect, $actual);
    }

    public function testMDelete()
    {
        $keys = $this->keys;

        $storage = $this->storage();
        $storage->expects(self::once())
            ->method('delete')
            ->with($keys);

        $container = new StorageContainer($storage);

        $container->mdelete($keys);
    }

    public function testGetFound()
    {
        $key = $this->presentKey;
        $keys = array($key);
        $expect = $this->presentValue;

        $storage = $this->storage();
        $storage->expects(self::once())
            ->method('get')
            ->with($keys);

        $container = new StorageContainer($storage);

        $actual = $container->get($key);
        self::assertEquals($expect, $actual);
    }

    /**
     * @expectedException OutOfBoundsException
     */
    public function testGetMissing()
    {
        $key = $this->missingKey;
        $keys = array($key);

        $storage = $this->storage();
        $storage->expects(self::once())
            ->method('get')
            ->with($keys);

        $container = new StorageContainer($storage);

        $container->get($key);
    }

    public function testGetOrFound()
    {
        $key = $this->presentKey;
        $keys = array($key);
        $default = null;
        $expect = $this->presentValue;

        $storage = $this->storage();
        $storage->expects(self::once())
            ->method('get')
            ->with($keys);

        $container = new StorageContainer($storage);

        $actual = $container->getOr($key, $default);
        self::assertEquals($expect, $actual);
    }

    public function testGetOrMissing()
    {
        $key = $this->missingKey;
        $keys = array($key);
        $default = $this->missingValue;
        $expect = $this->missingValue;

        $storage = $this->storage();
        $storage->expects(self::once())
            ->method('get')
            ->with($keys);

        $container = new StorageContainer($storage);

        $actual = $container->getOr($key, $default);
        self::assertEquals($expect, $actual);
    }

    public function testGetOrPutFound()
    {
        $key = $this->presentKey;
        $keys = array($key);
        $expect = $this->presentValue;
        $default = null;
        $called = false;
        $factory = function ($key) use (&$called, $default) {
            $called = true;
            return $default;
        };

        $storage = $this->storage();
        $storage->expects(self::once())
            ->method('get')
            ->with($keys);
        $storage->expects(self::never())
            ->method('put');

        $container = new StorageContainer($storage);

        $actual = $container->getOrPut($key, $factory);
        self::assertFalse($called);
        self::assertEquals($expect, $actual);
    }

    public function testGetOrPutMissing()
    {
        $key = $this->missingKey;
        $keys = array($key);
        $expect = $this->missingValue;
        $default = $this->missingValue;
        $called = false;
        $factory = function ($key) use (&$called, $default) {
            $called = true;
            return $default;
        };

        $storage = $this->storage();
        $storage->expects(self::once())
            ->method('get')
            ->with($keys);
        $storage->expects(self::once())
            ->method('put')
            ->with($this->missingEntries);

        $container = new StorageContainer($storage);

        $actual = $container->getOrPut($key, $factory);
        self::assertTrue($called);
        self::assertEquals($expect, $actual);
    }

    public function testDelete()
    {
        $key = $this->missingKey;

        $storage = $this->storage();
        $storage->expects(self::once())
            ->method('delete')
            ->with($this->missingKeys);

        $container = new StorageContainer($storage);

        $container->delete($key);
    }
}

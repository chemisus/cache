<?php

namespace Chemisus\Cache;

use PHPUnit_Framework_TestCase;

class ContainerTest extends PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $storage = self::getMockBuilder('Chemisus\\Storage\\Storage')->getMock();
        $container = new Container($storage);
        self::assertEquals($storage, $container->storage());
    }

    public function testMGet()
    {
        $entries = array('a' => 'A', 'b' => 'B');
        $keys = array_keys($entries);
        $expect = $entries;

        $storage = self::getMockBuilder('Chemisus\\Storage\\Storage')->getMock();
        $storage->expects(self::once())->method('get')->with($keys)->will(self::returnValue($entries));

        $container = new Container($storage);

        $actual = $container->mget($keys);
        self::assertEquals($expect, $actual);
    }

    public function testMGetWithMissing()
    {
        $entries = array('a' => 'A', 'b' => 'B');
        $keys = array_merge(array_keys($entries), array('c'));
        $expect = $entries;

        $storage = self::getMockBuilder('Chemisus\\Storage\\Storage')->getMock();
        $storage->expects(self::once())->method('get')->with($keys)->will(self::returnValue($entries));

        $container = new Container($storage);

        $actual = $container->mget($keys);
        self::assertEquals($expect, $actual);
    }

    public function testMGetWithEmpty()
    {
        $entries = array();
        $keys = array_keys($entries);
        $expect = $entries;

        $storage = self::getMockBuilder('Chemisus\\Storage\\Storage')->getMock();
        $storage->expects(self::once())->method('get')->with($keys)->will(self::returnValue($entries));

        $container = new Container($storage);

        $actual = $container->mget($keys);
        self::assertEquals($expect, $actual);
    }

    public function testMGetOr()
    {
        $entries = array('a' => 'A', 'b' => 'B');
        $keys = array_keys($entries);
        $default = 5;
        $expect = $entries;

        $storage = self::getMockBuilder('Chemisus\\Storage\\Storage')->getMock();
        $storage->expects(self::once())->method('get')->with($keys)->will(self::returnValue($entries));

        $container = new Container($storage);

        $actual = $container->mgetOr($keys, $default);
        self::assertEquals($expect, $actual);
    }

    public function testMGetOrWithMissing()
    {
        $entries = array('a' => 'A', 'b' => 'B');
        $keys = array_merge(array_keys($entries), array('c'));
        $default = 5;
        $expect = array_merge($entries, array('c' => $default));

        $storage = self::getMockBuilder('Chemisus\\Storage\\Storage')->getMock();
        $storage->expects(self::once())->method('get')->with($keys)->will(self::returnValue($entries));

        $container = new Container($storage);

        $actual = $container->mgetOr($keys, $default);
        self::assertEquals($expect, $actual);
    }

    public function testMGetOrWithEmpty()
    {
        $entries = array();
        $keys = array_keys($entries);
        $default = 5;
        $expect = $entries;

        $storage = self::getMockBuilder('Chemisus\\Storage\\Storage')->getMock();
        $storage->expects(self::once())->method('get')->with($keys)->will(self::returnValue($entries));

        $container = new Container($storage);

        $actual = $container->mgetOr($keys, $default);
        self::assertEquals($expect, $actual);
    }

    public function testMGetOrPut()
    {
        $entries = array('a' => 'A', 'b' => 'B');
        $keys = array_keys($entries);
        $expect = $entries;

        $storage = self::getMockBuilder('Chemisus\\Storage\\Storage')->getMock();
        $storage->expects(self::once())->method('get')->with($keys)->will(self::returnValue($entries));

        $callback = function () use ($entries) {
            return $entries;
        };

        $container = new Container($storage);

        $actual = $container->mgetOrPut($keys, $callback);
        self::assertEquals($expect, $actual);
    }

//    public function testMGetOrPutWithMissing()
//    {
//        $entries = array('a' => 'A', 'b' => 'B');
//        $keys = array_merge(array_keys($entries), array('c'));
//        $default = 5;
//        $expect = array_merge($entries, array('c' => $default));
//
//        $storage = self::getMockBuilder('Chemisus\\Storage\\Storage')->getMock();
//        $storage->expects(self::once())->method('get')->with($keys)->will(self::returnValue($entries))
//
//        $container = new Container($storage);
//
//        $actual = $container->mgetOrPut($keys, $default);
//        self::assertEquals($expect, $actual);
//    }
//
//    public function testMGetOrPutWithEmpty()
//    {
//        $entries = array();
//        $keys = array_keys($entries);
//        $default = 5;
//        $expect = $entries;
//
//        $storage = self::getMockBuilder('Chemisus\\Storage\\Storage')->getMock();
//        $callback = self::callback(function () {
//        });
//        $storage->expects(self::once())->method('get')->with($keys)->will(self::returnValue($entries))
//
//        $container = new Container($storage);
//
//        $actual = $container->mgetOrPut($keys, $callback);
//        self::assertEquals($expect, $actual);
//    }
}
